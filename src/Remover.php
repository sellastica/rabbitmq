<?php
namespace Sellastica\RabbitMQ;

class Remover
{
	/** @var \Gamee\RabbitMQ\Connection\ConnectionsDataBag */
	private $dataBag;
	/** @var RabbitMQFactory */
	private $rabbitMQFactory;


	/**
	 * @param RabbitMQFactory $rabbitMQFactory
	 * @param \Gamee\RabbitMQ\Connection\ConnectionsDataBag $dataBag
	 */
	public function __construct(
		RabbitMQFactory $rabbitMQFactory,
		\Gamee\RabbitMQ\Connection\ConnectionsDataBag $dataBag
	)
	{
		$this->dataBag = $dataBag;
		$this->rabbitMQFactory = $rabbitMQFactory;
	}

	public function remove(): void
	{
		$credentials = (object)$this->dataBag->getDataBykey('default');
		$bunny = $this->rabbitMQFactory->create();
		$bunny->delete("vhosts/$credentials->vhost");
	}
}
