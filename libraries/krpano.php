<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Krpano
 *
 * @since   2.0.0
 */
class Vr360Krpano
{
	/**
	 * @var string
	 */
	protected $binPath;

	/**
	 * @var string
	 */
	protected $license;

	/**
	 * @var array
	 */
	protected $parameters = array();

	/**
	 * @var array
	 */
	protected $files = array();

	/**
	 * Vr360Krpano constructor.
	 *
	 * @param   string $binPath Binary path
	 * @param   string $license License
	 */
	public function __construct($binPath, $license)
	{
		$this->binPath = Vr360Configuration::getConfig('krPanoPath');
		$this->license = Vr360Configuration::getConfig('krPanoLicense');
	}

	/**
	 * @param   array $files Files
	 *
	 * @return  void
	 */
	public function addFiles($files)
	{
		foreach ($files as $key => $file)
		{
			$files[$key] = Vr360HelperFile::clean($file);
		}

		$this->files = $files;
	}

	/**
	 * @param   string $file Configuration file path
	 *
	 * @return  void
	 */
	public function useConfigFile($file)
	{
		$this->addParameter('-config=' . Vr360HelperFile::clean($file));
	}

	/**
	 * @param   string $parameter Parameter
	 *
	 * @return  void
	 */
	public function addParameter($parameter)
	{
		$this->parameters[] = $parameter;
	}

	/**
	 * @param   string $command Command
	 *
	 * @return  boolean|string
	 */
	public function makePano(&$command)
	{
		if ($this->validate() === false)
		{
			return false;
		}

		if (!empty($this->license))
		{
			$execute[] = $this->binPath . ' register ' . $this->license;
		}

		// Make pano
		array_unshift($this->parameters, 'makepano');

		$execute[] = $this->binPath . ' ' . implode(' ', $this->parameters) . ' ' . implode(' ', $this->files);

		$command = implode(' && ', $execute);

		return exec($command);
	}

	/**
	 * @return boolean
	 */
	protected function validate()
	{
		if (empty($this->files))
		{
			return false;
		}

		foreach ($this->files as $file)
		{
			if (!Vr360HelperFile::exists($file))
			{
				return false;
			}
		}

		return true;
	}
}
