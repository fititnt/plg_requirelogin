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
     * Do something onAfterInitialize
     */
    function onAfterInitialise()
    {
        $db = & JFactory::getDBO();
        //$db->debug(1);
    }

    /**
     * Do something onAfterRender
     */
    function onAfterRender() 
    {

        //load user info
        $user = & JFactory::getUser();
        $app = & JFactory::getApplication();
        return true;
    }

}