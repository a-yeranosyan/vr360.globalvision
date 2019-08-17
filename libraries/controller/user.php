<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ControllerUser
 *
 * @since  2.0.0
 */
class Vr360ControllerUser extends Vr360Controller
{
	/**
	 * @return  void
	 */
	public function login()
	{
		$input = Vr360Factory::getInput();

		if (Vr360HelperAuthorize::authorize($input->getString('username'), $input->getString('password')))
		{
			$input->set('view', 'tours');
			$this->display();
		}
		else
		{
			Vr360Session::getInstance()->addMessage(\Joomla\Language\Text::_('USER_NOTICE_INVALID_USERNAME_OR_PASSWORD'), 'warning');
			$input->set('view', 'user');
			$this->display();
		}
	}

	/**
	 * Logout by clear session
	 *
	 * @return  void
	 *
	 * @since  2.0.0
	 */
	public function logout()
	{
		Vr360Session::getInstance()->reset();

		header("Location: " . 'index.php');
	}

	/**
	 * @return  void
	 */
	public function ajaxGetUserHtml()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning(\Joomla\Language\Text::_('USER_NOTICE_USER_IS_NOT_AUTHORIZED'))->fail()->respond();
		}

		$html = Vr360Layout::getInstance()->fetch('user.profile');

		Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
	}

	/**
	 * @return  void
	 */
	public function ajaxSaveProfile()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning(\Joomla\Language\Text::_('USER_NOTICE_USER_IS_NOT_AUTHORIZED'))->fail()->respond();
		}

		Vr360ModelUser::getInstance()->ajaxSaveProfile();
	}
}
