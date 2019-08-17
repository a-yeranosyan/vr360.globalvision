<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableScene
 *
 * @since   2.1.0
 */
class Vr360Scene extends Vr360TableScene
{
	/**
	 * Method for delete Scene
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function delete()
	{
		if (!$this->id)
		{
			return false;
		}

		// Delete hotspots
		if (!Vr360ModelHotspots::getInstance()->deleteBySceneId($this->get('id')))
		{
			return false;
		}

		// Delete scene
		if (!parent::delete())
		{
			return false;
		}

		$pathInfo  = pathinfo($this->file);
		$path      = VR360_PATH_DATA . '/' . $this->tourId . '/' . $this->file;
		$scenePath = VR360_PATH_DATA . '/' . $this->tourId . '/vtour/panos/' . $pathInfo['filename'];

		// Finally delete physical file
		if (!Vr360HelperFile::delete($path))
		{
			return false;
		}

		if (!Vr360HelperFile::delete($scenePath))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method for get all hotspots of scene
	 *
	 * @return  array|false  Array of hotspots. False otherwise.
	 *
	 * @since   2.1.0
	 */
	public function getHotspots()
	{
		if (!$this->id)
		{
			return false;
		}

		return Vr360ModelHotspots::getInstance()->getList($this->get('id'));
	}

	public function isValid()
	{
		return Vr360HelperFile::exists($this->getFile());
	}

	public function getFile()
	{
		return VR360_PATH_DATA . '/' . $this->tourId . '/' . $this->file;
	}
}
