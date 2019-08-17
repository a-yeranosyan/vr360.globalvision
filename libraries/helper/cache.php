<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperCache
 *
 * @since  2.1.0
 */
class Vr360HelperCache
{
	/**
	 * @param $key
	 *
	 * @return \Stash\Interfaces\ItemInterface
	 */
	public static function getItem($key)
	{
		return self::getCache()->getItem($key);
	}

	/**
	 * @return \Stash\Pool
	 */
	public static function getCache()
	{
		static $pool;

		if (!isset($pool))
		{
			// Create Driver with default options
			$driver = new Stash\Driver\FileSystem(array('path' => VR360_PATH_ROOT . '/cache'));

			// Inject the driver into a new Pool object.
			$pool = new Stash\Pool($driver);
		}

		return $pool;
	}

	/**
	 * @param  $item
	 *
	 * @return boolean
	 */
	public static function setItem($item)
	{
		return self::getCache()->save($item);
	}

	public static function deleteItem($key)
	{
		return self::getCache()->deleteItem($key);
	}
}
