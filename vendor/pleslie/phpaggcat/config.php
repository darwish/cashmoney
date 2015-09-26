<?php

define('SIMPLESAML_PATH',  '../../simplesamlphp');
define('SIMPLEOAUTH_PATH',  '../../jrconlin/oauthsimple/php');

define('OAUTH_CONSUMER_KEY',   'qyprd40pYoRRO6MjOwaude2v5apd7V');
define('OAUTH_SHARED_SECRET',  'SouKS3PNDhsqElYd2hQJsrLKJ7gwws1w4cO82j0Q');

define('SAML_IDENTITY_PROVIDER_ID',  'cashmoney.269380.cc.dev-intuit.ipp.prod');
define('SAML_X509_CERT_PATH',        '../../../cashmoney.crt');
define('SAML_X509_PRIVATE_KEY_PATH', '../../../cashmoney.key');
define('SAML_NAME_ID',               'cashmoney');  // Up to you; just "keep track" of what you use

define('OAUTH_SAML_URL', 'https://oauth.intuit.com/oauth/v1/get_access_token_by_saml');
define('FINANCIAL_FEED_HOST', 'financialdatafeed.platform.intuit.com');
define('FINANCIAL_FEED_URL', 'https://'.FINANCIAL_FEED_HOST.'/');

require_once(SIMPLESAML_PATH . "/vendor/simplesamlphp/xmlseclibs/xmlseclibs.php");
require_once(SIMPLESAML_PATH . "/lib/SimpleSAML/Utilities.php");
require_once(SIMPLEOAUTH_PATH . "/OAuthSimple.php");

