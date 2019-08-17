<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Table
 *
 * @since   2.0.0
 */
class Vr360Table extends Vr360Object
{
	/**
	 * @var   integer
	 */
	public $id = null;

	/**
	 * @var   object|array
	 */
	public $params = null;

	/**
	 * @var   string
	 */
	protected $_table = null;

	/**
	 * @param   array $condition Condition
	 *
	 * @return  boolean
	 */
	public function load($condition)
	{
		$db    = Vr360Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->quoteName($this->_table));

		$columns = $this->getProperties();

		foreach ($condition as $field => $value)
		{
			// Check that $field is in the table.
			if (!array_key_exists($field, $columns))
			{
				continue;
			}

			$query->where($db->quoteName($field) . ' = ' . $db->quote($value));
		}

		$row = $db->setQuery($query)->loadAssoc();

		// Check that we have a result.
		if (empty($row))
		{
			return false;
		}

		// Bind the object with the row and return.
		$this->reset();
		$result       = $this->bind($row);
		$this->params = !empty($this->params) ? new Vr360Object(json_decode($this->params)) : new Vr360Object;

		return $result;
	}

	/**
	 * @param $src
	 *
	 * @return boolean
	 */
	public function save($src)
	{
		$this->bind($src);

		return $this->store();
	}

	/**
	 * @return boolean
	 */
	public function store()
	{
		if (!$this->check())
		{
			return false;
		}

		$db = Vr360Factory::getDbo();

		// Insert
		if ($this->id === null)
		{
			return $db->insertObject($this->_table, $this, 'id');
		}

		return $db->updateObject($this->_table, $this, 'id');
	}

	/**
	 * Basic checking
	 *
	 * @return boolean
	 */
	protected function check()
	{
		if ($this->_table === null)
		{
			return false;
		}

		// Convert params to json before saving
		if (is_object($this->params) || is_array($this->params))
		{
			$this->params = json_encode($this->params);
		}

		return true;
	}

	/**
	 * @return boolean
	 */
	public function delete()
	{
		$db = Vr360Factory::getDbo();

		// Delete the row by primary key.
		$query = $db->getQuery(true)
			->delete($this->_table)
			->where($db->quoteName('id') . ' = ' . (int) $this->get('id'));

		try
		{
			$db->setQuery($query)->execute();
		}
		catch (Exception $exception)
		{
			$this->setError($exception->getMessage());

			return false;
		}

		return true;
	}
}
