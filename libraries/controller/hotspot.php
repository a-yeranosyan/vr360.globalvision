<?php

defined('_VR360_EXEC') or die;

/**
 * Hotspot controller class
 *
 * @since   2.1.0
 */
class Vr360ControllerHotspot extends Vr360Controller
{
	/**
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function ajaxSaveHotspot()
	{
		$ajax  = Vr360AjaxResponse::getInstance();
		$input = Vr360Factory::getInput();

		// Get tour by ID
		$tour = Vr360ModelTour::getInstance()->getItem();

		// Tour not found
		if (!$tour)
		{
			$ajax->addDanger(\Joomla\Language\Text::_('HOTSPOT_NOTICE_TOUR_IS_NOT_AVAILABLE'))->fail()->respond();
		}

		// Hotspots
		$hotspotsList = json_decode($input->getString('hotspotList'), true);

		// Default view
		$defaultViewsList = json_decode($input->getString('defaultViewList'), true);

		if ($hotspotsList === null || $defaultViewsList === null)
		{
			$ajax->addWarning(\Joomla\Language\Text::_('HOTSPOT_NOTICE_TOUR_IS_NOT_AVAILABLE'))->fail()->respond();
		}

		// Get all scenes of current tour
		$scenes = $tour->getScenes();

		$hotspotModel = Vr360ModelHotspot::getInstance();
		$hotspotModel->saveDefaultView($scenes, $defaultViewsList);
		$hotspotModel->saveHotspot($scenes, $hotspotsList);

		Vr360ModelTour::getInstance()->modifyXML($tour);

		$ajax->addSuccess(\Joomla\Language\Text::_('GENERAL_NOTICE_SAVED_SUCCESS'))->success()->respond();
	}
}
