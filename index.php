<?php

include __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.inc.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'funcoes.php';


$url = (!empty(filter_input(INPUT_GET, 'param', FILTER_DEFAULT)) ? filter_input(INPUT_GET, 'param', FILTER_DEFAULT) : 'home');
$url = array_filter(explode('/', $url));

if($url[0] === 'remove'){
    clear();
}

var_dump($url[0]);