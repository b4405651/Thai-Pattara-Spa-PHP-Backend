<?php
/**
* @version 1.0 for Joomla! 1.6
* @copyright (C) 2011 JoomSpirit
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @Author name JoomSpirit 
* @Author website  http://www.joomspirit.com/
*/

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once(dirname(__FILE__).'/helper.php');

$url = trim( $params->get( 'url' ) );

require( JModuleHelper::getLayoutPath( 'mod_google_map' ) );