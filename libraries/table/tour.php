<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableTour
 *
 * @since  2.0.0
 */
class Vr360TableTour extends Vr360Table
{
	/**
	 * @var string
	 */
	public $name = null;

	/**
	 * @var string
	 */
	public $alias = null;

	/**
	 * @var string
	 */
	public $description = null;

	/**
	 * @var string
	 */
	public $keyword = null;

	/**
	 * @var  integer
	 */
	public $hits = null;

	/**
	 * @var string
	 */
	public $ordering = null;

	/**
	 * @var string
	 */
	public $created = null;

	/**
	 * @var integer
	 */
	public $created_by = null;

	/**
	 * @var string
	 */
	public $status = null;

	/**
	 * @var string
	 */
	protected $_table = 'tours';

	/**
	 * @return boolean
	 */
	protected function check()
	{
		$db    = Vr360Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName('id'))
			->from($db->quoteName('tours'))
			->where($db->quoteName('id') . ' !=' . (int) $this->get('id'))
			->where($db->quoteName('alias') . ' = ' . $db->quote($this->alias));

		$duplicatedAlias = $db->setQuery($query)->loadObjectList();

		if ($duplicatedAlias !== false && count($duplicatedAlias) > 0)
		{
			$this->setError('Duplicated alias');

			return false;
		}

		if (empty($this->name) || empty($this->alias))
		{
			$this->setError('Missing name or alias');

			return false;
		}

		if ($this->created === null)
		{
			$this->created = Vr360HelperDatetime::getMySqlFormat();
		}

		if ($this->created_by === null)
		{
			$this->created_by = Vr360Factory::getUser()->id;
		}

		if ($this->status === null)
		{
			$this->status = VR360_TOUR_STATUS_PUBLISHED_READY;
		}

		if ($this->hits === null)
		{
			$this->hits = 0;
		}

		if ($this->ordering === null)
		{
			$this->ordering = 0;
		}

		return parent::check();
	}
}
