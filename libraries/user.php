<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360User
 *
 * @since  2.1.0
 */
class Vr360User extends Vr360TableUser
{
	public function haveLogo()
	{
		return Vr360HelperFile::exists($this->getLogo());
	}

	public function getLogo()
	{
		return realpath(VR360_PATH_DATA . '/users/' . $this->get('id')) . '/logo.png';
	}

	public function getLogoUrl()
	{
		return VR360_URL_ROOT . '/_/users/' . $this->get('id') . '/logo.png';
	}
}
