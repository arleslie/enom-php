<?php

namespace arleslie\Enom;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Message\Response;

class Helper
{
	protected $client;

	public function __construct(Guzzle $client)
	{
		$this->client = $client;
	}

	protected function request($args)
	{
		$response = $this->client->get('interface.asp?'.http_build_query($args));
		return $this->parseResponse($response);
	}

	protected function parseResponse(Response $response)
	{
		$xml = $response->xml();
		if (!empty($xml->errors)) {
			return [
				'status' => 'error',
				'errors' => json_decode(json_encode($xml->errors), true)
			];
		}

		return [
			'status' => 'success',
			'response' => $xml
		];
	}
}