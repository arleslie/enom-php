<?php

namespace arleslie\Enom\APIs;

class Domain extends \arleslie\Enom\Helper {

	/**
	 * Check the availability of a domain name.
	 *
	 * @param string $sld Second-level domain
	 * @param string $tld Top-level domain, *, *1, *2, @ (See http://www.enom.com/APICommandCatalog/API%20topics/api_Check.htm#input)
	 * @return array
	 */
	public function check($sld, $tld = '*')
	{
		return $this->request([
			'command' => 'check',
			'sld'     => $sld,
			'tld'     => $tld
		]);
	}

	/**
	 * Check the avilability of multiple domain names.
	 *
	 * @param string|array $list A string of a single second-level domain or an array of multiple full domains.
	 * @param array [$tlds] An array of Top-level domains (if $list isn't an array)
	 * @return array
	 */
	public function batchCheck($list, $tlds = false)
	{
		if (is_array($list)) {
			return $this->request([
				'command'    => 'check',
				'domainlist' => implode(',', $list)
			]);
		} elseif (is_array($tlds)) {
			return $this->request([
				'command' => 'check',
				'sld'     => $list,
				'tldlist' => implode(',', $tlds)
			]);
		}
	}

	/**
	 * Retrieve TLD characteristics in detail for a specified TLD.
	 *
	 * @param string $tld Top-level domain
	 * @return array
	 */
	public function getTLDDetails($tld)
	{
		return $this->request([
			'command' => 'GetTLDDetails',
			'tld'     => $tld
		]);
	}

	/**
	 * Retrieve a list of the TLDs that you offer.
	 *
	 * @return array
	 */
	public function getTLDList()
	{
		return $this->request([
			'command' => 'GetTLDList'
		]);
	}

	/**
	 * Generate variations of a domain name based on a search term.
	 *
	 * @param string $search Term to use to generate sugesstions.
	 * @param array [$includeTlds] An array of TLDs to generate results.
	 * @param array [$onlyTlds] Only use these TLDs in results.
	 * @param array [$excludseTlds] Exclude these TLDs in results.
	 * @param boolean [$adult] Include Adult type domains (Default: false)
	 * @param boolean [$premium] Include Premium domains (Default: true)
	 * @return array
	 */
	public function getNameSuggestions($search, $includeTlds = [], $onlyTlds = [], $excludeTlds = [], $adult = false, $premium = true)
	{
		$params = [
			'command'    => 'GetNameSuggestions',
			'SearchTerm' => $search,
			'Adult'      => $adult ? 'True' : 'False',
			'Premium'    => $premium ? 'True' : 'False'
		];

		if (!empty($includeTlds)) {
			$params['TldList'] = implode(',', $includeTlds);
		}

		if (!empty($onlyTlds)) {
			$params['OnlyTldList'] = implode(',', $onlyTlds);
		}

		if (!empty($excludeTlds)) {
			$params['ExcludeTldList'] = implode(',', $excludeTlds);
		}

		return $this->request($params);
	}

	/**
	 * Add a list of items to the shopping cart.
	 *
	 * @param array $domains ['<sld>.<tld>' => int(years)]
	 * @param string [$type] 'register' or 'renew' (Default is register)
	 * @param boolean [$autorenew] Auto renew all domains (Default is false)
	 * @param boolean [$reglock] Registar lock (Default is true)
	 * @param boolean [$usecard] Use the shopping cart, required to be true for retail accounts. (Default is true)
	 * @return array
	 */
	public function addBulkDomains($domains, $type = 'register', $autorenew = false, $reglock = true, $usecart = true)
	{
		$autorenew = $autorenew ? 1 : 0;
		$reglock   = $reglock ? 1 : 0;
		$usecart   = $usecart ? 1 : 0;

		$params = [
			'command' => 'AddBulkDomains',
			'producttype' => $type,
			'autorenew' => $autorenew,
			'reglock' => $reglock,
			'usecard' => $usecard
		];

		$i = 1;
		foreach ($domains as $domain => $years) {
			$domain = explode('.', $domain);
			$sld = $domain[0];
			$tld = $domai[1];

			$params["sld{$i}"] = $sld;
			$params["tld{$i}"] = $tld;
			$params["numyears{$i}"] = $years;

			$i++;
		}

		$params['listcount'] = $i;

		return $this->request($params);
	}

	/**
	 * Cancel a pre-order for a specific domain name.
	 *
	 * @param string $domain The Domain Name to cancel
	 * @param string $orderid The order id the domain name belongs to
	 * @return array
	 */
	public function cancelOrder($domain, $orderid)
	{
		return $this->request([
			'command' => 'cancelorder',
			'domain' => $domain,
			'orderid' => $orderid
		]);
	}

	/**
	 * Delete a domain name registration.
	 *
	 * @param string $sld Second-level domain name.
	 * @param string $tld Top-level domain name.
	 * @param string $ip End User's IP address. (###.###.###.###)
	 * @return array
	 */
	public function deleteRegistration($sld, $tld, $ip)
	{
		return $this->request([
			'command' => 'deleteregistration',
			'sld' => $sld,
			'tld' => $tld,
			'EndUserIp' => $ip
		]);
	}

