<?php

namespace Kollarovic\TrustPay;

use Nette;


/**
 * TrustPay
 *
 * @author  Mario Kollarovic
*/
class TrustPay extends Nette\Object
{

	const URL_TEST = 'https://test.trustpay.eu/mapi/pay.aspx';
	const URL_PRODUCTION = 'https://ib.trustpay.eu/mapi/pay.aspx';
	const URL_CARD = 'https://ib.trustpay.eu/mapi/cardpayments.aspx';
	const SUCCESS = '0';
	const PENDING = '1';
	const AUTHORIZED = '3';
	const PROCESSING = '4';
	const INVALID_REQUEST = '1001';
	const UNKNOWN_ACCONNT = '1002';
	const MERCHANT_DISABLED = '1003';
	const INVALID_SIGN = '1004';
	const CANCEL = '1005';
	const INVALID_AUTHENTICATION = '1006';
	const DISPOSABLE_BALANCE = '1007';
	const SERVICE_NOT_ALLOWED = '1008';
	const PAYSAFECARD_TIMEOUT = '1009';
	const GENERAL_ERROR = '1100';
	const UNSUPPORTED_CURRENCY_CONVERSION = '1101';
	const UNKOWN_ERROR = -1;
	const NOTIFICATION_ERROR = -2;


	/** @var string */
	private $aid;

	/** @var string */
	private $key;

	/** @var string */
	private $currency;

	/** @var bool */
	private $sandbox;

	/** @var array */
	private $sigMessageSequence = array('AID', 'TYP', 'AMT', 'CUR', 'REF', 'RES', 'TID', 'OID', 'TSS');

	/** @var array */
	private $sig2MessageSequence = array(
		'AID', 'TYP', 'AMT', 'CUR', 'REF', 'RES', 'TID', 'OID', 'TSS', 
		'CardID', 'CardMask', 'CardExp', 'AuthNumber', 'CardRecTxSec', 'CardAcquirerResponseId'
	);


	/**
	 * @param string
	 * @param string
	 * @param string
	 * @param bool
	 * @return void
	 */
	public function __construct($aid, $key, $curency, $sandbox = false)
    {
		$this->aid = $aid;
		$this->key = $key;
		$this->currency = $curency;
		$this->sandbox = $sandbox;
	}


	/**
	 * @param string
	 */
	public function setCurrency($currency) 
	{ 
		$this->currency = $currency;
	} 


	/**
	 * @param float
	 * @param string
	 * @return string
	 */
	public function getPayUrl($amount, $reference) 
	{ 
		$url = $this->sandbox ? self::URL_TEST : self::URL_PRODUCTION;
		return $url . '?' . $this->buildQuery($amount, $reference);
	} 

	
	/**
	 * @param float
	 * @param string
	 * @return string
	 */
	public function getCardUrl($amount, $reference) 
	{ 
		$url = $this->sandbox ? self::URL_TEST : self::URL_CARD;
		return $url . '?' . $this->buildQuery($amount, $reference);
	} 

	
	/**
	 * @param array
	 * @return bool
	 */
	public function isOk(array $result) 
	{
		return isset($result['SIG2']) ? $this->isSig2Ok($result) : $this->isSigOk($result);
	}

	
	/**
	 * @param array
	 * @return bool
	 */
	private function isSigOk(array $result) 
	{
		$message = $this->createMesage($result, $this->sigMessageSequence);
		return $this->createSign($message) === @$result['SIG'];
	}

	
	/**
	 * @param array
	 * @return bool
	 */
	private function isSig2Ok(array $result) 
	{
		$message = $this->createMesage($result, $this->sig2MessageSequence);
		return $this->createSign($message) === @$result['SIG2'];
	}


	/**
	 * @param string
	 * @return string
	 */
	private function createSign($message) 
	{ 
		return strtoupper(
			hash_hmac('sha256', pack('A*', $message), pack('A*', $this->key))
		); 
	} 


	/**
	 * @param array
	 * @return string
	 */
	private function createMesage(array $result, array $sequence) 
	{
		$message = '';
		foreach($sequence as $key) {
			$message .= @$result[$key];
		}
		return $message;
	}
	
	
	/**
	 * @param float
	 * @param string
	 * @return string
	 */
	private function buildQuery($amount, $reference) 
	{
		$message = $this->aid . $amount . $this->currency  . $reference;
		$query = array(
			'AID' => $this->aid,
			'AMT' => $amount,
			'CUR' => $this->currency,
			'REF' => $reference,
			'SIG' => $this->createSign($message)
		);
		return http_build_query($query);
	}
	
}
