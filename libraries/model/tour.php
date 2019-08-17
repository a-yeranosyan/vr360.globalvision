<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelTour
 *
 * @since  2.0.0
 */
class Vr360ModelTour extends Vr360Model
{
	/**
	 * @param null $alias
	 *
	 * @return boolean|Vr360Tour
	 */
	public function getItemByAlias($alias = null)
	{
		$alias = Vr360Factory::getInput()->getString('alias', $alias);

		if ($alias)
		{
			$table = new Vr360TableTour;

			if (!$table->load(array('alias' => $alias)))
			{
				return false;
			}

			$tour = new Vr360Tour;
			$tour->bind($table);

			return $tour;
		}

		return false;
	}

	/**
	 * @return  void
	 */
	public function ajaxSave()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		/**
		 * @var $tour Vr360TableTour
		 */
		$tour = $this->getItem();

		if (!$tour)
		{
			$tour = new Vr360Tour;
		}

		$tour->bind($input->post->getArray());
		$tour->params = new Vr360Object($tour->params);

		$files = Vr360Factory::getInput()->files->get('newSceneFile');

		// Check if tour is create new. Scene Files must have.
		if (!$tour->id && empty($files))
		{
			$ajax->addDanger("Missing scenes files.")->fail()->respond();
		}

		// Need to be validate files.
		if (!empty($files))
		{
			// File validate
			foreach ($files as $i => $file)
			{
				// Respond ajax if anything failed
				if (true !== Vr360HelperTour::fileValidate($file['tmp_name']))
				{
					unset($files[$i]);
					$ajax->addDanger('Invalid scene: ' . $file['name'])->fail()->respond();
				}
			}
		}

		if (!$tour->store())
		{
			$ajax->addDanger($tour->getError())->fail()->respond();
		}

		$tour->params = new Vr360Object(json_decode($tour->params));

		if ($input->getInt('id'))
		{
			// Save scene
			$ajax->addInfo(\Joomla\Language\Text::_('TOUR_NOTICE_TOUR_IS_UPDATED'))->success();
		}
		else
		{
			// Save scene
			$ajax->addInfo(\Joomla\Language\Text::_('TOUR_NOTICE_TOUR_IS_CREATED'))->success();
		}

		// Make sure tour data folder exist.
		$tourDataDir = VR360_PATH_DATA . '/' . $tour->id;

		if (!Vr360HelperFolder::exists($tourDataDir))
		{
			Vr360HelperFolder::create($tourDataDir);
		}

		// Update / delete current scenes
		$currentScenes = $this->saveScenes($tour);

		// Try to save new scenes files
		$newScenes = $this->saveNewScenes($tour, $files);

		$vTourFolder = Vr360HelperFile::clean($tourDataDir . '/vtour');

		// If there are new scenes or missing vtour folder. Regenerate with krpano program.
		if ((is_array($newScenes) && !empty($newScenes)) || !Vr360HelperFolder::exists($vTourFolder))
		{
			$newScenes = is_array($newScenes) && !empty($newScenes) ? array_merge($newScenes, $currentScenes) : $currentScenes;

			try
			{
				// Remove old folder vtour
				if (Vr360HelperFolder::exists($vTourFolder))
				{
					Vr360HelperFolder::delete($vTourFolder);
				}

				// And now generate tour
				$command = '';
				$krPano  = new Vr360Krpano(Vr360Configuration::getConfig('krPanoPath'), Vr360Configuration::getConfig('krPanoLicense'));
				$krPano->useConfigFile(Vr360Configuration::getConfig('krPanoConfigFile'));
				$krPano->addFiles($newScenes);
				$krPano->makePano($command);
			}
			catch (Exception $exception)
			{
				$ajax->addInfo($exception->getMessage());
			}
		}

		$this->modifyXML($tour, $ajax);

		if (Vr360HelperCache::deleteItem('vtour/' . $tour->alias))
		{
			$ajax->addSuccess('Cache is deleted');
		}

		$scenes = $tour->getScenes();

		if (!$scenes)
		{
			$ajax->addWarning(\Joomla\Language\Text::_('TOUR_NOTICE_NO_SCENES_PROVIDED'))->fail();
		}

