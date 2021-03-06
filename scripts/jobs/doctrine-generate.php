#!/usr/bin/php
<?php
/**
 * CloneUI.com - Base Framework
 * Generate Models, Entities, et al 
 * for Doctrine 2.x
 *
 * @author      CloneUI <code@cloneui.com>
 * @copyright	2014 CloneUI
 * @link        http://cloneui.com
 * @license     Commercial
 *
 * @since  	    Thursday, July 24, 2014, 15:57 GMT+1
 * @modified    $Date: 2013-10-12 00:13:50 -0700 (Sat, 12 Oct 2013) $ $Author: marquis@marquisknox.com $
 * @version     $Id: UploadController.php 61 2013-10-12 07:13:50Z marquis@marquisknox.com $
 *
 * @category    Scripts; Bash
 * @package     Base Framework
*/

error_reporting( E_ALL );
ini_set( 'display_errors', true );

if ( function_exists('set_time_limit') AND get_cfg_var('safe_mode') == 0 ) {
	@set_time_limit(0);
}

define( 'BASEPATH', dirname( dirname( dirname( __FILE__ ) ) ) );
set_include_path( 
	BASEPATH.'/application/'.PATH_SEPARATOR.
	BASEPATH.'/application/configs'.PATH_SEPARATOR.
	BASEPATH.'/application/models'.PATH_SEPARATOR.
	BASEPATH.'/library/'.PATH_SEPARATOR. 
	BASEPATH.'/library/PEAR'.PATH_SEPARATOR.
	get_include_path()
);

require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader( true );

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

require_once('functions.php');
require_once('constants.php');

if( !defined( 'RUN_ENV' ) ) {
	define( 'RUN_ENV', 'DEV' );	
}

$config	= new Zend_Config_Ini( APP_DIR.'/configs/db.ini', strtolower( RUN_ENV ) );
$db		= $config->params->toArray();

$paths		= array( BASEPATH.'/application/models/Entities' );
$isDevMode	= ( RUN_ENV == 'DEV' ) ? true : false;

// DB connection config
$dbParams = array(
	'driver'		=> 'pdo_mysql',
	'host'			=> $db['host'],
	'user'     		=> $db['username'],
	'password' 		=> $db['password'],
	'dbname'   		=> $db['dbname'],
	'charset' 		=> 'utf8',
	'driverOptions' => array(
		'1002' =>'SET NAMES utf8'
	)
);

$doctrineConfig	= Setup::createAnnotationMetadataConfiguration( $paths, $isDevMode );
$entityManager	= EntityManager::create( $dbParams, $doctrineConfig );
ConsoleRunner::createHelperSet( $entityManager );