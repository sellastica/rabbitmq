<?php
namespace Sellastica\RabbitMQ;

class RabbitMQFactory
{
	/** @var \Gamee\RabbitMQ\Connection\ConnectionFactory */
	private $connectionFactory;


	/**
	 * @param \Gamee\RabbitMQ\Connection\ConnectionFactory $connectionFactory
	 */
	public function __construct(\Gamee\RabbitMQ\Connection\ConnectionFactory $connectionFactory)
	{
		$this->connectionFactory = $connectionFactory;
	}

	/**
	 * @return RabbitMQ
	 */
	public function create(): RabbitMQ
	{
		$connection = $this->connectionFactory->getConnection('default');
		return new RabbitMQ(
			$connection->host,
			15672, //default Web UI and API port
			$connection->user,
			$connection->password,
			$connection->vhost
		);
	}
}
