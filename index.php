<?php

require 'vendor/autoload.php';

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
set_time_limit(0);
$wd_host = 'http://localhost:9515';
$desired_capabilities = DesiredCapabilities::phantomjs();
$desired_capabilities->setCapability('acceptSslCerts', false);
$chromeOptions = new ChromeOptions();
$arguments = ["--user-agent=Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)"];
$chromeOptions->addArguments($arguments);
$chromeOptions->addExtensions(['expansions/Block-image_v1.1.crx']);
$desired_capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
$driver = RemoteWebDriver::create($wd_host, $desired_capabilities, 5000, 30000);
$driver1 = RemoteWebDriver::create($wd_host, $desired_capabilities, 5000, 30000);
$driver->get('https://minebet.com/login');
$driver1->get('https://vodds.com');
$driver->findElement(WebDriverBy::name('LoginForm[username]'))->sendKeys("testpro");
$driver->findElement(WebDriverBy::name('LoginForm[password]'))->sendKeys("testpro");
$driver->findElement( WebDriverBy::tagName('button'))->click();
sleep(2);
//while (true) {
    $driver->get('https://minebet.com/strategies')->getPageSource();
    $html = $driver->getPageSource();
    $doc = new DOMDocument();
    $res = @$doc->loadHTML($html);
    if ($res) {
        // Извлекаем из документа все теги - <a>
        $tags = $doc->getElementsByTagName('tr');
        $i = 0;
        //Перебираем массив полученных элементов тега <a>
        foreach ($tags as $tr) {
            if ($tr->hasAttribute('data-id')) {
                $arr[$i] = $tr->getAttribute('data-id');
                $i++;
            }
        }
    }
$id = file('params.txt');
    if($arr[0] != $id[0]) {
        $doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        $nodes = $xpath->evaluate('//tr[@data-id="' . $arr[0] . '"]');
        //echo $doc->saveXML($nodes->item(0));
        //var_dump($doc->saveXML($nodes->item(0)));
        //var_dump($nodes->parentNode->replaceChild(new DOMText($nodes->nodeValue), $nodes));
        $words[0] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[1]/span');
        $words[1] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[2]');
        $words[2] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[3]/span');
        $words[3] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[12]');
        foreach( $words[0] as $obj ) {
           // echo 'URL: '.$obj->getAttribute('href');
            echo '<br>Событие<br>';
            echo '_____________________________________ <br>';
            echo 'Номер: '. $obj->nodeValue.'<br>';
        }
        foreach( $words[1] as $obj ) {
            echo 'Счет: '. $obj->nodeValue.'<br>';
        }
        foreach( $words[2] as $obj ) {
            echo 'Играют: '. $obj->nodeValue.'<br>';
        }
        foreach( $words[3] as $obj ) {
            echo 'Сумма: '. $obj->nodeValue.'<br>';
        }
        $fd = fopen("params.txt", 'w+') or die("не удалось создать файл");
        fputs($fd, $arr[0]);
        fclose($fd);
        $url = 'https://api.vk.com/method/messages.send';
        $params = array(
            'user_id' => '21383187',
            'message' => strip_tags($doc->saveXML($nodes->item(0))),
            'access_token' => 'ce1200db50d7461d24d1b0b414870ba85d718373b338ff946d82d69cf23bd12f8a346b0894945034442a7',
            'v' => '5.37',
        );
        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));
  //  }

}