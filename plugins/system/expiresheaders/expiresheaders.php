<?php
/**
 * @copyright	Copyright (C) 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemExpiresHeaders extends JPlugin
{
       var $site = false;
       var $expireInfo = null;
       var $menu = null;
       var $itemid = null;
       var $defaultid = null;
       var $expired = null;
       function plgSystemExpiresHeaders( &$subject, $config )
        {
           $this->site = JFactory::getApplication()->isSite();
           if($this->site) {
               $this->menu =& JSite::getMenu();
               $this->defaultid = $this->menu->getDefault();
               $this->itemid = JRequest::getVar('itemid',null);
           }
           parent::__construct( $subject, $config );
        }

        function onAfterInitialise()
        {           
           if($this->itemid && $this->site) {
               $this->calcExpire();
               $this->expire($this->expireInfo);
           }
        }

        function onAfterDispatch() {
            if($this->site && !$this->expireInfo && !$this->expired) {
                $active = $this->menu->getActive();
                $this->itemid = $active->id;
                $this->calcExpire();
                $this->expire($this->expireInfo);
            }
        }

        function onAfterRender()
        {
           if($this->site && !$this->expired) $this->expire($this->expireInfo);
        }

	function calcExpire()
	{
		// only operate in the site
		$app = JFactory::getApplication();
		if ($app->isAdmin()) {
			return;
		}

                // if the itemid is 0 and active menu is null, we're on the homepage - setting it to the default id from the menu
                $uri = JURI::getInstance();
                if(
                        // these first two indicate that we might be on the homepage where there is no itemid
                        $this->itemid == 0 &&
                        is_null($this->menu->getActive()) &&
                        // we passed the first two tests, now two more to confirm homepage
                        (
                                $uri->current() == $uri->base() ||
                                $uri->current() == $this->defaultid->link
                        )
                ) {
                    $this->itemid = $this->defaultid->id;
                    $this->menu->setActive($itemid);
                }

		// get the custom expires rules
		$itemrules = array();
		foreach(explode('#',$this->params->get('headerrulestext')) as $itemrule) {
			$rulearray=explode(':',$itemrule);
			$itemrules[$rulearray[0]] = $rulearray;
		}

		// does this page have a rule, and does that rule indicate modification
		if (is_numeric($this->itemid) && array_key_exists($this->itemid,$itemrules) && (bool)$itemrules[$this->itemid]) {			date_default_timezone_set('GMT');
			$vars['time'] = strtotime('+'.$itemrules[$this->itemid][2].' '.$itemrules[$this->itemid][3]);
			$vars['seconds'] = $vars['time']-time();
			$vars['expireslength'] = $itemrules[$this->itemid][2];
			$vars['expiresinterval'] = $itemrules[$this->itemid][3];
			$vars['cachecontrol'] = $itemrules[$this->itemid][4];
			$vars['nostore'] = ($itemrules[$this->itemid][6] == '1')?true:false;
			$vars['mustrevalidate'] = ($itemrules[$this->itemid][7] == '1')?true:false;
			$vars['publicprivate'] = (int)$itemrules[$this->itemid][5];
			$vars['pragma'] = ($itemrules[$this->itemid][8] == '1')?true:false;
			$this->expireInfo = $vars;
		} else {
			// set defaults if enabled
			if ($this->params->get('defaultexpires')) {
				date_default_timezone_set('GMT');
				$vars['time'] = strtotime('+'.$this->params->get('defaultexpireslength').' '.$this->params->get('defaultexpiresinterval'));
				$vars['seconds'] = $vars['time']-time();
				$vars['expireslength'] = (int)$this->params->get('defaultexpireslength');
				$vars['expiresinterval'] = $this->params->get('defaultexpiresinterval');
				$vars['cachecontrol'] = (bool)$this->params->get('defaultcachecontrol');
				$vars['nostore'] = ($this->params->get('defaultnostore') == '1')?true:false;
				$vars['mustrevalidate'] = ($this->params->get('defaultmustrevalidate') == '1')?true:false;
				$vars['publicprivate'] = (int)$this->params->get('defaultpublicprivate');
				$vars['pragma'] = ($this->params->get('defaultpragma') == '1')?true:false;
				$this->expireInfo = $vars;
			}
		}
	}

	function expire($vars)
	{
                if (!$vars) {
                    return null;
                }
                $headers = JResponse::getHeaders();
                if($this->params->get('defaultexpires')) {
		// allow caching
		JResponse::allowCache(true);

		// set the target date
		if($this->needsHeader($headers,'Expires')) JResponse::setHeader('Expires',date('D, j M Y H:i:s T',$vars['time']));
                }
		// do we include the cache-control header
		if ($vars['cachecontrol']) {
			$valuearray = array();
			switch ($vars['publicprivate']) {
				case 0: $valuearray[] = 'public'; break;
				case 1: $valuearray[] = 'private'; break;
				case 2: $valuearray[] = 'no-cache'; break;
			}
			// should we set expire lengths?
			if ($vars['publicprivate'] < 2 && $vars['nostore'] != 1) {
				$valuearray[] = 'max-age='.$vars['seconds'];
				$valuearray[] = 'pre-check='.$vars['seconds'];
				$valuearray[] = 'post-check='.$vars['seconds'];
			} else {
				$valuearray[] = 'max-age=0';
				$valuearray[] = 'pre-check=0';
				$valuearray[] = 'post-check=0';
			}
			if($vars['nostore']) $valuearray[]='no-store';
			if($vars['mustrevalidate']) $valuearray[]='must-revalidate';
                        
			if($this->needsHeader($headers,'Cache-Control')) JResponse::setHeader('Cache-Control',implode(',',$valuearray));
		}
		// do we include pragma, and should we include it
		if($vars['pragma'] && $vars['publicprivate'] == 2){
			if($this->needsHeader($headers,'Pragma')) JResponse::setHeader('Pragma','no-cache');
		}
                $this->expired = true;
		return true;
	}
        private function needsHeader($headers,$search) {
            foreach($headers as $header) {
                if($header['name']==$search) return false;
            }
            return true;
        }
}
