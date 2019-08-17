<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelHotspots
 *
 * @since  2.1.0
 */
class Vr360ModelHotspots extends Vr360Model
{
	/**
	 * @param null $sceneId
	 *
	 * @return array|boolean
	 */
	public function getList($sceneId = null)
	{
		$db    = Vr360Factory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('hotspots'));

		if ($sceneId)
		{
			$query->where($db->quoteName('sceneId') . ' = ' . (int) $sceneId);
		}

		$query->order($db->quoteName('id') . ' ASC');

		$rows = $db->setQuery($query)->loadObjectList();

		if (!$rows)
		{
			return false;
		}

		$hotspots = array();

		foreach ($rows as $row)
		{
			$row->params = !empty($row->params) ? new Vr360Object(json_decode($row->params)) : new Vr360Object;
			$hotspot     = new Vr360Hotspot;
			$hotspot->bind($row);
			$hotspots[] = $hotspot;
		}

		return $hotspots;
	}

	/**
	 * @param   integer $sceneId
	 *
	 * @return  boolean
	 */
	public function deleteBySceneId($sceneId)
	{
		$db    = Vr360Factory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->quoteName('hotspots'))
			->where($db->quoteName('sceneId') . ' = ' . (int) $sceneId);

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
}
