<?php

namespace arleslie\Enom;

use GuzzleHttp\Client as Guzzle;

class Client {

	const ENOM_API_URL = 'http://reseller.enom.com/interface.asp';
	const ENOM_API_TEST_URL = 'http://resellertest.enom.com/interface.asp';

	protected $guzzle = false;

	public function __construct($username, $password, $testmod)
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


}