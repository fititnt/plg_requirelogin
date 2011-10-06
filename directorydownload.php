<?php
/**
 * @package         mod_directorydownload
 * @author          Emerson Rocha Luiz ( emerson@webdesign.eng.br - http://fititnt.org )
 * @copyright       Copyright (C) 2005 - 2011 Webdesign Assessoria em Tecnologia da Informacao.
 * @license         GNU General Public License version 3. See license.txt
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * GetGitHub Content Plugin
 *
 * @since		1.6
 */
class plgContentDirectorydownload extends JPlugin
{
    /**
     * Example prepare content method
     *
     * Method is called by the view
     *
     * @param	string          The context of the content being passed to the plugin.
     * @param	object          The content object.  Note $article->text is also available
     * @param	object          The content params
     * @param	int		The 'page' number
     * @since	1.6
     */
    public function onContentPrepare($context, &$article, &$params, $limitstart)
    {
            $app = JFactory::getApplication();

            // simple performance check to determine whether bot should process further
            $tagname = $this->get('tagname', 'github');
            if (strpos($article->text, $tagname) === false) {
                    return true;
            }

            // expression to search
            // {github}https://raw.github.com/example...{/github}
            // @todo: rewrite to make able to ask start and end lines
            $regex		= '~{'.$tagname.'}(.*?){/'.$tagname.'}~i'; 
            $matches	= array();

            // find all instances of plugin and put in $matches
            preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                    // $match[0] is full pattern match, $match[1] is the url
                    $code = $this->_getDownloadHtml($match[1]);
                    // We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
                    $article->text = preg_replace("|$match[0]|", $code, $article->text, 1);
            }
            return '';
    }

    /*
     * 
     */
    private function _getDownloadHtml($path){

    }
        
    /*
     * Parse directory in to one object, recursively
     * @todo: think if is really a good ideal parse a tree of directories instead
     * of just one directory
     * 
     * @return      object
     */
    private function _parseDirectoryTree( $directory = NULL )
    {
        $directory = $this->_clearDirectory( $directory );

        if(!$directory){
            return FALSE;
        }

        $info = array();
        $dir = opendir( $directory );
        while ( ( $filePointer = readdir($dir)) !== FALSE )
        {
            if( $filePointer != '.' && $filePointer != '..' )
            {
                $path = $directory .'/'.$filePointer;
                if( is_readable($path) )//Be sure if file or path is readable before try parse it
                {
                    $subdirs = explode('/',$path);
                    if(is_dir($path))
                    {
                        $info[] = array(
                            'path'          => $path,
                            'type'          => 'directory',
                            'name'          => end($subdirs),
                            'permission'    => substr( decoct( fileperms($path) ), 1),
                            'content'       => $this->_parseDirectoryTree( $path )
                        );
                    }elseif(is_file($path))
                    {
                        $ext = substr( strrchr( end($subdirs),'.' ),1 );
                        $info[] = array(
                            'path'          => $path,
                            'type'          => $ext,
                            'name'          => end($subdirs),
                            'edited'        => date ("Y:m:d H:i:s", filemtime($path)),
                            'size'          => filesize($path)
                        );
                    }
                }
            }
        }
        closedir($dir);
        return $info;
    }
        
}
