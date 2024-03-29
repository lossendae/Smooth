<?php
/**
 * Smooth build script
 *
 * @package discuss
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

define('PKG_NAME','Smooth Manager Theme');
define('PKG_NAMESPACE','Smooth');
define('PKG_VERSION','1.01');
define('PKG_RELEASE','RC1');

/* override with your own defines here (see build.config.sample.php) */
require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$root = dirname(dirname(__FILE__)).'/';
$sources = array(
    'root' => $root,
    'build' => $root . '_build/',
    'docs' => $root.'docs/',	
    'controllers' => $root.'manager/controllers/smooth',
    'templates' => $root.'manager/templates/smooth',
	'resolvers' => $root . '_build/resolvers/',
);
unset($root);

$modx= new modX();
$modx->initialize('mgr');
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAMESPACE,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAMESPACE,false,true,'{base_path}manager/templates/smooth/');

$modx->log(modX::LOG_LEVEL_INFO, 'Create Category...');

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);

$vehicle= $builder->createVehicle($category, array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => false,
));

$modx->log(modX::LOG_LEVEL_INFO, 'Adding file resolvers to category...');

$vehicle->resolve('file',array(
    'source' => $sources['templates'],
    'target' => "return MODX_BASE_PATH . 'manager/templates/';",
));
$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'resolve.settings.php',
));
$builder->putVehicle($vehicle);

/* now pack in the license file, readme and setup options */
$modx->log(modX::LOG_LEVEL_INFO,'Adding package attributes and setup options...');
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'setup-options' => array(
        'source' => $sources['build'].'setup.options.php',
    ),
));

/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...');
$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();