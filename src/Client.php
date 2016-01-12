<?php

namespace arleslie\Enom;

use GuzzleHttp\Client as Guzzle;

class Client {

	const ENOM_API_URL = 'http://reseller.enom.com';
	const ENOM_API_TEST_URL = 'http://resellertest.enom.com';

	protected $guzzle = false;
	private $apis = [];

	public function __construct($username, $password, $testmode)
	{
		$this->guzzle = new Guzzle([
			'base_url' => $testmode ? self::ENOM_API_TEST_URL : self::ENOM_API_URL,
			'defaults' => [
				'query' => [
					'uid' => $username,
					'pw' => $password,
					'responsetype' => 'xml'
				]
			]
		]);
	}

	public function _getApi($api)
	{
		if (empty($this->apis[$api])) {
			$class = 'arleslie\\Enom\\APIs\\' . $api;
			$this->apis[$api] = new $class($this->guzzle);
		}

		return $this->apis[$api];
	}

	public function Domain() { return $this->_getApi('Domain'); }
}