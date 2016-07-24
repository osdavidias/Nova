<?php
namespace Nova\Dostava2\Model\Carrier;
use Magento\Shipping\Model\Rate\Result;
/**
Naslijeđuje klasu AbstractCarrier i provodi interface CarrierInterface
*/
class Dostava2 extends \Magento\Shipping\Model\Carrier\AbstractCarrier
implements
\Magento\Shipping\Model\Carrier\CarrierInterface {
protected $_code = 'dostava2';
/**
* @var \Magento\Shipping\Model\Rate\ResultFactory
*/
protected $_rateResultFactory;
/**
* @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
*/
protected $_rateMethodFactory;
public function __construct(
\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
\Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory
$rateErrorFactory,
\Psr\Log\LoggerInterface $logger,
\Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
\Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
$rateMethodFactory,
array $data = []
) {
$this->_rateResultFactory = $rateResultFactory;
$this->_rateMethodFactory = $rateMethodFactory;
parent::__construct($scopeConfig, $rateErrorFactory, $logger,
$data);
}
public function
collectRates(\Magento\Quote\Model\Quote\Address\RateRequest $request) {
if (!$this->getConfigFlag('active')) {
return false;
}
$result = $this->_rateResultFactory->create();

//Provjer je li prva metoda ukljucena i vadi varijable iz config.xml

if ($this->getConfigData('prva_metoda_ukljucena')) {
$metoda_dostave = $this->_rateMethodFactory->create();
$metoda_dostave->setCarrier($this->_code);
$metoda_dostave->setCarrierTitle($this->getConfigData('name'));
$metoda_dostave->setMethod('prva_metoda');
$metoda_dostave->setMethodTitle($this->getConfigData('naziv_prve_metode'));
$metoda_dostave->setPrice($this->getConfigData('cijena_prve_metode'));
$metoda_dostave->setCost($this->getConfigData('cijena_prve_metode'));
$result->append($metoda_dostave);
}
//Provjeri je li druga metoda ukljucena
if ($this->getConfigData('druga_metoda_ukljucena')) {
$metoda_dostave = $this->_rateMethodFactory->create();
$metoda_dostave->setCarrier($this->_code);
$metoda_dostave->setCarrierTitle($this->getConfigData('name'));
$metoda_dostave->setMethod('druga_metoda');
$metoda_dostave->setMethodTitle($this->getConfigData('naziv_druge_metode'));
$metoda_dostave->setPrice($this->getConfigData('cijena_druge_metode'));
$metoda_dostave->setCost($this->getConfigData('cijena_druge_metode'));
$result->append($metoda_dostave);
}
return $result;
}

// prikupi dostupne metode
public function getAllowedMethods() {
return ['dostava2' => $this->getConfigData('name')];
}

// omogući praćenje pošiljke
public function isTrackingAvailable() {
return true;
}
}// kraj klase

