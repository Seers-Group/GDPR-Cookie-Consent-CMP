<?php

/**
 * Configuration for: Database Connection
 * This is the place where your database login constants are saved
 *
 * DB_HOST: database host, usually it's "127.0.0.1" or "localhost", some servers also need port info
 * DB_NAME: name of the database. please note: database and database table are not the same thing
 * DB_USER: user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT.
 *          by the way, it's bad style to use "root", but for development it will work.
 * DB_PASS: the password of the above user
 * MODE : Project on local or server
 */

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define("DB_HOST", "localhost");
    define("DB_NAME", "seers-cookie-consent");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define('MODE', 'dev');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    /* define site url and admin details */
    define('ABS_PATH', dirname(dirname(__FILE__)));
    define('SITE_URL', 'https://localhost/private-apps/seers-cookie-consent/');
    define('SITE_USER_URL', 'https://localhost/private-apps/seers-cookie-consent/user/');
    define('ASSETS_URL','https://localhost/private-apps/seers-cookie-consent/assets/');  
} elseif ($_SERVER['SERVER_NAME'] == 'gdpr-cookie-consent-banner-cookie-notice-seers.seersco.com') {
    define("DB_HOST", "livedbhost");
    define("DB_NAME", "livedbname");
    define("DB_USER", "livedbuser");
    define("DB_PASS", "livedbpassword");
    define('MODE', 'live');
    
    /* define site url and admin details */
    define('ABS_PATH', dirname(dirname(__FILE__)));
    define('SITE_URL', 'https://gdpr-cookie-consent-banner-cookie-notice-seers.seersco.com/');
    define('SITE_USER_URL', 'https://gdpr-cookie-consent-banner-cookie-notice-seers.seersco.com/user/');
    define('ASSETS_URL','https://gdpr-cookie-consent-banner-cookie-notice-seers.seersco.com/assets/');
} else {
    echo 'Undefine host';
}

/**
 * Configuration for: Shopify
 */
define("SHOPIFY_API_KEY", "--- shopify api key ---");
define("SHOPIFY_SECRET", "--- shopify secret key ---");
define("SHOPIFY_SCOPE", "read_themes,write_themes,write_script_tags,read_script_tags, read_analytics");
define("SITE_PATH", "https://gdpr-cookie-consent-banner-cookie-notice-seers.seersco.com/index.php");

/*
 * Database connection
 *  */
class DB_Class {

    function __construct() {
        if (!isset($GLOBALS['conn']) && empty($GLOBALS['conn'])) {
            $objdbconn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (mysqli_connect_errno()) {
                echo "Failed : connect to MySQL: " . mysqli_connect_error();
                die;
            }
            $GLOBALS['conn'] = $objdbconn;
            mysqli_set_charset($objdbconn, "utf8mb4");
            return $objdbconn;
        }
    }

}

$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
define('PROTOCOL', $protocol);

define('SITE_NAME', 'Seers Cookie Consent');
define('SITE_EMAIL', 'alex.reed@seersco.com');
/**
 * Table name constant
 */
define('TABLE_USER_STORES', 'user_stores');

/* Database formate Date decalre */
define('DATE', date('Y-m-d H:i:s'));