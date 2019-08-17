<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableScene
 */
class Vr360TableScene extends Vr360Table
{
	/**
	 * @var  integer
	 */
	public $tourId = null;

	/**
	 * @var  string
	 */
	public $name = null;

	/**
	 * @var  string
	 */
	public $description = null;

	/**
	 * @var  string
	 */
	public $file = null;

	/**
	 * @var  integer
	 */
	public $ordering = null;

	/**
	 * @var  integer
	 */
	public $status = null;

	/**
	 * @var   integer
	 */
	public $default = null;

	/**
	 * @var string
	 */
	protected $_table = 'scenes';

	/**
	 * @return boolean
	 */
	protected function check()
	{
		// TourId must be provided
		if ($this->tourId === null)
		{
			return false;
		}

		if ($this->name === null || empty($this->name))
		{
			return false;
		}

		if ($this->status === null)
		{
			$this->status = 1;
		}

		if ($this->default === null)
		{
			$this->default = 0;
		}

		if ($this->ordering === null)
		{
			$db    = Vr360Factory::getDbo();
			$query = $db->getQuery(true)
				->select(' MAX(' . $db->quoteName('ordering') . ')')
				->from($db->quoteName('scenes'))
				->where($db->quoteName('tourId') . ' = ' . (int) $this->tourId);

			$this->ordering = $db->setQuery($query)->loadResult() + 1;
		}

		return parent::check();
	}
}