	/**
	 * Retrieve the up-to-date Registration Agreement.
	 *
	 * @param string $page Which agreement to retrieve. (See: http://www.enom.com/APICommandCatalog/API%20topics/api_GetAgreementPage.htm)
	 * @param string $language Language of agreement. [Eng, English, Ger, German, Por, Portuguese, Spa, Spanish] (Default is English)
	 * @return array
	 */
	public function getAgreementPage($page = 'agreement', $language = 'English')
	{
		return $this->request([
			'command' => 'GetAgreementPage',
			'page' => $page,
			'language' => $language
		]);
	}

	/**
	 * Retrieve the settings for email confirmations of orders.
	 *
 	 * @return array
	 */
	public function getConfirmationSettings()
	{
		return $this->request([
			'command' => 'GetConfirmationSettings'
		]);
	}

	/**
	 * This command retrieves the extended attributes for a country code TLD (required parameters specific to the country code).
	 *
	 * @return array
	 */
	public function getExtAttributes($tld)
	{
		return $this->request([
			'command' => 'GetExtAttributes',
			'tld' => $tld
		]);
	}

	/**
	 * Retrieve a list of language codes currently supported for a TLD.
	 *
	 * @return array
	 */
	public function getIDNCodes($tld)
	{
		return $this->request([
			'command' => 'GetIDNCodes',
			'tld' => $tld
		]);
	}

	/**
	 * Generate variations of a domain name that you specify, for .com, .net, .tv, and .cc TLDs.
	 *
	 * @param string $sld Second-level domain name
	 * @param string [$tld] Top-level domain name (Default: 'com')
	 * @param array [$tldlist] List of top-level domains
	 * @param boolean [$sensitivecontent] Block potentially offensive content. (Default: false)
	 * @param int [$max] Maximum length of SLD to return.
	 * @param boolean [$hyphens] Return domain names that include hyphens. (Default: false)
	 * @param boolean [$numbers] Return domains that include numbers. (Default: true)
	 * @param string [$basic] Higher values return suggestions that are built by addeding prefixes, suffices and words to sld. (Off, Low, Medium, High) (Default: medium)
	 * @param string [$related] Higher vlues return domain names by interpreting the input semnatically and construct suggestions with similar meaning. (Off, Low, Medium, High) (Default: high)
	 * @param string [$similar] Higher values return suggestions that are similar to customer's input but not necessarily in meaning. (Off, Low, Medium, High) (Default: medium)
	 * @param string [$topical] Higher values return suggestions that reflect current topics and popular words. (Off, Low, Medium, High) (Default: high)
	 * @return array
	 */
	public function nameSpinner($sld, $tld = 'com', $tldlist = [], $sensitivecontent = false, $max = 64, $hyphens = false, $numbers = true, $basic = 'medium', $related = 'high', $similar = 'medium', $topical = 'medium')
	{
		return $this->request([
			'command' => 'NameSpinner',
			'sld' => $sld,
			'tld' => $tld,
			'tldlist' => implode(',', $tldlist),
			'sensitivecontent' => $sensitivecontent,
			'maxlength' => $max,
			'usehyphens' => $hyphens,
			'usenumbers' => $numbers,
			'basic' => $basic,
			'related' => $related,
			'similar' => $similar,
			'topical' => $topical
		]);
	}

	/**
	 * @todo Refactor all functions below this.
	 */

