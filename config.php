<?php
/**
 * Файл для настройки скрипта
 */

$value = file('profile.php');
return array(
    'loginMinebet'    => 'testpro', // Логин на сайте minebet
    'passwordMinebet' => 'testpro', // Пароль на сайте minebet
    'loginVodds'      => 'demoeur0381', // Логин на сайте vodds
    'passwordVodds'   => 'Qw5431769er!', // Пароль на сайте vodds
    'idVk'            => '21383187', // id пользователя на сайте vk которому отправлять уведомление
    'tokenVk'         => 'ce1200db50d7461d24d1b0b414870ba85d718373b338ff946d82d69cf23bd12f8a346b0894945034442a7', // token вашего аккаунта на сайте vk
    'message'         => '', // Содержание уведомления
);