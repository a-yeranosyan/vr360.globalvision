<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelUser
 *
 * @since   2.0.0
 */
class Vr360ModelUser extends Vr360Model
{
	/**
	 * @param   string $userName
	 * @param   string $password
	 *
	 * @return  boolean
	 */
	public function authorize($userName, $password)
	{
		$user = $this->getItem($userName);

		if (!$user)
		{
			return false;
		}

		/**
		 * @TODO    Need improve password encryption
		 */
		if ($user->password === md5($password)
			|| $user->password === md5(Vr360Configuration::getConfig('salt') . $password)
		)
		{
			$session = Vr360Session::getInstance();
			$session->set('logged', true);
			$session->set('user', $user);

			return true;
		}
	}

	/**
	 * @param   string $userName
	 *
	 * @return  boolean|Vr360TableUser
	 */
	public function getItem($userName)
	{
		$db    = Vr360Factory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('users'))
			->where($db->quoteName('username') . ' = ' . $db->quote($userName), 'OR')
			->where($db->quoteName('email') . ' = ' . $db->quote($userName));

		$row = $db->setQuery($query)->loadObject();

		if (!$row)
		{
			return false;
		}

		$row->params = !(empty($row->params)) ? new Vr360Object(json_decode($row->params)) : new Vr360Object;
		$user        = new Vr360TableUser;
		$user->bind($row);

		return $user;
	}

	/**
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function ajaxSaveProfile()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		$user            = Vr360Factory::getUser();
		$password        = $input->getString('password');
		$confirmpassword = $input->getString('confirmpassword');

		$tableUser = new Vr360TableUser;
		$tableUser->load(array(
			'id'       => $user->id,
			'password' => md5($input->getString('currentpassword'))
		));

		if ($tableUser->id)
		{
			$tableUser->email = $input->getString('email');
			$tableUser->name  = $input->getString('name');

			if ($password && !empty($password) && $password == $confirmpassword)
			{
				$tableUser->password = md5($password);
			}
			else
			{
				if ($password && !empty($password))
				{
					$ajax->addWarning(\Joomla\Language\Text::_('USER_NOTICE_CONFIRM_PASSWORD_DOES_NOT_MATCH'))->fail()->respond();
				}
			}

			$file = Vr360Factory::getInput()->files->get('logo');

			$tableUser->params->company        = $input->getString('company');
			$tableUser->params->companyaddress = $input->getString('companyaddress');

			if ($file && !empty($file) && !empty($file['name']))
			{
				$userDataDir = VR360_PATH_DATA . '/users/' . $user->id;

				if (!Vr360HelperFolder::exists($userDataDir))
				{
					Vr360HelperFolder::create($userDataDir);
				}

				if (!move_uploaded_file($file['tmp_name'], $userDataDir . '/logo.png'))
				{
					$ajax->addDanger(\Joomla\Language\Text::_('USER_NOTICE_CAN_NOT_UPLOAD_LOGO'))->fail()->respond();
				}

				$tableUser->params->logo = 'logo.png';
			}

			if ($tableUser->store())
			{
				$session = Vr360Session::getInstance();
				$session->set('user', $tableUser);

				$ajax->addSuccess(\Joomla\Language\Text::_('USER_NOTICE_USER_PROFILE_UPDATED_SUCCESS'))->success()->respond();
			}
		}

		$ajax->addDanger(\Joomla\Language\Text::_('USER_NOTICE_USER_PROFILE_UPDATED_FAIL'))->fail()->respond();
	}
}
