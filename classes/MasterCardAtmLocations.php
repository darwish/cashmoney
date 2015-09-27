<?php

namespace CashMoney;

if (!defined('MASTERCARD_DIR')) {
    define('MASTERCARD_DIR', ROOT_DIR . 'vendor/darwish/mastercard-api-php/');
}

include_once MASTERCARD_DIR . 'common/Environment.php';
include_once MASTERCARD_DIR . 'services/location/atms/AtmLocationService.php';


class MasterCardAtmLocations {

    private $locationService;

    public function __construct() {
        $testUtils = new TestUtils(\Environment::SANDBOX);
        $this->locationService = new \AtmLocationService(TestUtils::SANDBOX_CONSUMER_KEY, $testUtils->getPrivateKey(), \Environment::SANDBOX);
    }

    public function findAtms($latitude = null, $longitude = null) {
        $options = new \AtmLocationRequestOptions(0, 25);

        if ($latitude !== null && $latitude !== $longitude) {
            $options->setLatitude($latitude);
            $options->setLongitude($longitude);
            $options->setRadius(100);
        }

        $atms = $this->locationService->getAtms($options)->getAtm();

        $return = [];
        foreach ($atms as $atm) {
            $return[] = [
                'position' => [
                    'lat' => $atm->getLocation()->getPoint()->getLatitude(),
                    'lng' => $atm->getLocation()->getPoint()->getLongitude(),
                ],
                'title' => $atm->getLocation()->getName() .' (fee: '.$atm->getAccessFees().')',
            ];
        }

        return json_encode($return);

    }
}