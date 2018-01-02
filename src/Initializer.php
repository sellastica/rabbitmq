<?php
namespace RabbitMQ;

class Initializer
{
	/** @var \Sellastica\RabbitMQ\RabbitMQFactory */
	private $rabbitMQFactory;
	/** @var \Gamee\RabbitMQ\Connection\ConnectionFactory */
	private $connectionFactory;


	/**
	 * @param \Sellastica\RabbitMQ\RabbitMQFactory $rabbitMQFactory
	 * @param \Gamee\RabbitMQ\Connection\ConnectionFactory $connectionFactory
	 */
	public function __construct(
		\Sellastica\RabbitMQ\RabbitMQFactory $rabbitMQFactory,
		\Gamee\RabbitMQ\Connection\ConnectionFactory $connectionFactory
	)
	{
		$this->rabbitMQFactory = $rabbitMQFactory;
		$this->connectionFactory = $connectionFactory;
	}

	public function initialize(): void
	{
		$connection = $this->connectionFactory->getConnection('default');
		$apiClient = $this->rabbitMQFactory->create();
		$apiClient->put("vhosts/$connection->vhost");
		$apiClient->put("permissions/$connection->vhost/$connection->user", [
			'configure' => '.*',
			'write' => '.*',
			'read' => '.*',
		]);
	}
}
