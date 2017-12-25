<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelHotspot
 *
 * @since  2.1.0
 */
class Vr360ModelHotspot extends Vr360Model
{
	/**
	 * Method for store default view of scenes
	 *
	 * @param   array $scenes      List of available scenes
	 * @param   array $defaultView List of default view data
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function saveDefaultView($scenes = array(), $defaultView = array())
	{
		if (empty($scenes) || empty($defaultView))
		{
			return;
		}

		$ajax = Vr360AjaxResponse::getInstance();

		foreach ($scenes as $scene)
		{
			/** @var Vr360Scene $scene */

			$sceneName     = 'scene_' . explode('.', $scene->file)[0];
			$scene->setParam('defaultview', isset($defaultView[$sceneName]) && !empty($defaultView[$sceneName]) ? $defaultView[$sceneName] : array());

			if ($scene->store())
			{
				$ajax->addMessage('Scene ' . $scene->name . ' store default view successed');
			}
			else
			{
				$ajax->addWarning('Scene ' . $scene->name . ' store default view failed');
			}
		}
	}

	/**
	 * Method for store default view of scenes
	 *
	 * @param   array $scenes   List of available scenes
	 * @param   array $hotspots List of default view data
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function saveHotspot($scenes = array(), $hotspots = array())
	{
		$ajax = Vr360AjaxResponse::getInstance();

		if (empty($scenes) || empty($hotspots))
		{
			$ajax->addInfo('There are no hotspot');
		}

		foreach ($scenes as $scene)
		{
			$key = 'scene_' . explode('.', $scene->file)[0];

			if (!isset($hotspots[$key]) || empty($hotspots[$key]))
			{
				continue;
			}

			/**
			 * Delete old hotspot
			 * But only for request scenes
			 */
			if (!Vr360ModelHotspots::getInstance()->deleteBySceneId($scene->id))
			{
				$ajax->addWarning('Can not delete hotspots')->fail()->respond();
			}

			$hotspotPrefix = 'skin_hotspotstyle|';

			foreach ($hotspots[$key] as $code => $hotspot)
			{
				/** @var Vr360Scene $scene */
				$hotspotObj = new Vr360Hotspot;

				$hotspotObj->id      = null;
				$hotspotObj->code    = $code;
				$hotspotObj->sceneId = $scene->id;
				$hotspotObj->ath     = $hotspot['ath'];
				$hotspotObj->atv     = $hotspot['atv'];
				$hotspotObj->type    = trim($hotspot['hotspot_type']);
				if ($hotspotObj->type == '')
				{
					continue;
				}
						// print_r($hotspot);die();
				switch ($hotspotObj->type)
				{
					case 'link':
						$hotspotObj->style  = 'hotspot_style_linkedscene';
						$hotspotObj->params = array(
							'linkedscene' => $hotspot['linkedscene']);
						if (empty($hotspot['linkedscene']))
						{
							$ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty content');

							continue;
						}
						break;
					case 'text':
						$hotspotObj->style  = 'hotspot_style_text';
						$hotspotObj->params = array(
							'hotspot_title' => $hotspot['title'],
							'hotspot_content' => $hotspot['content']
						);

						if (empty($hotspot['title']))
						{
							$ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty title or content');

							continue;
						}
						break;
					case 'tooltip':
						$hotspotObj->style  = 'hotspot_style_tooltip';
						$hotspotObj->params = array(
							'tooltip_title' => $hotspot['title'],
							'tooltip_content' => $hotspot['content']
						);

						if (empty($hotspot['title']))
						{
							$ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty content');

							continue;
						}
						break;
					case 'modal':
						$hotspotObj->style  = 'hotspot_style_modal';
						$hotspotObj->params = array(
							'modal_title' => $hotspot['title'],
							'modal_content' => $hotspot['content']
						);

						if (empty($hotspot['title']))
						{
							// $ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty content');

							// continue;
						}
						break;
					case 'video':
						$hotspotObj->style  =  'hotspot_style_video';
						$hotspotObj->params = array('video_url' => $hotspot['video_url']);

						if (empty($hotspot['video_url']))
						{
							$ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty content');

							continue;
						}
						break;
					case 'image':
						$hotspotObj->style  =  'hotspot_style_image';
						$hotspotObj->params = array('image_url' => $hotspot['image_url']);

						if (empty($hotspot['image_url']))
						{
							$ajax->addWarning('Can not save hotspot . ' . $hotspotObj->code . ' because empty content');

							continue;
						}
						break;
				}

				if ($hotspotObj->store())
				{
					$ajax->addSuccess('Hotspot ' . $hotspotObj->code . ' save successful');
				}
				else
				{
					$ajax->addWarning('Hotspot ' . $hotspotObj->code . ' save fail. ' . $hotspotObj->getError());
				}
			}
		}
	}
}
