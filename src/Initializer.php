<?php
namespace Sellastica\RabbitMQ;

class Initializer
{
	/** @var \Gamee\RabbitMQ\Connection\ConnectionsDataBag */
	private $dataBag;
	/** @var \Sellastica\Project\Model\SettingsAccessor */
	private $settingsAccessor;


	/**
	 * @param \Gamee\RabbitMQ\Connection\ConnectionsDataBag $dataBag
	 * @param \Sellastica\Project\Model\SettingsAccessor $settingsAccessor
	 */
	public function __construct(
		\Gamee\RabbitMQ\Connection\ConnectionsDataBag $dataBag,
		\Sellastica\Project\Model\SettingsAccessor $settingsAccessor
	)
	{
		$this->dataBag = $dataBag;
		$this->settingsAccessor = $settingsAccessor;
	}

	public function initialize(): void
	{
		$settings = $this->settingsAccessor->get();
		if ($settings->getSetting('rabbitmq.initialized')) {
			return;
		}

		$credentials = (object)$this->dataBag->getDataBykey('default');
		$apiClient = new RabbitMQ(
			$credentials->host,
			15672, //default Web UI and API port
			$credentials->user,
			$credentials->password,
			'/'
		);
		//create vhost
		$apiClient->put("vhosts/$credentials->vhost");
		//assign user to vhost
		$apiClient->put("permissions/$credentials->vhost/$credentials->user", [
			'configure' => '.*',
			'write' => '.*',
			'read' => '.*',
		]);
		//save initialization
		$settings->saveSettingValue('initialized', 'rabbitmq', 1);
	}
}
