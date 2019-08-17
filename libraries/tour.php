<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Tour
 *
 * @since  2.0.0
 */
class Vr360Tour extends Vr360TableTour
{

	/**
	 * Vr360Tour constructor.
	 *
	 * @param array|null $properties
	 */
	public function __construct(array $properties = null)
	{
		parent::__construct($properties);

		$this->params = new Vr360Object;
	}

	/**
	 * @return integer
	 */
	public function getHotspots()
	{
		$scenes = $this->getScenes();

		if (!$scenes)
		{
			return 0;
		}

		$count = 0;

		foreach ($scenes as $scene)
		{
			$hotspots = $scene->getHotspots();

			if (!$hotspots)
			{
				continue;
			}

			$count = $count + count($hotspots);
		}

		return $count;
	}

	/**
	 * Method for get all scenes of tour
	 *
	 * @return   array|false  Array of scenes. False otherwise.
	 *
	 * @since  3.0.0
	 */
	public function getScenes()
	{
		if (!$this->id)
		{
			return false;
		}

		return Vr360ModelTour::getInstance()->getScenes($this->get('id'));
	}

	/**
	 * @return string
	 */
	public function getKrpanoVersion()
	{
		$xml = $this->getXml();

		if ($xml)
		{
			$nodes = $xml->getNodes();

			return $nodes['@attributes']['version'];
		}

		return 'Invalid';
	}

	/**
	 * @return boolean|Vr360TourXml
	 */
	public function getXml()
	{
		$tourXml = $this->getFile('vtour/tour.xml');

		if (!Vr360HelperFile::exists($tourXml))
		{
			return false;
		}

		$xml = new Vr360TourXml;
		$xml->load($tourXml);

		return $xml;
	}

	/**
	 * @param $file
	 *
	 * @return boolean|string
	 */
	public function getFile($file)
	{
		$filePath = Vr360HelperFile::clean($this->getDir() . '/' . $file);

		if (!Vr360HelperFile::exists($filePath))
		{
			return false;
		}

		return $filePath;
	}

	/**
	 * @return string
	 */
	public function getDir()
	{
		return VR360_PATH_DATA . '/' . $this->id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return trim($this->name);
	}

	/**
	 * @param   integer $truncateLength Truncate length
	 *
	 * @return  string
	 */
	public function getDescription($truncateLength = 300)
	{
		$description = !empty($this->description) ? $this->description : Vr360Configuration::getConfig('siteDescription');

		return trim(mb_substr($description, 0, $truncateLength));
	}

	/**
	 * @return  string|null
	 */
	public function getKeyword()
	{
		return !empty($this->keyword) ? $this->keyword : Vr360Configuration::getConfig('siteKeyword');
	}

	/**
	 * @return array|boolean
	 */
	public function getThumbnail()
	{
		$scenes = $this->getScenes();
		$scene  = $scenes[0];

		$pathInfo = pathinfo($scene->file);
		$filePath = VR360_PATH_DATA . '/' . $scene->tourId . '/vtour/panos/' . $pathInfo['filename'] . '.tiles/thumb.jpg';

		if (Vr360HelperFile::exists($filePath))
		{
			$imageSize           = getimagesize($filePath);
			$thumbnail['file']   = '/_/' . $this->id . '/vtour/panos/' . $pathInfo['filename'] . '.tiles/thumb.jpg';
			$thumbnail['url']    = VR360_URL_ROOT . $thumbnail['file'];
			$thumbnail['alt']    = $scene->name;
			$thumbnail['width']  = $imageSize[0];
			$thumbnail['height'] = $imageSize[1];
			$thumbnail['mime']   = $imageSize['mime'];

			return $thumbnail;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getKrpanoJsUrl()
	{
		return './_/' . $this->id . '/vtour/tour.js';
	}

	/**
	 * @return string
	 */
	public function getKrpanoEmbedPano()
	{
		$embed                      = new stdClass;
		$embed->swf                 = $this->getKrpanoSwfUrl();
		$embed->xml                 = '_/' . $this->id . '/vtour/tour.xml';
		$embed->target              = 'pano';
		$embed->html5               = Vr360Configuration::getConfig('krPanoEmbedHtml5', 'auto');
		$embed->mobilescale         = '1.0';
		$embed->passQueryParameters = 'true';

		return 'embedpano(' . json_encode($embed) . ');';
	}

	/**
	 * @return string
	 */
	public function getKrpanoSwfUrl()
	{
		return './_/' . $this->id . '/vtour/tour.swf';
	}

	/**
	 * @return boolean
	 */
	public function isValidForRender()
	{
		if (!Vr360HelperFile::exists(Vr360HelperFile::clean($this->getDir() . '/vtour/tour.xml')))
		{
			return false;
		}

		if (!Vr360HelperKrpano::checkFile('viewer/skin/vtourskin.xml'))
		{
			return false;
		}

		if (!Vr360HelperKrpano::checkFile('viewer/skin/tour-vtskin.xml'))
		{
			return false;
		}

		if (!Vr360HelperKrpano::checkFile('viewer/skin/social-skin.xml'))
		{
			return false;
		}

		return true;
	}

	/**
	 * @return boolean
	 */
	public function canEmbed()
	{
		return $this->isValid();
	}

	/**
	 * @return boolean
	 */
	public function isValid()
	{
		$dataDir = Vr360HelperFile::clean($this->getDir());

		if (!Vr360HelperFolder::exists($dataDir))
		{
			return false;
		}

		$tourDir = Vr360HelperFile::clean($dataDir . '/vtour');

		if (!Vr360HelperFolder::exists($tourDir))
		{
			return false;
		}

		return true;
	}

	/**
	 * @return boolean
	 */
	public function canEdit()
	{
		return $this->isValid();
	}

	/**
	 * @return boolean
	 */
	public function canEditHotspot()
	{
		return $this->isValid();
	}

	/**
	 * @return boolean
	 */
	public function canPreview()
	{
		return $this->isValid();
	}

	/**
	 * Delete tour
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

		$scenes = $this->getScenes();

		if (!empty($scenes))
		{
			foreach ($scenes as $scene)
			{
				/** @var  Vr360Scene $scene */
				$scene->delete();
			}
		}

		// Delete tour directory
		Vr360HelperFolder::delete(Vr360HelperFile::clean(VR360_PATH_DATA . '/' . $this->id));

		// Delete tour record
		if (!parent::delete())
		{
			return false;
		}

		return true;
	}

	/**
	 * @return boolean
	 */
	public function hit()
	{
		if (!$this->id)
		{
			return false;
		}

		$db    = Vr360Factory::getDbo();
		$query = $db->getQuery(true)
			->update($this->_table)
			->set($db->quoteName('hits') . ' = (' . $db->quoteName('hits') . ' + 1)')
			->where($db->quoteName('id') . ' = ' . (int) $this->get('id'));

		$db->setQuery($query)->execute();

		$this->hits++;

		return true;
	}

	public function getUser()
	{
		$user = new Vr360User;
		$user->load(array('id' => $this->get('created_by')));

		return $user;
	}
}
