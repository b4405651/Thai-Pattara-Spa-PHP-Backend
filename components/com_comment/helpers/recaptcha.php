<?php

/*
 * Copyright Copyright (C) 2010 Daniel Dimitrov (http://compojoom.com). All rights reserved.
 * Copyright Copyright (C) 2007 Alain Georgette. All rights reserved.
 * Copyright Copyright (C) 2006 Frantisek Hliva. All rights reserved.
 * License http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * !JoomlaComment is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * !JoomlaComment is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA  02110-1301, USA.
 */

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

JLoader::register('ccommentRecaptchalib', JPATH_SITE . '/components/com_comment/classes/recaptcha/recaptcha.php');

class ccommentHelperRecaptcha
{

	public static function getHtml($publicKey)
	{
		return ccommentRecaptchalib::recaptcha_get_html($publicKey);
	}

	public static function checkAnswer($privateKey, $ip, $responseField)
	{
		$reCaptcha = new ccommentRecaptchalib($privateKey);

		$resp = $reCaptcha->verifyResponse(
			$ip,
			$responseField
		);

		return $resp->success;
	}
}
