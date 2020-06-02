<?php
/*------------------------------------------------------------------------
# com_zhyandexmap - Zh YandexMap
# ------------------------------------------------------------------------
# author:    Dmitry Zhuk
# copyright: Copyright (C) 2011 zhuk.cc. All Rights Reserved.
# license:   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
# website:   http://zhuk.cc
# Technical Support Forum: http://forum.zhuk.cc/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

	$document->addScript($current_custom_js_path.'common-min.js');
	if (isset($use_object_manager) && (int)$use_object_manager == 1)
	{
		$document->addScript($current_custom_js_path.'objectmanager-min.js');
	}

	if (isset($compatiblemode) && (int)$compatiblemode == 1)
	{
		$document->addScript($current_custom_js_path.'compatibility-min.js');
	}
	