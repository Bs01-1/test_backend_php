<?php

/*
    php 8.1
    Подключение к базе тут /classes/DataBase.php
 */

define('ABSOLUTE_PATH', __DIR__.'/');

$classes_path = ABSOLUTE_PATH.'classes/';
$classes = array_diff(scandir($classes_path),  ['..', '.']);
foreach ($classes as $class) {
    require_once $classes_path.$class;
}

\classes\Core::Init();