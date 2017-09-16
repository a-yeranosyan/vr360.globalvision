<?php

defined('_VR360_EXEC') or die;

class Vr360ModelTours extends Vr360Model
{
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static();

		return $instance;
	}

	public function getList($userId = null)
	{
		if ($userId === null)
		{
			$userId = Vr360Factory::getUser()->id;
		}

		$input = Vr360Factory::getInput();

		$offset = $input->getInt('page', 0) * 20;
		$limit  = 20;

		$db   = Vr360Database::getInstance();
		$rows = $db->select(
			'tours',
			[
				'tours.id',
				'tours.name',
				'tours.description',
				'tours.alias',
				'tours.created',
				'tours.created_by',
				'tours.dir',
				'tours.status'
			],
			[
				'tours.created_by' => (int) $userId,
				'tours.status[!]'  => VR360_TOUR_STATUS_UNPUBLISHED,
				'ORDER'            => [
					'tours.id' => 'DESC'
				],

				'LIMIT' => [
					$offset,
					$limit
				]

			]
		);

		if (!empty($rows))
		{
			$data = array();
			foreach ($rows as $row)
			{
				$tour = new Vr360TableTour();
				$tour->bind($row);
				$data[] = $tour;
			}

			return $data;
		}

		return $rows;
	}

	public function getPagination($userId = null)
	{
		if ($userId === null)
		{
			$userId = Vr360Factory::getUser()->id;
		}

		$limit = 20;

		$db   = Vr360Database::getInstance();
		$rows = $db->select(
			'tours',
			[
				'tours.id',
				'tours.name',
				'tours.description',
				'tours.alias',
				'tours.created',
				'tours.created_by',
				'tours.dir',
				'tours.status'
			],
			[
				'tours.created_by' => (int) $userId,
				'tours.status[!]'  => VR360_TOUR_STATUS_UNPUBLISHED,
				'ORDER'            => [
					'tours.id' => 'DESC'
				],
			]
		);

		$input = Vr360Factory::getInput();
		if (!empty($rows))
		{
			return array(
				'current' => $input->getInt('page', 1),
				'total'   => round(count($rows) / $limit)
			);
		}
	}
}