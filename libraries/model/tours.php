<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360ModelTours
 *
 * @since  1.0.0
 */
class Vr360ModelTours extends Vr360Model
{
	/**
	 * @return array|boolean
	 */
	public function getList()
	{

		$input  = Vr360Factory::getInput();
		$offset = $input->getInt('page', 0) * 20;
		$limit  = 20;

		$db    = Vr360Factory::getDbo();
		$query = $this->buildQuery();

		$rows = $db->setQuery($query, $offset, $limit)->loadObjectList();

		if (!$rows || empty($rows))
		{
			return false;
		}

		$tours = array();

		// Assign to tour object
		foreach ($rows as $row)
		{
			$row->params = !empty($row->params) ? new Vr360Object(json_decode($row->params)) : new Vr360Object();
			$tour        = new Vr360Tour;
			$tour->bind($row);
			$tours[] = $tour;
		}

		return $tours;
	}
	/**
	 * @return \Joomla\Database\DatabaseQuery
	 */
	protected function buildQuery()
	{
		$db    = Vr360Factory::getDbo();
		$input = Vr360Factory::getInput();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('tours'))
			->where($db->quoteName('status') . ' = ' . VR360_TOUR_STATUS_PUBLISHED_READY)
			->where($db->quoteName('created_by') . ' = ' . Vr360Factory::getUser()->id)
			->order($db->quoteName('id') . ' DESC');

		// Filter by keyword
		$keyword = $input->getString('keyword');

		if ($keyword)
		{
			$query->where(
				$db->quoteName('name') . ' LIKE ' . $db->quote('%' . $keyword . '%')
				. ' OR ' . $db->quoteName('alias') . ' LIKE ' . $db->quote('%' . $keyword . '%')
				. ' OR ' . $db->quoteName('description') . ' LIKE ' . $db->quote('%' . $keyword . '%')
				. ' OR ' . $db->quoteName('keyword') . ' LIKE ' . $db->quote('%' . $keyword . '%')
			);
		}

		return $query;
	}

	/**
	 * @return array|boolean
	 */
	public function getPagination()
	{
		$limit = 20;

		$db    = Vr360Factory::getDbo();
		$query = $this->buildQuery();

		$rows = $db->setQuery($query)->loadObjectList();

		$input = Vr360Factory::getInput();

		if (!empty($rows))
		{
			return array(
				'current' => $input->getInt('page', 0),
				'total'   => round(count($rows) / $limit) - 1
			);
		}

		return false;
	}
}
