<?php

use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;

$pluginForProxyLogin = 'tmp/a'.uniqid().'.zip';

$zip = new ZipArchive();
$res = $zip->open($pluginForProxyLogin, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$zip->addFile('proxy/manifest.json', 'manifest.json');
$background = file_get_contents('proxy/background.js');
$background = str_replace(['%proxy_host', '%proxy_port', '%username', '%password'], ['5.39.64.181', '54991', 'd1g1m00d', '13de02d0e0z9'], $background);
$zip->addFromString('background.js', $background);
$zip->close();

putenv("webdriver.chrome.driver=chromedriver");
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
set_time_limit(0);
$wd_host = 'http://localhost:9515';
$desired_capabilities = DesiredCapabilities::phantomjs();
$desired_capabilities->setCapability('acceptSslCerts', false);
$options = new ChromeOptions();
$options->addExtensions([$pluginForProxyLogin]);
$caps = DesiredCapabilities::chrome();
$caps->setCapability(ChromeOptions::CAPABILITY, $options);

$driver = ChromeDriver::start($caps);
$driver->get('https://old-linux.com/ip/');

header('Content-Type: image/png');
echo $driver->takeScreenshot();


unlink($pluginForProxyLogin);