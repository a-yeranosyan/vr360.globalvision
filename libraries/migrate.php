<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Migrate
 *
 * @since  3.0.0
 */
class Vr360Migrate
{

	public function migrate()
	{
		//$this->syncTours();

		$tours = $this->getTours();

		foreach ($tours as $tour)
		{
			// Migrate old tours
			$this->migrateTour($tour);

			$this->cleanup($tour);
		}
	}

	/**
	 * @return array|bool
	 */
	protected function getTours()
	{
		$db = Vr360Database::getInstance();

		return $db->select(
			'tours',
			[
				'tours.id',
				'tours.name',
				'tours.description',
				'tours.alias',
				'tours.created',
				'tours.created_by',
				'tours.dir',
				'tours.status',
				'tours.params'
			],
			[
				"ORDER" => ["tours.created" => "ASC"]
			]
		);
	}

	protected function migrateTour($tour)
	{
		// Upgrade krpano files
		$this->updateKrpano($tour);

		// Upgrade XML content
		$this->updateXml($tour);

		// ! Upgrade json data & params
		$this->updateJson($tour);
	}

	/**
	 * @param $tour
	 */
	protected function updateKrpano($tour)
	{
		$tourDir = VR360_PATH_DATA . '/' . $tour['dir'];

		// Copy new files
		$src = VR360_PATH_ROOT . '/krpano/viewer/plugins';
		$dst = $tourDir . '/vtour/plugins';

		$files = glob($src . "/*.*");

		if (Vr360HelperFolder::exists($dst))
		{
			foreach ($files as $file)
			{
				$to = str_replace($src, $dst, $file);
				copy($file, $to);
			}

			// Update new krpano scripts
			copy(VR360_PATH_ROOT . '/krpano/licenses/tour.js', $tourDir . '/vtour/tour.js');
			copy(VR360_PATH_ROOT . '/krpano/licenses/tour.swf', $tourDir . '/vtour/tour.swf');
		}
	}

	/**
	 * @param $tour
	 *
	 * @return bool
	 */
	protected function updateXml($tour)
	{
		$tourDir = VR360_PATH_DATA . '/' . $tour['dir'];

		// Old XML format
		$xmlFile = $tourDir . '/vtour/t.xml';

		if (!Vr360HelperFile::exists($xmlFile))
		{
			$xmlFile = $tourDir . '/vtour/tour.xml';
		}

		if (!Vr360HelperFile::exists($xmlFile))
		{
			return false;
		}

		// Replace include XML to correct link
		$xmlBuffer = Vr360HelperFile::read($xmlFile);

		// Replace version
		$xmlBuffer = str_replace('<krpano version="1.18"', '<krpano version="1.19"', $xmlBuffer);

		// Replace includes
		$includes[] = '<include url="http://data.globalvision.ch/krpano/1.19/skin/';
		$includes[] = '<include url="http://vr360.globalvision.ch/assets/krpano/1.19/skin/';
		$xmlBuffer  = str_replace($includes, '<include url="/krpano/viewer/skin/', $xmlBuffer);

		// Write back to XML
		Vr360HelperFile::write($xmlFile, $xmlBuffer);
	}

	protected function updateJson($tour)
	{
		$db = Vr360Database::getInstance();

		$tourDir  = VR360_PATH_DATA . '/' . $tour['dir'];
		$jsonFile = $tourDir . '/data.json';

		// Only upgrade if this tour data exists
		if (Vr360HelperFolder::exists($tourDir))
		{
			$params = $tour['params'];
			$params = json_decode($params, true);

			// Read old json data
			$oldJsonData = json_decode(Vr360HelperFile::read($jsonFile), true);

			// Try to cover failed migrate data if possible
			if (Vr360HelperFile::exists($tourDir . '/data.json.bak'))
			{
				$oldJsonData = json_decode(Vr360HelperFile::read($tourDir . '/data.json.bak'), true);
			}

			if (
				// Only migrate if params is null or old json format
				is_null($params) ||
				(
					isset($oldJsonData['editID']) ||
					isset($oldJsonData['jsonData']) ||
					isset($oldJsonData['uId']) ||
					isset($oldJsonData['email'])
				)
			)
			{
				// Prepare new params
				$newParams = new stdClass;

				// List of files. Actually we'll not use this
				$newParams->files = array();

				// List of panos
				$newParams->panos = array();

				// Only migrate if found right needed panosList
				if (isset($oldJsonData['panoList']))
				{
					// Work on old panos list
					foreach ($oldJsonData['panoList'] as $index => $pano)
					{
						if (!isset($pano['currentFileName']))
						{
							continue;
						}

						$parts = explode('/', $pano['currentFileName']);

						// Store filename only
						$newPanoData['file']        = end($parts);
						$newPanoData['title']       = $pano['des'];
						$newPanoData['description'] = isset($pano['des_sub']) ? $pano['des_sub'] : '';

						// Store list of panos
						$newParams->panos[] = $newPanoData;

						// Store default pano
						if (isset($oldJsonData['defaultScene']))
						{
							if ($index == $oldJsonData['defaultScene'])
							{
								$newParams->defaultPano = $newPanoData['file'];
							}
						}

						// Store list of files
						$newParams->files[] = $newPanoData['file'];
					}

					copy($jsonFile, $tourDir . '/data.json' . '.' . time());

					// Write back to data.json
					Vr360HelperFile::write($jsonFile, json_encode($newParams));
				}
			}

			// Update pano tables
			$data = json_decode(Vr360HelperFile::read($jsonFile), true);

			foreach ($data['panos'] as $index => $pano)
			{
				// Try to check if this pano already created
				if (empty($this->getPano($tour, $pano)))
				{
					$db->insert('panos',
						array
						(
							'tourId'      => (int) $tour['id'],
							'title'       => $pano['title'],
							'description' => $pano['description'],
							'file'        => $pano['file'],
							'ordering'    => $index,
						)
					);
				}
			}

			// Update params if it's null
			if (is_null($params))
			{
				$tour['params'] = Vr360HelperFile::read($jsonFile);
				$db->update(
					'tours',
					$tour,
					[
						'id' => $tour['id']
					]
				);
			}
		}
	}

