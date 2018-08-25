<?php
namespace Sellastica\RabbitMQ;

class RabbitMQ
{
	/** @var string */
	private $host;
	/** @var int */
	private $port;
	/** @var string */
	private $user;
	/** @var string */
	private $password;
	/** @var string */
	private $scheme;


	/**
	 * @param string $host
	 * @param int $port
	 * @param string $user
	 * @param string $password
	 * @param string $scheme
	 */
	public function __construct(
		string $host,
		int $port,
		string $user,
		string $password,
		string $scheme = 'http://'
	)
	{
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->password = $password;
		$this->scheme = $scheme;
	}

	/**
	 * @param string $resource
	 * @return mixed
	 */
	public function get(string $resource)
	{
		$url = $this->buildUrl($resource);
		return $this->request('GET', $url);
	}

	/**
	 * @param string $resource
	 * @param array|null $body
	 * @return mixed
	 */
	public function put(string $resource, array $body = null)
	{
		$url = $this->buildUrl($resource);
		return $this->request('PUT', $url, $body);
	}

	/**
	 * @param string $resource
	 * @param array|null $body
	 * @return mixed
	 */
	public function post(string $resource, array $body = null)
	{
		$url = $this->buildUrl($resource);
		return $this->request('POST', $url, $body);
	}

	/**
	 * @param string $resource
	 * @return mixed
	 */
	public function delete(string $resource)
	{
		$url = $this->buildUrl($resource);
		return $this->request('DELETE', $url);
	}

	/**
	 * @param string $resource
	 * @return string
	 */
	private function buildUrl(string $resource): string
	{
		return $this->scheme . $this->host . ':' . $this->port . '/api/' . $resource;
	}

	/**
	 * @param null|string $method
	 * @param string $url
	 * @param array|null $body
	 * @return mixed
	 */
	public function request(string $method, string $url, array $body = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Accept: application/json',
		]);
		if (is_array($body)) {
			$body = json_encode($body, JSON_PRETTY_PRINT);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		}

		$response = curl_exec($ch);
		curl_close($ch);

		return !empty($response) && is_string($response)
			? \Nette\Utils\Json::decode($response)
			: $response;
	}
}
