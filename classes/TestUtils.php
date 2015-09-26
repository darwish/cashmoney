<?php

namespace CashMoney;

class TestUtils {
    const SANDBOX_KEYSTORE_PASSWORD = "tester";
    private static $SANDBOX_KEYSTORE_PATH;
    const SANDBOX_CONSUMER_KEY = "PgQQ340jLPSDmhRydmULBSMmY9dZgB_5Z0syOKxW47d688b5!47784278446247774c45775672707867317a547a2f773d3d";

    const PRODUCTION_KEYSTORE_PASSWORD = "tester";
    const PRODUCTION_KEYSTORE_PATH = "C:\\Users\\JBK0718\\dev\\mastercard\\keystore\\production\\546536344e2b647558374a4156382f414644524173673d3d.p12";

    // APP-SPECIFIC PRODUCTION KEYS
    const LOCATION_PRODUCTION_CONSUMER_KEY = "yW8e1pgChCfdA4U3ZkSz-vBPdXgnxm1dDiVLHAVze9216d5b!546536344e2b647558374a4156382f414644524173673d3d";


    private $environment;

    public function __construct($environment)
    {
        $this->environment = $environment;
        self::$SANDBOX_KEYSTORE_PATH = ROOT_DIR . "certs/MCOpenAPI.p12";
    }

    public function getPrivateKey()
    {
        $keystorePath = "";
        $keystorePassword = "";

        if ($this->environment == \Environment::PRODUCTION)
        {
            $keystorePath = self::PRODUCTION_KEYSTORE_PATH;
            $keystorePassword = self::PRODUCTION_KEYSTORE_PASSWORD;
        }
        else
        {
            $keystorePath = self::$SANDBOX_KEYSTORE_PATH;
            $keystorePassword = self::SANDBOX_KEYSTORE_PASSWORD;
        }

        $path = realpath($keystorePath);
        $keystore = array();
        $pkcs12 = file_get_contents($path);
        
        // Read the p12 file
        trim(openssl_pkcs12_read( $pkcs12, $keystore, $keystorePassword));

        // Return private key
        if(is_array($keystore) && isset($keystore['pkey']) && !empty($keystore['pkey']))
        {
            return  $keystore['pkey'];
        }
        else
        {
            throw new \Exception('Missing private key');
        }
    }
}
