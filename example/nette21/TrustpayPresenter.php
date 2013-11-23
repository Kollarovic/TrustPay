<?php

namespace Example;

use Kollarovic\TrustPay\TrustPay;


class TrustpayPresenter extends BasePresenter
{

	/** @var \Kollarovic\TrustPay\TrustPay  @inject */
	public $trustPay;


	/**
	 * @param float
	 * @param string
	 * @return void
	 */
	public function actionPay($amount, $ref)
	{
		$url = $this->trustPay->getPayUrl($amount, $ref);
		$this->redirectUrl($url);
	}


	/**
	 * @param float
	 * @param string
	 * @return void
	 */
	public function actionCardPay($amount, $ref)
	{
		$url = $this->trustPay->getCardUrl($amount, $ref);
		$this->redirectUrl($url);
	}
	

	/**
	 * @param string
	 * @param string
	 * @param string
	 * @return void
	 */
	public function actionSuccess($REF, $RES, $PID)
	{
		if ($RES === TrustPay::SUCCESS) {
			$this->flashMessage('Platba prebehla úspešne.');
		} elseif ($RES === TrustPay::PENDING) {
			$this->flashMessage('Čakáme na platbu.');
		//...	
		} else {
			$this->flashMessage('Nepodarilo previesť platbu. Prosím, skúste neskôr.', 'warning');
		}
		$this->redirect('Homepage:default');
	}


	/**
	 * @param string
	 * @param string
	 * @param string
	 * @return void
	 */
	public function actionError($REF, $RES, $PID)
	{
		$this->flashMessage('Nepodarilo previesť platbu. Prosím, skúste neskôr.', 'warning');
		// save error...
		$this->redirect('Homepage:default');
	}


	/**
	 * @param string
	 * @param string
	 * @param string
	 * @return void
	 */
	public function actionCancel($REF, $RES, $PID)
	{
		$this->flashMessage('Platba bola zrušená zo strany uživateľa.', 'warning');
		// save cancel...
		$this->redirect('Homepage:default');
	}


	/**
	 * @param string
	 * @param string
	 * @param string
	 * @return void
	 */
	public function renderNotification($RES, $AMT, $REF)
	{
		$result = $this->params;
		if ($this->trustPay->isOk($result)) { //overime ci neboli podvrhunte data
			//save result...	
		}
	}

}
