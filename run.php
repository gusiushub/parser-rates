<?php
/**
 * Скрипт для автоставок
 */

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\TimeOutException;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;

require 'vendor/autoload.php';

// сюда нужно вписать токен вашего бота
define('TELEGRAM_TOKEN', '688404574:AAEGCIxVP4o2BGMETPAMhZM3WsBRBs3x0qg');
// сюда нужно вписать ваш внутренний айдишник
define('TELEGRAM_CHATID', '489822059');
define('VK_TOKEN','тут токен');
define('VK_USERID','тут токен');




/**
 *
 */
function sendVk()
{
    $url = 'https://api.vk.com/method/messages.send';
    $params = array(
        'user_id' => '21383187',
        'message' => '
                +-------------+
                |   Событие   |
                +-------------+
                Номер: ' . trim($num) . '
                Играют: ' . trim($game) . '
                Счет: ' . trim($score) . '
                Ставка: ' . trim($sum),
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
}

/**
 * @param $text
 */
function sendTelegram($text)
{
    $ch = curl_init();
    curl_setopt_array(
        $ch,
        array(
            CURLOPT_URL => 'https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => array(
                'chat_id' => TELEGRAM_CHATID,
                'text' => $text,
            ),
        )
    );
    curl_exec($ch);
}
sendTelegram('aasddasdas');
function getElementsByClass(&$parentNode, $tagName, $className) {
    $nodes=array();

    $childNodeList = $parentNode->getElementsByTagName($tagName);
    for ($i = 0; $i < $childNodeList->length; $i++) {
        $temp = $childNodeList->item($i);
        if (stripos($temp->getAttribute('class'), $className) !== false) {
            $nodes[]=$temp;
        }
    }

    return $nodes;
}

/**
 * Вывод ошибок
 */
function debug()
{
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);
}

/**
 * @param $var
 */
function dump($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    exit;
}

/**
 * Ожидание появление элемента N секунд
 * Если находит раньше, то возвращает true, иначе исключение
 *
 * @param $driver
 * @param $WebDriverBy
 * @param int $timeout
 * @param int $interval
 * @param string $message
 * @return bool
 * @throws TimeOutException
 * @throws WebDriverException
 */
function waitForElementVisible(&$driver, $WebDriverBy, $timeout = 10, $interval = 250, $message = 'TIME_OUT')
{
    $end = microtime(true) + $timeout;
    $last_exception = null;
    while ($end > microtime(true)) {
        try {
            $driver->wait($timeout)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated($WebDriverBy)
            );
            return true;
        } catch (WebDriverException $e) {
            $last_exception = $e;
        }
        usleep($interval * 1000);
    }
    if ($last_exception) {
        throw $last_exception;
    }
    throw new TimeOutException($message);
}

/**
 * авторизация в профиле
 * @param $driver
 * @param $driver1
 */
