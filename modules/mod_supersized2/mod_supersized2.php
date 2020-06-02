<?php
/**
* @version 2.0 for Joomla! 3.X
* @copyright (C) 2013 JoomSpirit
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @Author name JoomSpirit 
* @Author website  http://www.joomspirit.com/
*/

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once(dirname(__FILE__).'/helper.php');
jimport('joomla.filesystem.folder');

$listofimages = mod_supersized2Helper::getImages($params);

if ($params->get('imagefolder') != '-1' ) {
	$folderimages = mod_supersized2Helper::getImagesFromFolder($params);
	
	foreach ($folderimages as $img)
		array_push($listofimages , $img);
}

$slideshow = trim( $params->get( 'slideshow' ) );
$stop_loop = trim( $params->get( 'stop_loop' ) );
$random = trim( $params->get( 'random' ) );
$slide_interval = trim( $params->get( 'slide_interval' ) );
$transition = trim( $params->get( 'transition' ) );
$transition_speed = trim( $params->get( 'transition_speed' ) );
$performance = trim( $params->get( 'performance' ) );
$image_protect = trim( $params->get( 'image_protect' ) );
$show_thumb = trim( $params->get( 'show_thumb' ) );
$fit_always = trim( $params->get( 'fit_always' ) );
$fit_portrait = trim( $params->get( 'fit_portrait' ) );
$fit_landscape = trim( $params->get( 'fit_landscape' ) );
$min_width = trim( $params->get( 'min_width' ) );
$min_height = trim( $params->get( 'min_height' ) );
$vertical_center = trim( $params->get( 'vertical_center' ) );
$horizontal_center = trim( $params->get( 'horizontal_center' ) );

require( JModuleHelper::getLayoutPath( 'mod_supersized2' ) );