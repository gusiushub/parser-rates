<?php
require 'vendor/autoload.php';
//require 'simplehtmldom/simple_html_dom.php';
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

set_time_limit(0);
$wd_host = 'http://localhost:9515';
$desired_capabilities = DesiredCapabilities::phantomjs();
$desired_capabilities->setCapability('acceptSslCerts', false);
$chromeOptions = new ChromeOptions();
$arguments = ["--user-agent=Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)"];
$chromeOptions->addArguments($arguments);
$chromeOptions->addExtensions(['Selenium/Block-image_v1.1.crx']);
$desired_capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
$driver = RemoteWebDriver::create($wd_host, $desired_capabilities, 5000, 30000);
$driver->get('https://minebet.com/login');
$driver->findElement(WebDriverBy::name('LoginForm[username]'))->sendKeys("testpro");
$driver->findElement(WebDriverBy::name('LoginForm[password]'))->sendKeys("testpro");
$driver->findElement( WebDriverBy::tagName('button'))->click();
sleep(2);
while (true) {
    $driver->get('https://minebet.com/strategies')->getPageSource();
    $html = $driver->getPageSource();
//$count = substr_count($html,'<tr data-id=">');
    $doc = new DOMDocument();
    $res = @$doc->loadHTML($html);
    /*Если файл является корректным, то оператор вернет
    нам TRUE и тогда мы сможем манипулировать его DOM моделью.*/
    //echo '<br>';

    //echo '<br>';
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
        echo $doc->saveXML($nodes->item(0));
        $fd = fopen("params.txt", 'w+') or die("не удалось создать файл");
        fputs($fd, $arr[0]);
        fclose($fd);

        $url = 'https://api.vk.com/method/messages.send';
        $params = array(
            'user_id' => '',
            'message' => strip_tags($doc->saveXML($nodes->item(0))),
            'access_token' => '',
            'v' => '5.37',
        );
        $result = file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));

        print_r(strip_tags($doc->saveXML($nodes->item(0))));
    }
}


