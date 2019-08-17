<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperTour
 */
class Vr360HelperTour
{
	/**
	 * @param   string $filePath
	 *
	 * @return  boolean|string
	 */
	public static function fileValidate($filePath)
	{
		if (!file_exists($filePath))
		{
			return 'File not found: ' . $filePath;
		}

		$imageSize = getimagesize($filePath);

		if (!$imageSize)
		{
			return 'Invalid file';
		}

		$mime = $imageSize['mime'];

		if (!in_array($mime, Vr360Configuration::getConfig('allowMimeTypes')))
		{
			return 'Invalid file mime';
		}

		if ($imageSize[0] < Vr360Configuration::getConfig('minimumWidth'))
		{
			return 'Invalid image width. Minimum required: ' . Vr360Configuration::getConfig('minimumWidth');
		}

		if ($imageSize[1] < Vr360Configuration::getConfig('minimumHeight'))
		{
			return 'Invalid image height. Minimum required: ' . Vr360Configuration::getConfig('minimumHeight');
		}

		if ($imageSize[0] < 2 * $imageSize[1])
		{
			return 'Invalid file dimension';
		}

		return true;
	}

	/**
	 * @param   string $fileName
	 *
	 * @return  string
	 */
	public static function generateFilename($fileName)
	{
		$md5 = md5(uniqid('sth', true));
		$md5 = substr($md5, 0, 12);

		return $md5 . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
	}

	/**
	 * @return bool|string
	 */
	public static function createDataDir()
	{
		// Create data directory first
		if (!file_exists(VR360_PATH_DATA))
		{
			mkdir(VR360_PATH_DATA);
		}

		$uId = Vr360HelperTour::generateUId();

		if (!mkdir(VR360_PATH_DATA . '/' . $uId))
		{
			return false;
		}

		return $uId;
	}

	/**
	 * @return string
	 */
	public static function generateUId()
	{
		$uId = uniqid('__', false);

		return $uId . '_' . md5(uniqid('', true));
	}

	/**
	 * @param   string $uId      Directory
	 * @param   array  $jsonData Json data
	 *
	 * @return string
	 */
	public static function generateTour($uId, $jsonData, &$command = array())
	{
		$preImageDir      = VR360_PATH_DATA . '/' . $uId . '/';
		$vTourXmlFilePath = $preImageDir . '/vtour/tour.xml';

		// Delete old tour.xml if exists
		if (Vr360HelperFile::exists($vTourXmlFilePath))
		{
			Vr360HelperFile::delete($vTourXmlFilePath);
		}

		// There is no panos
		if (empty($jsonData['files']))
		{
			return false;
		}

		$files = array();

		foreach ($jsonData['files'] as $file)
		{
			$file = $preImageDir . $file;

			// Make sure file exists
			if (Vr360HelperFile::exists($file))
			{
				$files[] = $file;
			}
		}

		if (empty($files))
		{
			return false;
		}

		$command = '';
		$krPano  = new Vr360Krpano(Vr360Configuration::getConfig('krPanoPath'), Vr360Configuration::getConfig('krPanoLicense'));
		$krPano->useConfigFile(Vr360Configuration::getConfig('krPanoConfigFile'));
		$krPano->addFiles($files);

		return $krPano->makePano($command);
	}

