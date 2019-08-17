<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Session
 *
 * @since  2.0.0
 */
class Vr360Session extends Vr360Object
{

	/**
	 * @var  int
	 */
	protected $status;

	/**
	 * @var  string
	 */
	protected $id;

	/**
	 * @var array
	 */
	protected $config = array();

	protected $namespace = null;

	/**
	 * Vr360Session constructor.
	 *
	 * @param   array $properties Properties
	 */
	public function __construct($properties = null)
	{
		parent::__construct($properties);

		$this->namespace = Vr360Configuration::getConfig('sessionNamespace', 'VR360');

		$this->config['gc_maxlifetime'] = Vr360Configuration::getConfig('cookieTime');
		$this->start();
	}

	/**
	 * @return  void
	 */
	public function start()
	{
		if (empty(session_id()))
		{
			session_start($this->config);
		}

		$this->status = session_status();
		$this->id     = session_id();
	}

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
	 * @param        $message
	 * @param string $type
	 */
	public function addMessage($message, $type = 'default')
	{
		$messages          = $this->get('messages', array());
		$messages[$type][] = $message;

		$this->set('messages', $messages);
	}

	public function get($property, $default = null)
	{
		if ($this->isValid())
		{
			if (isset($_SESSION[$this->namespace][$property]))
			{
				$default = $_SESSION[$this->namespace][$property];
			}
		}

		return $default;
	}

	/**
	 * @return boolean
	 */
	protected function isValid()
	{
		return $this->status == PHP_SESSION_ACTIVE;
	}

	/**
	 * @param string $property
	 * @param mixed  $value
	 *
	 * @return null|void
	 */
	public function set($property, $value)
	{
		if ($this->isValid())
		{
			$_SESSION[$this->namespace][$property] = $value;
		}
	}

	/**
	 * @return  string
	 */
	public function getMessages()
	{
		$messages = $this->get('messages', array());

		$this->set('messages', null);

		return $messages;
	}

	/**
	 * @return  void
	 */
	public function reset()
	{
		session_unset();
		session_destroy();

		parent::reset();
	}
}
