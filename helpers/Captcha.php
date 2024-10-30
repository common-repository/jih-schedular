<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 21-Mar-15
 * Time: 14:29
 */

namespace helpers;


class Captcha {

	/**
	 * @param array $data the form data, it will extract: 'g-recaptcha-response'
	 *
	 * @return bool
	 */
	public static function Check($data){
//		if(self::IsValidated())
//			return true;
		$captcha = $data['g-recaptcha-response'];

		$secret = Setting::get('reCaptchaServer');
		$remoteIp = $_SERVER['REMOTE_ADDR'];
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip=$remoteIp";
		$response = file_get_contents($url);
		$answers = json_decode($response, true);
		if($answers['success'] == true){
//			self::SetValidated();
			return true;
		}
		return false;
	}

	/**
	 * Session plugin needed: https://wordpress.org/plugins/wp-session-manager/
	 * WP doesnt contain session! -.-
	 * @return bool
	 */
//	public static function IsValidated(){
//		$session = \WP_Session::get_instance();
//		return $session['G-Captcha'];
//	}
//
//	public static function SetValidated($validate = true){
//		$session = \WP_Session::get_instance();
//		$session['G-Captcha'] = $validate;
//	}
}