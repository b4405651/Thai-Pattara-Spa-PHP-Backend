<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class mod_supersized2Helper {

	public static function getImages(&$params){
		
		$imgsAndCaps = array();		
		return $imgsAndCaps;
		
	}

	public static function getImagesFromFolder(&$params) { 
		$imgsAndCaps = array(); 
		$i=0;
		$imageFolder = JPATH_BASE.'/images/'.$params->get('imagefolder');

		$filesFromFolder = JFolder::files($imageFolder, '.', false, false, array('.svn', 'CVS','.DS_Store','__MACOSX','index.html'));
		
		foreach ($filesFromFolder as $image) { 
			$i++;
			$imgpath = JURI::root().'images/'.$params->get('imagefolder').'/'.$image;
			
			$listitem = "{image : '".$imgpath."', title : '', thumb : '".$imgpath."', url : ''}" ;
			
			
			array_push($imgsAndCaps, $listitem);
						
		}
		
		return $imgsAndCaps;
	}
	
}

?>