	/**
	 * @param   string $uId      Directory
	 * @param   array  $jsonData Json data
	 *
	 * @return  boolean|integer
	 */
	public static function generateXml($uId, $jsonData)
	{
		$tourDataDirPath = VR360_PATH_DATA . '/' . $uId . '/vtour';
		$tourXmlFile     = $tourDataDirPath . "/tour.xml";

		if (!Vr360HelperFolder::exists($tourDataDirPath))
		{
			return false;
		}

		$xmlData = array();

		/**
		 * Prepare xmlData
		 * Don't change the order of this array or you will have foot on your head
		 */
		$xmlData['header'] = array();
		$xmlData['scenes'] = array();
		$xmlData['footer'] = array();

		// Assign xmlData to array
		if (isset($jsonData['rotation']) && $jsonData['rotation'] == "1")
		{
			$xmlData['header']['openRotationTag']  = '';
			$xmlData['header']['closeRotationTag'] = '';
		}
		else
		{
			$xmlData['header']['openRotationTag']  = '<!--';
			$xmlData['header']['closeRotationTag'] = '-->';
		}

		if (isset($jsonData['socials']) && $jsonData['socials'] == "1")
		{
			$xmlData['header']['openSocialTag']  = '';
			$xmlData['header']['closeSocialTag'] = '';
		}
		else
		{
			$xmlData['header']['openSocialTag']  = '<!--';
			$xmlData['header']['closeSocialTag'] = '-->';
		}

		// Try to set default pano
		if (isset($jsonData['params']['defaultPano']))
		{
			// Try to get index
			$defaultPanoFile  = $jsonData['params']['defaultPano'];
			$defaultPanoIndex = array_search($defaultPanoFile, isset($jsonData['files']) ? $jsonData['files'] : array());

			$xmlData['header']['defaultPano'] = (string) $defaultPanoIndex;
		}
		else
		{
			$xmlData['header']['defaultPano'] = (string) VR360_TOUR_DEFAULT_PANO;
		}

		foreach ($jsonData['files'] as $scene => $fileName)
		{
			$xmlData['scenes'][$scene] = array();
			$xmlFileName               = explode('.', $fileName)[0];

			$xmlData['scenes'][$scene]['xmlFileName'] = $xmlFileName;
			$xmlData['scenes'][$scene]['xmlTitle']    = $jsonData['panoTitle'][$scene];
			$xmlData['scenes'][$scene]['xmlSubTitle'] = $jsonData['panoDescription'][$scene];

			if (isset($jsonData['defaultViewList']["scene_$xmlFileName"]))
			{
				$xmlData['scenes'][$scene]['fov']     = $jsonData['defaultViewList']["scene_$xmlFileName"]['fov'];
				$xmlData['scenes'][$scene]['hlookat'] = $jsonData['defaultViewList']["scene_$xmlFileName"]['hlookat'];
				$xmlData['scenes'][$scene]['vlookat'] = $jsonData['defaultViewList']["scene_$xmlFileName"]['vlookat'];
			}
			else
			{
				$xmlData['scenes'][$scene]['fov']     = VR360_TOUR_SCENE_DEFAULT_FOV;
				$xmlData['scenes'][$scene]['hlookat'] = VR360_TOUR_SCENE_DEFAULT_HLOOKAT;
				$xmlData['scenes'][$scene]['vlookat'] = VR360_TOUR_SCENE_DEFAULT_VLOOKAT;
			}

			$xmlData['scenes'][$scene]['xmlHotspots'] = self::xmlHotspots($jsonData, $xmlData['scenes'][$scene]['xmlFileName']);
		}

		// Write xmlData to xml Template
		$targetXmlFileContents = '';

		// Process xmlData
		foreach ($xmlData as $templateKey => $data)
		{
			$xmlTemplateFile = VR360_PATH_ASSETS . '/krpano-' . $templateKey . '.xml';
			$xmlContent      = Vr360HelperFile::read($xmlTemplateFile);

			foreach ($data as $key => $keyData)
			{
				if (gettype($keyData) == 'string')
				{
					$xmlContent = preg_replace("/\{\{$key\}\}/", $keyData, $xmlContent);
				}
				// Okie array will be like scenes, loop thought all template, I know this not perfect but this is the best I can do at this times.
				elseif (gettype($keyData) == 'array')
				{
					// @TODO We don't need read file again. Cache it somewhere
					$xmlContent = Vr360HelperFile::read($xmlTemplateFile);

					foreach ($keyData as $subKey => $subKeyData)
					{
						$xmlContent = preg_replace("/\{\{$subKey\}\}/", $subKeyData, $xmlContent);
					}

					$targetXmlFileContents .= $xmlContent;
					$xmlContent            = '';
				}
				else
				{
					// 500 error, wrong data format or empty
				}
			}

			$targetXmlFileContents .= $xmlContent;
		}

		$xmlObject = simplexml_load_string($targetXmlFileContents);

		return $xmlObject->asXML($tourXmlFile);
	}

	/**
	 * @param $jsonData
	 * @param $xmlFileName
	 *
	 * @return string
	 */
	protected static function xmlHotspots($jsonData, $xmlFileName)
	{
		$returnValue = '';

		if (!isset($jsonData['hotspotList']))
		{
			return $returnValue;
		}

		if (count($jsonData['hotspotList']) < 1)
		{
			return $returnValue;
		}

		// Process hotspots list
		foreach ($jsonData['hotspotList'] as $scene => $value)
		{
			$file = str_replace('scene_', '', $scene);

			// Match filename
			if ($file == $xmlFileName)
			{
				$hotspotObj = new stdClass;

				foreach ($jsonData['hotspotList'][$scene] as $hotspotId => $hotspot)
				{
					$hotspotObj->hotspotID = $hotspotId;
					$hotspotObj->ath       = $hotspot['ath'];
					$hotspotObj->atv       = $hotspot['atv'];

					$hotspot['linkedscene'] = isset($hotspot['linkedscene']) ? $hotspot['linkedscene'] : '';

					$isNotSceneIndex = preg_match('/scene\_/', $hotspot['linkedscene']);

					switch ($hotspot['hotspot_type'])
					{
						case 'normal':
							$hotspotObj->style        = 'tooltip';
							$hotspotObj->hotspot_type = 'normal';
							$hotspotObj->data         = $isNotSceneIndex ?
								'linkedscene="' . $hotspot['linkedscene'] . '"' :
								'linkedscene="scene_' . explode('.', $jsonData['files'][$hotspot['linkedscene']])[0] . '"';
							break;
						case'text':
							$hotspotObj->style        = 'textpopup';
							$hotspotObj->hotspot_type = 'text';
							$hotspotObj->data         = 'hotspot_text="' . $hotspot['hotspot_text'] . '"';
						default:
							break;
					}

					$returnValue .= self::xmlHotspot($hotspotObj);
				}
			}
		}

		return $returnValue;
	}

	/**
	 * @param   object $hotspotObj
	 *
	 * @return string
	 */
	protected static function xmlHotspot($hotspotObj)
	{
		$h = $hotspotObj;

		return "<hotspot name='spot_$h->hotspotID' dataId='$h->hotspotID' style='skin_hotspotstyle|$h->style' ath='$h->ath' atv='$h->atv' hotspot_type='$h->hotspot_type' $h->data /> \n";
	}
}
