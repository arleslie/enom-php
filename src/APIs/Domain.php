<?php

namespace arleslie\Enom\APIs;

class Domain extends \arleslie\Enom\Helper {

	public function check($sld, $tld = '.com')
	{
		return $this->request([
			'command' => 'check',
			'sld' => $sld,
			'tld' => $tld
		]);
	}
}