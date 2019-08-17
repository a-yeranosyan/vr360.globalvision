<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ControllerTour
 */
class Vr360ControllerTour extends Vr360Controller
{
	/**
	 * @return  void
	 */
	public function ajaxSaveTour()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning(\Joomla\Language\Text::_('USER_NOTICE_USER_IS_NOT_AUTHORIZED'))->fail()->respond();
		}

		Vr360ModelTour::getInstance()->ajaxSave();
	}

	/**
	 * Method for load create/edit tour html form
	 *
	 * @since   2.1.0
	 *
	 * @return  void
	 */
	public function ajaxGetTourHtml()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning(\Joomla\Language\Text::_('USER_NOTICE_USER_IS_NOT_AUTHORIZED'))->fail()->respond();
		}

		$tour = Vr360ModelTour::getInstance()->getItem();

		if ($tour)
		{
			$html = Vr360Layout::getInstance()->fetch('form.tour', array('tour' => $tour));
		}
		else
		{
			$html = Vr360Layout::getInstance()->fetch('form.tour', array('tour' => new Vr360Tour));
		}

		Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
	}

	/**
	 * @return  void
	 */
	public function ajaxGetTourEmbedHtml()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning(\Joomla\Language\Text::_('USER_NOTICE_USER_IS_NOT_AUTHORIZED'))->fail()->respond();
		}

		$tour = Vr360ModelTour::getInstance()->getItem();

		if ($tour)
		{
			$html = Vr360Layout::getInstance()->fetch('tour.embed', array('tour' => $tour));
			Vr360AjaxResponse::getInstance()->addData('html', $html)->success()->respond();
		}
	}

	/**
	 * @return  void
	 */
	public function ajaxDeleteTour()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning(\Joomla\Language\Text::_('USER_NOTICE_USER_IS_NOT_AUTHORIZED'))->fail()->respond();
		}

		$input = Vr360Factory::getInput();
		$tour  = Vr360ModelTour::getInstance()->getItem();

		if (!$tour)
		{
			$ajax->addWarning(\Joomla\Language\Text::_('TOUR_NOTICE_TOUR_IS_NOT_AVAILABLE'))->fail()->respond();
		}

		if (!$tour->delete())
		{
			$ajax->addDanger(\Joomla\Language\Text::sprintf('GENERAL_NOTICE_DELETED_FAIL', $input->getInt('id')))->fail()->respond();
		}

		Vr360Session::getInstance()->addMessage(\Joomla\Language\Text::sprintf('GENERAL_NOTICE_DELETED_SUCCESS', $input->getInt('id')),'success');
		$ajax->success()->respond();
	}

	/**
	 * @return  void
	 */
	public function ajaxGetHotspotEditorHtml()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		// Permission verify
		if (!Vr360HelperAuthorize::isAuthorized())
		{
			$ajax->addWarning(\Joomla\Language\Text::_('USER_NOTICE_USER_IS_NOT_AUTHORIZED'))->fail()->respond();
		}

		$tour = Vr360ModelTour::getInstance()->getItem();

		if ($tour === false)
		{
			$ajax->addData('html', \Joomla\Language\Text::_('TOUR_NOTICE_TOUR_IS_NOT_AVAILABLE'))->fail()->respond();
		}
		else
		{
			$html = Vr360Layout::getInstance()->fetch('form.hotspots', array('tour' => $tour));
			$ajax->addData('html', $html)->success()->respond();
		}
	}

	/**
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function ajaxValidateAlias()
	{
		$ajax = Vr360AjaxResponse::getInstance();

		$result = Vr360ModelTour::getInstance()->validateAlias();

		if ($result)
		{
			$ajax->addDanger(\Joomla\Language\Text::_('TOUR_NOTICE_DUPLICATED_ALIAS'))->fail()->respond();
		}

		$ajax->success()->respond();
	}
}