function auth($driver, $driver1)
{
    $config = require 'config.php';
    $driver ->findElement(WebDriverBy::name('LoginForm[username]'))->sendKeys(trim($config['loginMinebet']));
    $driver ->findElement(WebDriverBy::name('LoginForm[password]'))->sendKeys(trim($config['passwordMinebet']));
    $driver ->findElement(WebDriverBy::tagName('button'))->click();
    $driver1->findElement(WebDriverBy::name('username'))->sendKeys(trim($config['loginVodds']));
    $driver1->findElement(WebDriverBy::name('accessToken'))->sendKeys(trim($config['passwordVodds']));
    $html = $driver1->getPageSource();
    $doc = new DOMDocument();
    $res = @$doc->loadHTML($html);
    if ($res) {
        // Извлекаем из документа все теги - <tr>
        $tags = $doc->getElementsByTagName('button');
        if ($tags['length'] != null) {
            $driver1->findElement(WebDriverBy::tagName('button'))->click();
        }
    }

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

/**
 * Проверка на доступность элемента
 * Возвращает true, если элемент доступен. Иначе false
 *
 * @param $driver
 * @param $WebDriverBy
 * @return bool
 */
function isElementPresent(&$driver, $WebDriverBy)
{
    try {
        $driver->manage()->timeouts()->implicitlyWait(0);
        $element = $driver->findElement($WebDriverBy);
        if ($element->isDisplayed()) {
            return true;
        } else {
            return false;
        }
    } catch (WebDriverException $ex) {
        $msg = $ex->getMessage();
        if (preg_match("#Unable to locate element#is", $msg)) {
            #echo "Notice | __isElementPresent || Element not found | " . $WebDriverBy;
        } else {
            echo "Notice | isElementPresent || Exception || [\n $msg \n]\n";
        }
        return false;
    } finally {
        $driver->manage()->timeouts()->implicitlyWait(30);
    }
}

exec('start '.$_SERVER['DOCUMENT_ROOT'].'\chromedriver.exe');
debug();
set_time_limit(0);
ignore_user_abort(true);
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
$driver1 = RemoteWebDriver::create($wd_host, $desired_capabilities, 5000, 30000);
$driver  = RemoteWebDriver::create($wd_host, $desired_capabilities, 5000, 30000);
//переходы по ссылкам
$driver->get('https://minebet.com/login');
//ЕСЛИ ИСПОЛЬЗУЕТСЯ VPN РАСШИРЕНИЕ ДЛЯ БРАУЗЕРА ТО ждем пока пользователь настроит vpn
//sleep(15);
$driver1->get('https://vodds.com/login');
//авторизация
sleep(2);
auth($driver,$driver1);
//УВЕЛИЧИТЬ ЕСЛИ ИСПОЛЬЗУЕТСЯ VPN РАСШИРЕНИЕ ДЛЯ БРАУЗЕРА(~на 5 секунд)
sleep(35);
$driver1->findElement( WebDriverBy::xpath('.//div[@class="nav-tabs"]/span[2]/span'))->click();
$timeDo = fopen("time.txt", 'w+') or die("не удалось создать файл");
$min = time() / 60 ;
fputs($timeDo, $min);
while (true) {
    try {
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
        $timeStart = file('time.txt');
        $min = time() / 60 ;
        $doTime = $min - $timeStart[0];
        $id = file('params.txt');
        //смоделировать действие на странице
        if ($doTime>15) {
            $timeDo = fopen("time.txt", 'w+') or die("не удалось создать файл");
            fputs($timeDo, $min);
            $driver1->findElement( WebDriverBy::xpath('.//div[@class="nav-tabs"]/span[1]/span'))->click();
            sleep(1);
            $driver1->findElement( WebDriverBy::xpath('.//div[@class="nav-tabs"]/span[2]/span'))->click();
        }
        if ($arr[0] != $id[0]) {
            @$doc->loadHTML($html);
            $xpath = new DOMXPath($doc);
            $nodes = $xpath->evaluate('//tr[@data-id="' . $arr[0] . '"]');
            $words[0] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[1]/span');
            $words[1] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[2]');
            $words[2] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[3]/span');
            $words[3] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[12]');
            $words[4] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[6]');
            $words[5] = $xpath->query('.//tr[@data-id="' . $arr[0] . '"]/td[7]');
            $num = '';
            foreach ($words[0] as $obj) {
                $num .= $obj->nodeValue;
            }
            $score = '';
            foreach ($words[1] as $obj) {
                $score .= $obj->nodeValue;
            }
            $game = '';
            $firstCrew = '';
            $secondCrew = '';
            foreach ($words[2] as $obj) {
                $game .= $obj->nodeValue;
                $firstCrew .= strstr($obj->nodeValue, 'VS', true);
                $secondCrew = strstr($obj->nodeValue, 'VS');
                $secondCrew .= substr($secondCrew, 2);
            }
            $sum = '';
            foreach ($words[3] as $obj) {
                $sum .= $obj->nodeValue;
            }
            $sum = trim($sum);
            $sum = substr($sum, 0, -8);
            $on = '';
            foreach ($words[4] as $obj) {
                $on .= $obj->nodeValue;
            }
            //значение на minebet
            $odd = '';
            foreach ($words[5] as $obj) {
                $odd .= $obj->nodeValue;
            }
            //under\over и т.д.
            $on = trim($on);
            $fd = fopen("params.txt", 'w+') or die("не удалось создать файл");
            fputs($fd, $arr[0]);
            fclose($fd);
            $url = 'https://api.vk.com/method/messages.send';
            $params = array(
                'user_id' => '21383187',
                'message' => '
                +-------------+
                |   Событие   |
                +-------------+
                Номер: ' . trim($num) . '
                Играют: ' . trim($game) . '
                Счет: ' . trim($score) . '
                Ставка: ' . trim($sum),
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

//            $html = $driver1->getPageSource();
//                //$html = $driver1->get('https://vodds.com/member/dashboard')->getPageSource();
//                @$doc->loadHTML($html);
//                $xpath = new DOMXPath($doc);
//                $queryKrest = $xpath->query('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i')->length;
//                if($queryKrest==1){
//                    $driver1->findElement(WebDriverBy::xpath('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i'))->click();
//                }
                sleep(2);
            find($driver1, $firstCrew);
            sleep(1);
//            $html = $driver1->getPageSource();
//            //$html = $driver1->get('https://vodds.com/member/dashboard')->getPageSource();
//            @$doc->loadHTML($html);
//            $xpath = new DOMXPath($doc);
//            $ou = $xpath->query('.//table[@class="hover-table"]/tbody[2]/tr/td[1]/span/span/span[2]/i')->length;
//            if ($ou==1) {//ng-scope
                $driver1->findElement(WebDriverBy::cssSelector('i.fa.fa-plus'))->click();
                if ($on == 'Over') {
                    $html = $driver1->getPageSource();
                    //$html = $driver1->get('https://vodds.com/member/dashboard')->getPageSource();
                    @$doc->loadHTML($html);
                    $xpath = new DOMXPath($doc);
                    $ou = $xpath->query('.//table[@class="hover-table"]/tbody[2]/tr/td[9]/span');
                    $total = '';
                    if ($ou != null) {
                        foreach ($ou as $obj) {
                            $total .= $obj->nodeValue;
                        }
                        $total = trim($total);
                        $total = str_replace(chr(13), '', $total);
                        $total = str_replace(chr(10), '', $total);
                        $total = str_replace(' ', '', $total);
                        $total = explode("\t", $total);
                        $count = count($total);
                        $k = 0;
                        for ($j = 0; $j < $count; $j++) {
                            if ($total[$j] != null or $total[$j] != '') {
                                $k++;
                            }
                            $odd = trim($odd);
                            if ($total[$j] == $odd) {
                                $textUnder = $driver1->findElement(WebDriverBy::xpath('.//table[@class="hover-table"]/tbody[2]/tr[' . $k . ']/td[10]/span'))->getText();
                                if ($textUnder != "" and $textUnder != null) {
                                    $driver1->findElement(WebDriverBy::xpath('.//table[@class="hover-table"]/tbody[2]/tr[' . $k . ']/td[10]/span'))->click();
                                    //$driver1->findElement(WebDriverBy::xpath('.//table[@class="hover-table"]/tbody[2]/tr/td[11]/span'))->click();
                                } else {
                                    $driver1->findElement(WebDriverBy::xpath('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i'))->click();
                                }
                            }
                        }
                    }
                }
                if ($on == 'Under') {
                    $html = $driver1->getPageSource();
                    @$doc->loadHTML($html);
                    $xpath = new DOMXPath($doc);
                    $ou = $xpath->query('.//table[@class="hover-table"]/tbody[2]/tr/td[9]/span');
                    $total = '';
                    if ($ou != null) {
                        foreach ($ou as $obj) {
                            $total .= $obj->nodeValue;
                        }
                        $total = trim($total);
                        $total = str_replace(chr(13), '', $total);
                        $total = str_replace(chr(10), '', $total);
                        $total = str_replace(' ', '', $total);
                        $total = explode("\t", $total);
                        $count = count($total);
                        $k = 0;
                        for ($j = 0; $j < $count; $j++) {
                            if ($total[$j] != null or $total[$j] != '') {
                                $k++;
                            }
                            $odd = trim($odd);
                            if ($total[$j] == $odd) {
                                $textUnder = $driver1->findElement(WebDriverBy::xpath('.//table[@class="hover-table"]/tbody[2]/tr[' . $k . ']/td[11]/span'))->getText();
                                if ($textUnder != "" and $textUnder != null) {
                                    $driver1->findElement(WebDriverBy::xpath('.//table[@class="hover-table"]/tbody[2]/tr[' . $k . ']/td[11]/span'))->click();
                                    //$driver1->findElement(WebDriverBy::xpath('.//table[@class="hover-table"]/tbody[2]/tr/td[11]/span'))->click();
                                } else {
                                    $driver1->findElement(WebDriverBy::xpath('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i'))->click();
                                }
                            }
                        }
                    }
                    //$textUnder = $driver1->findElement(WebDriverBy::xpath('.//table[@class="hover-table"]/tbody[2]/tr/td[11]/span'))->getText();
                }
                sleep(2);
                $driver1->findElement(WebDriverBy::name('tradeTabStake'))->sendKeys($sum);
                //$driver1->findElement(WebDriverBy::cssSelector('button.ui-button.ui-corner-all.ui-widget.ui-button-icon-only.ui-dialog-titlebar-close.ng-scope'))->click();
                sleep(4);
                //var_dump($driver1->findElement(WebDriverBy::cssSelector('input.form-control.vodds-input-text'))->getText());exit;
                $driver1->findElement(WebDriverBy::xpath('.//div[@class="row"]/div/div/a'))->click();
                sleep(2);
                $driver1->findElement(WebDriverBy::cssSelector('button.btn.vodds-btn.vodds-blue-btn.pull-right'))->click();
                sleep(2);
                $driver1->findElement(WebDriverBy::cssSelector('button.btn.vodds-btn.vodds-blue-btn.pull-right'))->click();
            $driver1->get('https://vodds.com/member/dashboard');
            sleep(35);
            $driver1->findElement( WebDriverBy::xpath('.//div[@class="nav-tabs"]/span[2]/span'))->click();

//                $html = $driver1->getPageSource();
                //$html = $driver1->get('https://vodds.com/member/dashboard')->getPageSource();
//                @$doc->loadHTML($html);
//                $xpath = new DOMXPath($doc);
//                $queryKrest = $xpath->query('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i')->length;
                //var_dump($queryKrest);exit;
//            $krest = '';
//            foreach ($queryKrest as $obj) {
//                $krest .= $obj->nodeValue;
//            }
            //var_dump($krest);exit;
//                if ($queryKrest == 1) {
//                    $driver1->findElement(WebDriverBy::xpath('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i'))->click();
//                    $url = 'https://api.vk.com/method/messages.send';
//                    $params = array(
//                        'user_id' => '21383187',
//                        'message' => 'Ставка сделана без ошибок!',
//                        'access_token' => 'ce1200db50d7461d24d1b0b414870ba85d718373b338ff946d82d69cf23bd12f8a346b0894945034442a7',
//                        'v' => '5.37',
//                    );
//                    $result = file_get_contents($url, false, stream_context_create(array(
//                        'http' => array(
//                            'method' => 'POST',
//                            'header' => 'Content-type: application/x-www-form-urlencoded',
//                            'content' => http_build_query($params)
//                        )
//                    )));
//                } else {
//                    $driver1->get('https://vodds.com/member/dashboard');
//                    sleep(30);
//                    $driver1->findElement(WebDriverBy::xpath('.//div[@class="nav-tabs"]/span[2]/span'))->click();
//                    $url = 'https://api.vk.com/method/messages.send';
//                    $params = array(
//                        'user_id' => '21383187',
//                        'message' => 'Программа не нажала крестик!',
//                        'access_token' => 'ce1200db50d7461d24d1b0b414870ba85d718373b338ff946d82d69cf23bd12f8a346b0894945034442a7',
//                        'v' => '5.37',
//                    );
//                    $result = file_get_contents($url, false, stream_context_create(array(
//                        'http' => array(
//                            'method' => 'POST',
//                            'header' => 'Content-type: application/x-www-form-urlencoded',
//                            'content' => http_build_query($params)
//                        )
//                    )));
//                }
                //$driver1->findElement(WebDriverBy::xpath('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i'))->click();
                //$driver1->findElement(WebDriverBy::cssSelector('i.fa.fa-times.vodds-pointer.vodds-multi-tag-reset'))->click();

            }
//            else{
//            exit('sdfsdfsdfsdfsdfsdfsdffsdfsdf');
//                $html = $driver1->getPageSource();
//                //$html = $driver1->get('https://vodds.com/member/dashboard')->getPageSource();
//                @$doc->loadHTML($html);
//                $xpath = new DOMXPath($doc);
//                $queryKrest = $xpath->query('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i')->length;
//                if($queryKrest==1){
//                    $driver1->findElement(WebDriverBy::xpath('.//div[@id="voddsOddPanel"]/div[1]/div[2]/div/span/i'))->click();
//                }
//            }
        //}
        sleep(5);
    }catch (WebDriverException $ex){
        echo'ОШИБКА! <br> ERROR...';
        $msg = $ex->getMessage();
        echo "_Exception || msg=[[\n$msg\n]]";
    }
}