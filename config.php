<?php
/**
 * Файл для настройки скрипта
 */

$value = file('profile.php');
return array(
    'loginMinebet'    => substr($value[2],1), // Логин на сайте minebet
    'passwordMinebet' => substr($value[3],1), // Пароль на сайте minebet
    'loginVodds'      => substr($value[4],1), // Логин на сайте vodds
    'passwordVodds'   => substr($value[5],1), // Пароль на сайте vodds
    'idVk'            => substr($value[6],1), // id пользователя на сайте vk которому отправлять уведомление
    'tokenVk'         => substr($value[7],1), // token вашего аккаунта на сайте vk
    'message'         => '', // Содержание уведомления
);