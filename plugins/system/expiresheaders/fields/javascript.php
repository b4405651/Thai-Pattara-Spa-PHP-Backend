<?php
/**
 * @copyright	Copyright (C) 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldJavascript extends JFormField
{
	protected $type = 'Javascript';

	protected function getInput()
	{
		$doc = &JFactory::getDocument();
		$doc->addScript(JURI::root(true).DS.'media'.DS.'plg_expiresheaders'.DS.'expiresheaders.js');
		return;
	}
}
