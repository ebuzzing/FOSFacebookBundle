<?php
namespace FOS\FacebookBundle\DependencyInjection;

use FOS\FacebookBundle\Facebook\FacebookSessionPersistence;

class ServiceFactory
{
	static protected $instance;

	protected $config;
	protected $session;
	protected $prefix;

	protected $persistence;

	protected function __construct($config, $session, $prefix = FacebookSessionPersistence::PREFIX)
	{
		$this->config = $config;
		$this->session = $session;
		$this->prefix = $prefix;
	}

	static public function getInstance($config, $session, $prefix = FacebookSessionPersistence::PREFIX)
	{
		if (is_null(self::$instance))
		{
			self::$instance = new ServiceFactory($config, $session, $prefix);
		}

		return self::$instance;
	}

	public function __call($method, $param)
	{
		if(is_null($this->persistence))
		{
			$this->persistence = new FacebookSessionPersistence($this->config, $this->session, $this->prefix);
		}

		if(method_exists($this->persistence, $method))
		{
			return call_user_func_array(array($this->persistence, $method), $param);
		}
		else
		{
			throw new Exception("Unknow method $method on FacebookSessionPersistence");
		}
	}
}