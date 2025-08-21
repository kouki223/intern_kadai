<?php
// Bootstrap the framework DO NOT edit this
require COREPATH.'bootstrap.php';

\Autoloader::add_classes(array(
	// Add classes you want to override here
	// Example: 'View' => APPPATH.'classes/view.php',
));

// Register the autoloader
\Autoloader::register();

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGING
 * Fuel::PRODUCTION
 */
\Fuel::$env = \Arr::get($_SERVER, 'FUEL_ENV', \Arr::get($_ENV, 'FUEL_ENV', \Fuel::DEVELOPMENT));

// Initialize the framework with the config file.
\Fuel::init('config.php');

switch (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : ''){
    case 'production.co.jp':
        //本番
        Fuel::$env = Fuel::PRODUCTION;
        break;
    case 'staging.co.jp':
        //テスト環境 
        Fuel::$env = Fuel::STAGING;
        break;
    default:
        //ローカル環境
        Fuel::$env = Fuel::DEVELOPMENT;
        break;
}