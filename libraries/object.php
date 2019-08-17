<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Object
 *
 * @since 2.0.0
 */
class Vr360Object
{
	/**
	 * @var array
	 */
	protected $_errors = array();

	/**
	 * Vr360Object constructor.
	 *
	 * @param   array $properties Properties
	 */
	public function __construct($properties = null)
	{
		if ($properties !== null)
		{
			$this->setProperties($properties);
		}
	}

	/**
	 * @param   array $properties Properties
	 *
	 * @return  boolean
	 */
	public function setProperties($properties)
	{
		if (is_array($properties) || is_object($properties))
		{
			foreach ((array) $properties as $k => $v)
			{
				// Use the set function which might be overridden.
				$this->set($k, $v);
			}

			return true;
		}

		return false;
	}

	/**
	 * @param   string $property Property
	 * @param   mixed  $value    Value
	 *
	 * @return null
	 */
	public function set($property, $value)
	{
		$previous          = property_exists($this, $property) ? $this->{$property} : null;
		$this->{$property} = $value;

		return $previous;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return get_class($this);
	}

	/**
	 * @param   array $properties Properties
	 *
	 * @return  boolean
	 */
	public function bind($properties)
	{
		$classProperties = get_class_vars($this);

		foreach ($properties as $key => $property)
		{
			if (array_key_exists($key, $classProperties))
			{
				$this->$key = $property;
			}
		}

		return true;
	}

	/**
	 * @param   string $property Property
	 * @param   mixed  $default  Default value
	 *
	 * @return  mixed
	 */
	public function def($property, $default = null)
	{
		$value = $this->get($property, $default);

		return $this->set($property, $value);
	}

	/**
	 * @param   string $property Property
	 * @param   null   $default  Default value
	 *
	 * @return  null
	 */
	public function get($property, $default = null)
	{
		if (isset($this->$property))
		{
			return $this->$property;
		}

		return $default;
	}

	/**
	 * @return string
	 */
	public function toJson()
	{
		return json_encode($this->getProperties());
	}

	/**
	 * @param   boolean $public Get public properties
	 *
	 * @return  array
	 */
	public function getProperties($public = true)
	{
		$vars = get_object_vars($this);

		if ($public)
		{
			foreach ($vars as $key => $value)
			{
				if ('_' == substr($key, 0, 1))
				{
					unset($vars[$key]);
				}
			}
		}

		return $vars;
	}

	/**
	 * @param   string $error
	 */
	public function setError($error)
	{
		$this->_errors[] = $error;
	}

	/**
	 * @return   string
	 */
	public function getError()
	{
		return array_pop($this->_errors);
	}

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * @return boolean
	 */
	public function hasError()
	{
		return !empty($this->_errors);
	}

	/**
	 * @return   void
	 */
	public function resetErrors()
	{
		$this->_errors = array();
	}

	/**
	 * @param      $property
	 * @param null $default
	 *
	 * @return null
	 */
	public function getParam($property, $default = null)
	{
		if (empty($this->params))
		{
			$this->params = new Vr360Object;
		}

		if (is_string($this->params))
		{
			$this->params = new Vr360Object(json_decode($this->params));
		}

		if (!is_object($this->params) || !isset($this->params->$property))
		{
			return $default;
		}

		return $this->params->get($property, $default);
	}

	/**
	 * @param $property
	 * @param $value
	 */
	public function setParam($property, $value)
	{
		if (empty($this->params))
		{
			$this->params = new Vr360Object;
		}

		$this->params->{$property} = $value;
	}

	/**
	 *
	 */
	public function reset()
	{
		$properties = $this->getProperties();

		foreach ($properties as $key => $value)
		{
			$this->{$key} = null;
		}
	}
}
