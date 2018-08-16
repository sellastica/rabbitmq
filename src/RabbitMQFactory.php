<?php
namespace Sellastica\RabbitMQ;

class RabbitMQFactory
{
	/** @var \Gamee\RabbitMQ\Connection\ConnectionsDataBag */
	private $dataBag;


	/**
	 * @param \Gamee\RabbitMQ\Connection\ConnectionsDataBag $dataBag
	 */
	public function __construct(
		\Gamee\RabbitMQ\Connection\ConnectionsDataBag $dataBag
	)
	{
		$this->dataBag = $dataBag;
	}

	/**
	 * @return RabbitMQ
	 */
	public function create(): RabbitMQ
	{
		$credentials = (object)$this->dataBag->getDataBykey('default');
		return new RabbitMQ(
			$credentials->host,
			15672, //default Web UI and API port
			$credentials->user,
			$credentials->password
		);
	}
}
