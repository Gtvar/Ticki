#!/usr/bin/env php
<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;
use Ticki\Core\Application\MainApplication;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

date_default_timezone_set('UTC');

$application = new MainApplication();
$application->run();
