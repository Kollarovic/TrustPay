<?php


use Tester\Assert,
	Kollarovic\TrustPay\TrustPay;

require __DIR__ . '/../bootstrap.php';


const URL_TEST = 'https://test.trustpay.eu/mapi/pay.aspx';
const URL_PRODUCTION = 'https://ib.trustpay.eu/mapi/pay.aspx';
const URL_CARD = 'https://ib.trustpay.eu/mapi/cardpayments.aspx';

$key = 'abcd1234';
$aid = '9876543210';
$amt = '123.45';
$cur = 'EUR';
$ref = '1234567890';
$sig = 'DF174E635DABBFF7897A82822521DD739AE8CC2F83D65F6448DD2FF991481EA3';


////////////////////////////////////////////////////////////////////////


$trustpay = new TrustPay($aid, $key, $cur, false);

$urlPay = $trustpay->getPayUrl($amt, $ref);

Assert::contains(URL_PRODUCTION, $urlPay);

$parseUrl = parse_url($urlPay);
parse_str($parseUrl['query'], $query);

Assert::equal($aid, $query['AID']);
Assert::equal($amt, $query['AMT']);
Assert::equal($cur, $query['CUR']);
Assert::equal($ref, $query['REF']);
Assert::equal($sig, $query['SIG']);


////////////////////////////////////////////////////////////////////////


$urlCard = $trustpay->getCardUrl($amt, $ref);

Assert::contains(URL_CARD, $urlCard);

$parseUrl = parse_url($urlCard);
parse_str($parseUrl['query'], $query);

Assert::equal($aid, $query['AID']);
Assert::equal($amt, $query['AMT']);
Assert::equal($cur, $query['CUR']);
Assert::equal($ref, $query['REF']);
Assert::equal($sig, $query['SIG']);


////////////////////////////////////////////////////////////////////////


$trustpay = new TrustPay($aid, $key, $cur, true);

$urlPay = $trustpay->getPayUrl($amt, $ref);
Assert::contains(URL_TEST, $urlPay);

$urlCard = $trustpay->getCardUrl($amt, $ref);
Assert::contains(URL_TEST, $urlCard);

