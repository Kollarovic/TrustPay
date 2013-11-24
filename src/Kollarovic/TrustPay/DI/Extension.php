<?php

namespace Kollarovic\TrustPay\DI;

use Nette;


if (!class_exists('Nette\DI\CompilerExtension')) {
	class_alias('Nette\Config\CompilerExtension', 'Nette\DI\CompilerExtension');
}


/**
 * Extension
 *
 * @author  Mario Kollarovic
 */
class Extension extends Nette\DI\CompilerExtension
{

	public $defaults = array(
		'sandbox' => false,
		'curency' => 'EUR',
		'test' => array(
			'aid' => null,
			'key' => null
		),
		'production' => array(
			'aid' => null,
			'key' => null
		),
	);


	public function loadConfiguration()    
	{
		$config = $this->getConfig($this->defaults);
		$sandbox = $config['sandbox'];
		$curency = $config['curency'];
		$aid = $sandbox ? $config['test']['aid'] : $config['production']['aid'];
		$key = $sandbox ? $config['test']['key'] : $config['production']['key'];
		
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('trustpay'))
			->setClass('Kollarovic\TrustPay\TrustPay', array(
				'aid' => $aid, 
				'key' => $key, 
				'curency' => $curency, 
				'sandbox' => $sandbox, 
			));
	}

}
