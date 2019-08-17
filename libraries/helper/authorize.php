<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperAuthorize
 *
 * @since   2.0.0
 */
class Vr360HelperAuthorize
{
	/**
	 * @return boolean
	 */
	public static function isAuthorized()
	{
		$session = Vr360Session::getInstance();

		if ($session->get('logged') === true && $session->get('user')->id)
		{
			return true;
		}

		return false;
	}

	/**
	 * @param   string $userName
	 * @param   string $password
	 *
	 * @return  boolean
	 */
	public static function authorize($userName, $password)
	{
		if (empty($userName))
		{
			Vr360Session::getInstance()->addMessage('Invalid username');

			return false;
		}

		if (empty($password))
		{
			Vr360Session::getInstance()->addMessage('Invalid password');

			return false;
		}

		$modelUser = Vr360ModelUser::getInstance();

		return $modelUser->authorize($userName, $password);
	}
}