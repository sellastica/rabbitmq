<?php
namespace Sellastica\RabbitMQ;

class Initializer
{
	/** @var RabbitMQFactory */
	private $rabbitMQFactory;
	/** @var \Sellastica\Project\Model\SettingsAccessor */
	private $settingsAccessor;
	/** @var \Gamee\RabbitMQ\Connection\ConnectionsDataBag */
	private $dataBag;


	/**
	 * @param RabbitMQFactory $rabbitMQFactory
	 * @param \Gamee\RabbitMQ\Connection\ConnectionsDataBag $dataBag
	 * @param \Sellastica\Project\Model\SettingsAccessor $settingsAccessor
	 */
	public function __construct(
		RabbitMQFactory $rabbitMQFactory,
		\Gamee\RabbitMQ\Connection\ConnectionsDataBag $dataBag,
		\Sellastica\Project\Model\SettingsAccessor $settingsAccessor
	)
	{
		$this->rabbitMQFactory = $rabbitMQFactory;
		$this->settingsAccessor = $settingsAccessor;
		$this->dataBag = $dataBag;
	}

	public function initialize(): void
	{
		if ($this->isInitialized()) {
			return;
		}

		$apiClient = $this->rabbitMQFactory->create();
		$credentials = (object)$this->dataBag->getDataBykey('default');
		//create vhost
		$apiClient->put("vhosts/$credentials->vhost");
		//assign user to vhost
		$apiClient->put("permissions/$credentials->vhost/$credentials->user", [
			'configure' => '.*',
			'write' => '.*',
			'read' => '.*',
		]);
		//save initialization
		$this->settingsAccessor->get()->saveSettingValue('initialized', 'rabbitmq', 1);
	}

	/**
	 * @return bool
	 */
	public function isInitialized(): bool
	{
		return (bool)$this->settingsAccessor->get()->getSetting('rabbitmq.initialized');
	}
}
