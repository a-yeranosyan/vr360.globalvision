<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Template
 *
 * @since  2.0.0
 */
class Vr360Template
{
	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static;

		return $instance;
	}

	/**
	 * @param   string $templateName
	 *
	 * @return  string
	 */
	public function fetch($templateName = 'default')
	{
		ob_start();
		require_once VR360_PATH_TEMPLATES . DIRECTORY_SEPARATOR . $templateName . DIRECTORY_SEPARATOR . 'index.php';

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}