		if ($ajax->isSuccess())
		{
			if ($input->getInt('id'))
			{
				Vr360Session::getInstance()->addMessage(\Joomla\Language\Text::sprintf('TOUR_NOTICE_TOUR_IS_UPDATED', $tour->get('id')), 'success');
			}
			else
			{
				Vr360Session::getInstance()->addMessage(\Joomla\Language\Text::sprintf('TOUR_NOTICE_TOUR_IS_CREATED', $tour->get('id')), 'success');
			}

			Vr360Session::getInstance()->addMessage(\Joomla\Language\Text::_('TOUR_NOTICE_SCENES_UPDATED'), 'info');
		}

		$ajax->respond();
	}

	/**
	 * @param null $id
	 *
	 * @return boolean|Vr360Tour
	 */
	public function getItem($id = null)
	{
		$id = Vr360Factory::getInput()->getInt('id', $id);

		if ($id)
		{
			$table = new Vr360TableTour;

			if (!$table->load(array('id' => $id, 'created_by' => Vr360Factory::getUser()->id)))
			{
				return false;
			}

			$tour = new Vr360Tour;
			$tour->bind($table);

			return $tour;
		}

		return false;
	}

	/**
	 * Method for store current scenes
	 *
	 * @param   Vr360Tour $tour Tour data
	 *
	 * @return  array             List of scene files.
	 *
	 * @since   2.1.0
	 */
	protected function saveScenes($tour)
	{
		$input = Vr360Factory::getInput();
		$ajax  = Vr360AjaxResponse::getInstance();

		$sceneNames        = $input->get('sceneName', array(), 'Array');
		$sceneDescriptions = $input->get('sceneDescription', array(), 'Array');
		$sceneParams       = $input->get('sceneParams', array(), 'Array');
		$sceneDefault      = $input->getInt('sceneDefault');
		$sceneIds          = $input->get('sceneId', array(), 'Array');

		$files = array();

		// Clean up current default
		$this->resetDefaultScene($tour->get('id'));

		// Get current scenes of tour
		$currentScenes = $this->getScenes($tour->get('id'));

		if (empty($currentScenes))
		{
			return $files;
		}

		foreach ($currentScenes as $currentScene)
		{
			/** @var Vr360Scene $currentScene */

			// This means user delete this scene.
			if (!in_array($currentScene->id, $sceneIds))
			{
				if (!$currentScene->delete())
				{
					$ajax->addWarning('Can not delete Scene: ' . $currentScene->name . ' [' . $currentScene->id . ']');
				}
				else
				{
					$ajax->addWarning('Delete Scene: ' . $currentScene->name . ' [' . $currentScene->id . '] success');
				}

				continue;
			}

			$currentScene->name        = $sceneNames[$currentScene->id];
			$currentScene->description = $sceneDescriptions[$currentScene->id];

			/**
			 * Because scene params can include extra params. We need manual update each one instead overwrite all
			 */
			foreach ($sceneParams[$currentScene->id] as $key => $value)
			{
				$currentScene->setParam($key, $value);
			}

			if ($currentScene->id == $sceneDefault)
			{
				$currentScene->default = 1;
			}

			if (!$currentScene->store())
			{
				$ajax->addWarning('Can not update Scene: ' . $currentScene->name . ' [' . $currentScene->id . ']');
			}
			else
			{
				$ajax->addSuccess('Update Scene: ' . $currentScene->name . ' [' . $currentScene->id . '] successful');
			}

			$files[] = VR360_PATH_DATA . '/' . $tour->id . '/' . $currentScene->file;
		}

		return $files;
	}

	/**
	 * @param   integer $tourId Tour ID
	 *
	 * @return  boolean
	 */
	public function resetDefaultScene($tourId)
	{
		$db    = Vr360Factory::getDbo();
		$query = $db->getQuery(true)
			->update($db->quoteName('scenes'))
			->set($db->quoteName('default') . ' = 0')
			->where($db->quoteName('tourId') . ' = ' . (int) $tourId);

		try
		{
			$db->setQuery($query)->execute();
		}
		catch (Exception $exception)
		{
			return false;
		}

		return true;
	}

	/**
	 * @param     $tourId
	 * @param int $publish
	 *
	 * @return boolean|array
	 */
	public function getScenes($tourId, $publish = 1)
	{
		return Vr360ModelScenes::getInstance()->getList($tourId, $publish);
	}

	/**
	 * Method for store new scenes
	 *
	 * @param   Vr360Tour $tour  Tour data
	 * @param   array     $files List of new scenes files.
	 *
	 * @return  array|false        Array of new scenes files if success. False otherwise.
	 *
	 * @since   2.1.0
	 */
	protected function saveNewScenes($tour, $files)
	{
		$input       = Vr360Factory::getInput();
		$ajax        = Vr360AjaxResponse::getInstance();
		$tourDataDir = VR360_PATH_DATA . '/' . $tour->id;

		// Just check if user upload new files.
		if (empty($files))
		{
			return false;
		}

		$uploadedFiles = array();

		try
		{
			// Okay now we can process
			$sceneNames        = $input->get('newSceneName', array(), 'Array');
			$sceneDescriptions = $input->get('newSceneDescription', array(), 'Array');
			$sceneParams       = $input->get('sceneParams', array(), 'Array');

			foreach ($files as $index => $file)
			{
				$fileName = Vr360HelperTour::generateFilename($file['name']);

				if (!move_uploaded_file($file['tmp_name'], $tourDataDir . '/' . $fileName))
				{
					continue;
				}

				$uploadedFiles[] = $tourDataDir . '/' . $fileName;

				$scene = new Vr360Scene;

				$scene->set('tourId', (int) $tour->id);
				$scene->set('name', $sceneNames[$index]);
				$scene->set('description', $sceneDescriptions[$index]);
				$scene->set('file', $fileName);
				$scene->set('params', $sceneParams);

				if (!$scene->store())
				{
					$ajax->addDanger('Can not save scene: ' . $sceneNames[$index]);
				}
				else
				{
					$ajax->addSuccess('Added new scene: ' . $sceneNames[$index] . ' successed');
				}
			}
		}
		catch (Exception $exception)
		{
			$ajax->addDanger($exception->getMessage())->fail()->respond();
		}

		return empty($uploadedFiles) ? false : $uploadedFiles;
	}

	/**
	 * Method for modify XML file with new data.
	 *
	 * @param   Vr360Tour         $tour Tour data
	 * @param   Vr360AjaxResponse $ajax Ajax response.
	 *
	 * @return  array                     List of scene files.
	 *
	 * @since   2.1.0
	 */
	public function modifyXML($tour, $ajax = null)
	{
		$ajax = null === $ajax ? Vr360AjaxResponse::getInstance() : $ajax;

		$tourDataDirPath = VR360_PATH_DATA . '/' . $tour->id . '/vtour';

		if (!Vr360HelperFolder::exists($tourDataDirPath))
		{
			$ajax->addDanger('Missing folder tour: ' . $tourDataDirPath)->fail()->respond();
		}

		$sceneFiles  = array();
		$panoFolders = array();
		$scenes      = $this->getScenes($tour->get('id'));

		// Clean up folder
		if (!empty($scenes))
		{
			foreach ($scenes as $scene)
			{
				$sceneFiles[]  = $scene->file;
				$panoFolders[] = explode('.', $scene->file)[0] . '.tiles';
			}
		}

		$files = scandir(Vr360HelperFile::clean(VR360_PATH_DATA . '/' . $tour->id));

		foreach ($files as $file)
		{
			if (!in_array($file, $sceneFiles))
			{
				Vr360HelperFile::delete(VR360_PATH_DATA . '/' . $tour->id . '/' . $file);
			}
		}

		$folders = scandir(Vr360HelperFile::clean($tourDataDirPath . '/panos'));

		foreach ($folders as $folder)
		{
			if ($folder == '.' || $folder == '..')
			{
				continue;
			}

			if (!in_array($folder, $panoFolders))
			{
				Vr360HelperFolder::delete($tourDataDirPath . '/panos/' . $folder);
			}
		}

		$xmlData = Vr360Layout::getInstance()->fetch('tour.tour', array('tour' => $tour, 'scenes' => $scenes));
		$xmlData = simplexml_load_string($xmlData);

		$dom                     = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput       = true;
		$dom->loadXML($xmlData->asXML());
		$xml = new SimpleXMLElement($dom->saveXML());

		return $xml->saveXML($tourDataDirPath . '/tour.xml');
	}

	/**
	 * @return mixed
	 */
	public function validateAlias()
	{
		$input = Vr360Factory::getInput();
		$alias = $input->getString('alias');
		$id    = $input->getInt('id');

		$db    = Vr360Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('tours'))
			->where($db->quoteName('alias') . ' = ' . $db->quote($alias))
			->where($db->quoteName('id') . ' != ' . (int) $id);

		return $db->setQuery($query)->loadResult();
	}
}
