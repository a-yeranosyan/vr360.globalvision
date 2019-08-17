<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelScenes
 *
 * @since  2.1.0
 */
class Vr360ModelScenes extends Vr360Model
{
	/**
	 * @param null $tourId
	 * @param int  $publish
	 *
	 * @return array|boolean
	 */
	public function getList($tourId = null, $publish = 1)
	{
		$db = Vr360Factory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('scenes'))
			->where($db->quoteName('status') . ' = ' . (int) $publish);

		if ($tourId)
		{
			$query->where($db->quoteName('tourId') . ' = ' . (int) $tourId);
		}

		$rows = $db->setQuery($query)->loadObjectList();

		if (!$rows)
		{
			return false;
		}

		$scenes = array();

		foreach ($rows as $row)
		{
			$row->params = !empty($row->params) ? new Vr360Object(json_decode($row->params)) : new Vr360Object;
			$scene       = new Vr360Scene;
			$scene->bind($row);
			$scenes[] = $scene;
		}

		return $scenes;
	}
}
