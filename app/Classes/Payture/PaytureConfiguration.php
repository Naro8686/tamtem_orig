<?php

namespace App\Classes\Payture;

/**
 * Payture basic configuration class
 *
 * Uses PHP version 5
 *
 * @category Main class
 * @version 1.0
 * @since 1.0 Basic version
 */
abstract class PaytureConfiguration
{

    const ENV_PRODUCTION = 1;
    const ENV_DEVELOPMENT = 2;

    // -------------------------- DEV -------------------------------------------
    // private static $_env = self::ENV_DEVELOPMENT;
    // private static $_merchantKey = "MerchantTamtem"; // dev
    // private static $_merchantCheckPassword = "1234"; // dev- пароль для чеков 

    // -------------------------- PROD -------------------------------------------
    private static $_env = self::ENV_PRODUCTION; // prod
    private static $_merchantKey = "TamtemPSB3DS"; // prod - - терминал для проведения операций с обязательным вводом CVV и 3DS
    private static $_merchantCheckPassword = "mnVkeXFC"; //  prod - пароль для чеков 

    /** @var string API prefix for URL */
    private static $_apiPrefix = "";
    /** @var string url redirect back */
    private static $_urlRedirectBack = "";

    /**
     * Set API prefix for URL creation
     *
     * @param string $prefix API Prefix
     */
    public static function setApiPrefix( $prefix )
    {
        self::$_apiPrefix = $prefix;
    }

    /**
     * Return API prefix for URL creation
     *
     * @return string API Prefix
     */
    public static function getApiPrefix()
    {
        return self::$_apiPrefix;
    }

        /**
     * Set API prefix for URL creation
     *
     * @param string $url
     */
    public static function setUrlRedirectBack( $url )
    {
        self::$_urlRedirectBack = $url;
    }

    /**
     * Return API prefix for URL creation
     *
     * @return string UrlRedirectBack
     */
    public static function getUrlRedirectBack()
    {
        return self::$_urlRedirectBack;
    }

    /**
     * Set environment for domain choose
     *
     * @param int $environment Environment
     */
    public static function setEnvironment( $environment )
    {
        self::$_env = $environment;
    }

    /**
     * Return environment for domain choose
     *
     * @return int Environment
     */
    public static function getEnvironment()
    {
        return self::$_env;
    }

    /**
     * Set Merchant ID
     *
     * @param string $merchantKey Merchant ID, issued with test/production access parameters
     */
    public static function setMerchantKey( $merchantKey )
    {
        self::$_merchantKey = $merchantKey;
    }

    /**
     * Return Merchant ID
     *
     * @return string Merchant ID, issued with test/production access parameters if set
     */
    public static function getMerchantKey()
    {
        return self::$_merchantKey;
    }

    /**
     * Set Merchant check password
     *
     * @param string $merchantCheckPassword Merchant check password, issued with test/production access parameters
     */
    public static function setMerchantCheckPassword( $merchantCheckPassword )
    {
        self::$_merchantCheckPassword = $merchantCheckPassword;
    }

    /**
     * Return Merchant check password
     *
     * @return string Merchant check password, issued with test/production access parameters if set
     */
    public static function getMerchantCheckPassword()
    {
        return self::$_merchantCheckPassword;
    }
}