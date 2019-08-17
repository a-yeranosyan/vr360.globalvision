<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperFolder
 *
 * @since  2.0.0
 */
class Vr360HelperFile
{

	/**
	 * @param $src
	 * @param $dest
	 *
	 * @return boolean
	 */
	public static function copy($src, $dest)
	{
		if (!self::exists($src))
		{
			return false;
		}

		return copy($src, $dest);
	}

	/**
	 * @param   string $filePath File path
	 *
	 * @return  boolean
	 */
	public static function exists($filePath)
	{
		return (file_exists($filePath) && is_file($filePath));
	}

	/**
	 * @param $filePath
	 *
	 * @return boolean|string
	 */
	public static function read($filePath)
	{
		if (!self::exists($filePath))
		{
			return false;
		}

		return file_get_contents($filePath);
	}

	public static function write($filePath, $content)
	{
		$handler = fopen($filePath, 'w');

		if ($handler)
		{
			return fwrite($handler, $content);
		}

		return false;
	}

	public static function delete($filePath)
	{
		if (!self::exists($filePath))
		{
			return false;
		}

		return unlink($filePath);
	}

	/**
	 * Function to strip additional / or \ in a path name.
	 *
	 * @param   string $path The path to clean.
	 * @param   string $ds   Directory separator (optional).
	 *
	 * @return  string  The cleaned path.
	 *
	 * @since   11.1
	 * @throws  UnexpectedValueException
	 */
	public static function clean($path, $ds = DIRECTORY_SEPARATOR)
	{
		if (!is_string($path) && !empty($path))
		{
			throw new UnexpectedValueException('JPath::clean: $path is not a string.');
		}

		$path = trim($path);

		if (empty($path))
		{
			$path = VR360_PATH_ROOT;
		}

		// Remove double slashes and backslashes and convert all slashes and backslashes to DIRECTORY_SEPARATOR
		// If dealing with a UNC path don't forget to prepend the path with a backslash.
		elseif (($ds == '\\') && substr($path, 0, 2) == '\\\\')
		{
			$path = "\\" . preg_replace('#[/\\\\]+#', $ds, $path);
		}
		else
		{
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}

		return $path;
	}
}
