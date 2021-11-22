<?php

defined('VG_ACCESS') or die('Access denied'); //если константа неопределена, то выходим

const MS_MODE           = false;

const TEMPLATE          = 'templates/default/'; //шаблоны пользовательской части нашкго сайта
const ADMIN_TEMPLATE    = 'core/admin/view/'; //константа административных шаблонов
const UPLOAD_DIR        = 'userfiles/';

const COOKIE_VERSION    = '1.0.0'; // константа безопасности
const CRYPT_KEY         = 'jXn2r5u8x/A?D(G-QfTjWnZr4u7x!A%D+MbQeThWmZq4t7w!D(G+KbPeShVmYq3t/A?D*G-KaPdSgVkYu7x!A%D*F-JaNdRgZq4t7w!z%C*F)J@NhVmYq3t6w9z$C&E)'; //константа шифрования
const COOKIE_TIME       = '60'; //время жизни куки администратора
const BLOCK_TIME        = 3; //время блокировки пользователя, при вводе неправильного пароля
const QTI               = 8; //константа постраничной навигации
const QTI_LINKS         = 3; //количество ссылок левее и правее активной

const ADMIN_CSS_JS      = [
    'styles' => ['css/main.css'],
    'scripts'=> ['js/frameworkfunctions.js', 'js/scripts.js', 'js/tinymce/tinymce.min.js',
        'js/tinymce/tinymce.init.js']
];
const USER_CSS_JS      = [
    'styles' => [],
    'scripts'=> []
];

use core\base\exceptions\RouteException;

function autoloadMainClasses($class_name)
{
    $class_name = str_replace('\\', '/', $class_name);
    if (!@include_once $class_name . '.php')
    {
        throw new RouteException('Неверное имя файла для подключения - '.$class_name);
    }
}

spl_autoload_register('autoloadMainClasses');