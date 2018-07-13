<?php

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;

require 'vendor/autoload.php';

/**
 * Вывод ошибок
 */
function debug()
{
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);
}

/**
 * авторизация в профиле
 * @param $driver
 * @param $driver1
 */
function auth($driver, $driver1)
{
    $driver->findElement(WebDriverBy::name('LoginForm[username]'))->sendKeys("testpro");
    $driver->findElement(WebDriverBy::name('LoginForm[password]'))->sendKeys("testpro");
    $driver->findElement(WebDriverBy::tagName('button'))->click();

    $driver1->findElement(WebDriverBy::name('username'))->sendKeys("demoeur0381");
    $driver1->findElement(WebDriverBy::name('accessToken'))->sendKeys("Qw5431769er!");
    $driver1->findElement(WebDriverBy::tagName('button'))->click();
}

/**
 * функция поиска матчей на сайте vodds.com
 * @param $driver
 * @param $firstCrew
 */
function find($driver, $firstCrew)
{
    $driver->findElement(WebDriverBy::cssSelector('input#s2id_autogen3.select2-input.select2-default'))->sendKeys($firstCrew);
    $driver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
}

debug();

set_time_limit(0);

$wd_host = 'http://localhost:9515';
$desired_capabilities = DesiredCapabilities::phantomjs();
$desired_capabilities->setCapability('acceptSslCerts', false);

$chromeOptions = new ChromeOptions();
$arguments = ["--user-agent=Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)"];
$chromeOptions->addArguments($arguments);
//подключение расширений
$chromeOptions->addExtensions(['extensions/Block-image_v1.1.crx']);
$chromeOptions->addExtensions(['extensions/HotspotShield.crx']);
$desired_capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

//определение окон(что в каком окне откроется)
$driver = RemoteWebDriver::create($wd_host, $desired_capabilities, 5000, 30000);
$driver1 = RemoteWebDriver::create($wd_host, $desired_capabilities, 5000, 30000);

//переходы по ссылкам
$driver->get('https://minebet.com/login');
//ждем пока пользователь настроит vpn
sleep(30);
$driver1->get('https://vodds.com/login');

auth($driver,$driver1);

//ждем пока загрузится
sleep(35);

$submitButton = $driver1->findElement( WebDriverBy::xpath('.//div[@class="nav-tabs"]/span[2]/span'));
$submitButton->click();

while (true) {
    $driver->get('https://minebet.com/strategies')->getPageSource();
    $html = $driver->getPageSource();
    $doc = new DOMDocument();
    $res = @$doc->loadHTML($html);
    if ($res) {
        // Извлекаем из документа все теги - <tr>
        $tags = $doc->getElementsByTagName('tr');
        $i = 0;
        //Перебираем массив полученных элементов тега <tr>
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
        $words[0] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[1]/span');
        $words[1] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[2]');
        $words[2] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[3]/span');
        $words[3] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[12]');
        $num='';
        foreach( $words[0] as $obj ) {
            $num.= $obj->nodeValue;
        }
        $score = '';
        foreach( $words[1] as $obj ) {
            $score.=$obj->nodeValue;
        }
        $game = '';
        $firstCrew = '';
        $secondCrew = '';
        foreach( $words[2] as $obj ) {
            $game.=$obj->nodeValue;
            $firstCrew.= strstr($obj->nodeValue, 'VS', true);
            $secondCrew = strstr($obj->nodeValue, 'VS');
            $secondCrew.= substr($secondCrew,2);
        }
        $sum='';
        foreach( $words[3] as $obj ) {
            $sum.=$obj->nodeValue;
        }
        $fd = fopen("params.txt", 'w+') or die("не удалось создать файл");
        fputs($fd, $arr[0]);
        fclose($fd);
        $url = 'https://api.vk.com/method/messages.send';
        $params = array(
            'user_id' => '21383187',
            'message' => strip_tags('Событие
            _____________________________________ 
            Номер: '. $num.'
             Играют: '. $game.'
             Счет: '. $score.'
             Ставка:'.$sum),
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

        find($driver1,$firstCrew);
        sleep(1);
        $driver1->findElement( WebDriverBy::xpath('.//table[@class="hover-table"]/tbody[2]/tr/td[10]/span'))->click();
        $driver1->findElement(WebDriverBy::name('tradeTabStake'))->sendKeys("10");
        $driver1->findElement( WebDriverBy::xpath('.//div[@class="ui-dialog ui-corner-all ui-widget ui-widget-content ui-front ui-draggable vodds-dialog-active"]/div[2]/div/div[3]/div/div/a'))->click();
    }
    sleep(1);
}