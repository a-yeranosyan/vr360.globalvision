<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperFolder
 *
 * @since  2.0.0
 */
class Vr360HelperFolder
{

	/**
	 * @param $dirPath
	 */
	public static function delete($dirPath)
	{
		if (is_dir($dirPath))
		{
			//GLOB_MARK adds a slash to directories returned
			$files = glob($dirPath . '*', GLOB_MARK);

			foreach ($files as $file)
			{
				self::delete($file);
			}

			if (file_exists($dirPath))
			{
				rmdir($dirPath);
			}
		}
		elseif (is_file($dirPath))
		{
			unlink($dirPath);
		}
	}

	public static function create($dir)
	{
		if (!self::exists($dir))
		{
			mkdir($dir, 0777, true);
		}
	}

	public static function exists($path)
	{
		return (boolean) file_exists($path) && is_dir($path);
	}

	public static function move($source, $dir)
	{
		if (self::exists($source))
		{
			return rename($source, $dir);
		}

		return false;
	}

	public static function files($dir)
	{
		if (self::exists($dir))
		{
			return array_diff(scandir($dir), array('..', '.'));
		}

		return false;
	}
}
