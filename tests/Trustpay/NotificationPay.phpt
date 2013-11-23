<?php


use Tester\Assert,
	Kollarovic\TrustPay\TrustPay;

require __DIR__ . '/../bootstrap.php';


$key = 'abcd1234';
$aid = '1234567890';
$cur = 'EUR';

$result = array (
	'AID '=> $aid,
	'TYP' => 'CRDT',
	'AMT' => '123.45',
	'CUR' => 'EUR',
	'REF' => '9876543210',
	'RES' => '0',
	'TID' => '11111',
	'OID' => '1122334455',
	'TSS' => 'Y',
	'SIG' => '97F20061340411FFE4FC1C19084E7829DCE9ABDD9DDB127841C6E8FD60BC0569'
);



$trustpay = new TrustPay($aid, $key, $cur, false);

Assert::true($trustpay->isOk($result));


$incorrectResult = $result;
$incorrectResult['AMT'] = '25.4';
Assert::false($trustpay->isOk($incorrectResult));


$incorrectResult = $result;
$incorrectResult['REF'] = '22222';
Assert::false($trustpay->isOk($incorrectResult));


$incorrectResult = $result;
$incorrectResult['RES'] = '1';
Assert::false($trustpay->isOk($incorrectResult));


$incorrectResult = $result;
$incorrectResult['SIG'] = 'DFG1345621FFE4FC1C19084E7829DCE9ABDD9DDB127841C6E8FD60BC0569';
Assert::false($trustpay->isOk($incorrectResult));