	/**
	 * @param $tour
	 * @param $pano
	 *
	 * @return array|bool
	 */
	protected function getPano($tour, $pano)
	{
		$db = Vr360Database::getInstance();

		return $db->select(
			'panos',
			'*',
			[
				'tourId' => $tour['id'],
				'title'  => $pano['title'],
				'file'   => $pano['file']
			]
		);
	}

	/**
	 * @param $tour
	 */
	protected function cleanup($tour)
	{
		$tourDir = VR360_PATH_DATA . '/' . $tour['dir'];
		Vr360HelperFile::delete($tourDir . '/kr-tool.sh');
		Vr360HelperFile::delete($tourDir . '/kr.log.err.html');
		Vr360HelperFile::delete($tourDir . '/kr.log.html');
		Vr360HelperFile::delete($tourDir . '/php.mail.log.html');
		Vr360HelperFile::delete($tourDir . '/tour_testingserver.exe');
		Vr360HelperFile::delete($tourDir . '/tour_testingserver_macos');

		$this->deleteTours($tour);
	}

	/**
	 * @param $tour
	 */
	protected function deleteTours($tour)
	{
		$db = Vr360Database::getInstance();

		$dataDir = VR360_PATH_DATA . '/' . $tour['dir'];

		if (!Vr360HelperFolder::exists($dataDir))
		{
			// Delete this tour
			$db->delete("tours",
				[
					"AND" => ["id" => (int) $tour['id']]
				]);

			return;
		}

		// Move unpublished & pending
		if ($tour['status'] != 1)
		{
			//
			Vr360HelperFolder::create(VR360_PATH_ROOT . '/backup/tours');
			Vr360HelperFolder::move($dataDir, VR360_PATH_ROOT . '/backup/tours/' . $tour['dir']);

			// Delete this tour
			$db->delete("tours",
				[
					"AND" => ["id" => (int) $tour['id']]
				]);

			var_dump($db->error());
		}

		// Delete old tour created by Nhan
		if ($tour['created_by'] == 1)
		{

			$db->delete("tours", [
				"AND" => [
					"id" => (int) $tour['id']
				]
			]);
		}
	}

	protected function syncTours()
	{
		$prevDb = new \Medoo\Medoo(array
		(
			'database_type' => 'mysql',
			'server'        => 'localhost',
			'username'      => 'root',
			'password'      => 'root',
			'database_name' => 'globalvision_vr360_remigrate',
			'charset'       => 'utf8'
		));

		$liveDb = new \Medoo\Medoo(array
		(
			'database_type' => 'mysql',
			'server'        => 'localhost',
			'username'      => 'root',
			'password'      => 'root',
			'database_name' => 'globalvision_vr360_live',
			'charset'       => 'utf8'
		));

		$prevTours = $prevDb->select(
			'tours',
			[
				'tours.id',
				'tours.name',
				'tours.description',
				'tours.alias',
				'tours.created',
				'tours.created_by',
				'tours.dir',
				'tours.status',
				'tours.params'
			]);


		foreach ($prevTours as $prevTour)
		{
			// Check if this tour exists on live site
			$liveTour = $liveDb->select(
				'tours',
				[
					'tours.id',
					'tours.name',
					'tours.description',
					'tours.alias',
					'tours.created',
					'tours.created_by',
					'tours.dir',
					'tours.status',
					'tours.params'
				],
				[
					'id'         => $prevTour['id'],
					'name'       => $prevTour['name'],
					'alias'      => $prevTour['alias'],
					'created_by' => $prevTour['created_by']
				]
			);

			if (!empty($liveTour))
			{
				continue;
			}

			$liveDb->insert('tours', $prevTour);
		}
	}
}