	public function Purchase(
		$SLD,
		$TLD,
		$EndUserIP,
		$RegistrantFirstName,
		$RegistrantLastName,
		$RegistrantOrganizationName = '',
		$RegistrantJobTitle,
		$RegistrantAddress1,
		$RegistrantAddress2 = '',
		$RegistrantCity,
		$RegistrantStateProvince,
		$RegistrantPostalCode,
		$RegistrantCountry,
		$RegistrantEmailAddress,
		$RegistrantPhone,
		$RegistrantFax,
		$ContactTypeFirstName = '',
		$ContactTypeLastName,
		$ContactTypeOrganizationName = '',
		$ContactTypeJobTitle,
		$ContactTypeAddress1,
		$ContactTypeAddress2 = '',
		$ContactTypeCity,
		$ContactTypeStateProvince = '',
		$ContactTypePostalCode = '',
		$ContactTypeCountry,
		$ContactTypeEmailAddress,
		$ContactTypePhone,
		$ContactTypeFax = '',
		$NumYears = 1,
		$lockRegistar = true,
		$autoRenew = false,
		$EmailNotify = false,
		$DomainPassword = '',
		$QueueOrder = false,
		$AllowQueuing = true
	)
	{
		return $this->request([
			'command' => 'Purchase',
			'SLD' => $SLD,
			'TLD' => $TLD,
			'CustomerSuppliedPrice' => $CustomerSuppliedPrice,
			'PremiumDomain' => $PremiumDomain,
			'UseDNS' => 'default',
			'UnLockRegistrar' => $lockRegistar ? 0 : 1,
			'RenewName' => $autoRenew ? 1 : 0,
			'DomainPassword' => $DomainPassword,
			'EmailNotify' => $EmailNotify ? 1 : 0,
			'NumYears' => $NumYears,
			'QueueOrder' => $QueueOrder ? 1 : 0,
			'AllowQueuing' => $AllowQueuing ? 1 : 0,
			'IgnoreNSFail' => 'Yes',
			'EndUserIP' => $EndUserIP,
			'RegistrantFirstName' => $RegistrantFirstName,
			'RegistrantLastName' => $RegistrantLastName,
			'RegistrantOrganizationName' => $RegistrantOrganizationName,
			'RegistrantJobTitle' => $RegistrantJobTitle,
			'RegistrantAddress1' => $RegistrantAddress1,
			'RegistrantAddress2' => $RegistrantAddress2,
			'RegistrantCity' => $RegistrantCity,
			'RegistrantStateProvinceChoice' => $RegistrantStateProvinceChoice,
			'RegistrantStateProvince' => $RegistrantStateProvince,
			'RegistrantPostalCode' => $RegistrantPostalCode,
			'RegistrantCountry' => $RegistrantCountry,
			'RegistrantEmailAddress' => $RegistrantEmailAddress,
			'RegistrantPhone' => $RegistrantPhone,
			'RegistrantFax' => $RegistrantFax,
			'ContactTypeFirstName' => $ContactTypeFirstName,
			'ContactTypeLastName' => $ContactTypeLastName,
			'ContactTypeOrganizationName' => $ContactTypeOrganizationName,
			'ContactTypeJobTitle' => $ContactTypeJobTitle,
			'ContactTypeAddress1' => $ContactTypeAddress1,
			'ContactTypeAddress2' => $ContactTypeAddress2,
			'ContactTypeCity' => $ContactTypeCity,
			'ContactTypeStateProvinceChoice' => $ContactTypeStateProvinceChoice,
			'ContactTypeStateProvince' => $ContactTypeStateProvince,
			'ContactTypePostalCode' => $ContactTypePostalCode,
			'ContactTypeCountry' => $ContactTypeCountry,
			'ContactTypeEmailAddress' => $ContactTypeEmailAddress,
			'ContactTypePhone' => $ContactTypePhone,
			'ContactTypeFax' => $ContactTypeFax
		]);
	}

	public function Queue_GetDomains($DisplayMetrics = true, $DisplayDomains = false,$RecordStart = 1,$PagingSize = 25,$SortBy = 'DomainName',$SortOrder = 'Asc',$FilterTLD = false,$FilterQueue = false,$FilterStatus = false,$FilterStatusDesc = false,$DomainNameFilter = false)
	{
		return $this->request([
			'command' => 'Queue_GetDomains',
			'DisplayMetrics' => $DisplayMetrics ? 'true' : 'false',
			'DisplayDomains' => $DisplayDomains ? 'true' : 'false',
			'RecordStart' => $RecordStart,
			'PagingSize' => $PagingSize,
			'SortBy' => $SortBy,
			'SortOrder' => $SortOrder,
			'FilterTLD' => $FilterTLD,
			'FilterQueue' => $FilterQueue,
			'FilterStatus' => $FilterStatus,
			'FilterStatusDesc' => $FilterStatusDesc,
			'DomainNameFilter' => $DomainNameFilter
		]);
	}

	/**
	 * Retrieve a list of extended attributes for a specific queue.
	 *
	 * @param array $queueIds List of Queue IDs (Use Queue_GetInfo to get Ids)
	 * @return array
	 */
	public function Queue_GetExtAttributes($queueIds = [])
	{
		return $this->request([
			'command' => 'Queue_GetExtAttributes',
			'QIDList' => implode(',', $queueIds)
		]);
	}

	/**
	 * Retrieve a list of all queues, which can be filtered as appropriate.
	 *
	 * @return type
	 */
	public function Queue_GetInfo()
	{
		return $this->request([
			'command' => 'Queue_GetInfo'
		]);
	}

	/**
	 * Retrieve a detailed queue domain order.
	 *
	 * @param int $OrderID The Order ID. (Use Queue_GetOrders to get Id)
	 * @return array
	 */
	public function Queue_GetOrderDetail($OrderID)
	{
		return $this->request([
			'command' => 'Queue_GetOrderDetail',
			'OrderID' => $OrderID
		]);
	}

	public function Queue_GetOrders()
	{
		return $this->request([
			'command' => 'Queue_GetOrders'
		]);
	}

	public function TM_Check($SLD,$TLD)
	{
		return $this->request([
			'command' => 'TM_Check',
			'SLD' => $SLD,
			'TLD' => $TLD
		]);
	}

	public function TM_GetNotice($SLD,$LookupKey)
	{
		return $this->request([
			'command' => 'TM_GetNotice',
			'SLD' => $SLD,
			'LookupKey' => $LookupKey
		]);
	}

	public function TM_UpdateCart($SLD,$tcnID,$tcnExpDate,$tcnAcceptDate)
	{
		return $this->request([
			'command' => 'TM_UpdateCart',
			'SLD' => $SLD,
			'tcnID' => $tcnID,
			'tcnExpDate' => $tcnExpDate,
			'tcnAcceptDate' => $tcnAcceptDate
		]);
	}
}