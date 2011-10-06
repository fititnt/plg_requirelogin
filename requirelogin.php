<?php
/*
 * @package         plg_drequirelogin
 * @author          Emerson Rocha Luiz ( emerson@webdesign.eng.br - @fititnt -  http://fititnt.org )
 * @copyright       Copyright (C) 2005 - 2011 Webdesign Assessoria em Tecnologia da Informacao.
 * @license         GNU General Public License version 3. See license.txt
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemRequirelogin extends JPlugin {

    /**
     * Do something onAfterRender
     * 
     * @todo: is really on this System function the better way to do it? Hummm
     */
    function onAfterRender() 
    {
        $user = JFactory::getuser();
        if ($user->guest && JRequest::getCmd('option') != 'com_users'){
            $app = JFactory::getApplication();
            $app->redirect( JRoute::_('index.php?option=com_users&view=login') );
        }
        return true;
    }

}