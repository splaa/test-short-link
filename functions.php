<?php

require_once 'vendor/autoload.php';

use JetBrains\PhpStorm\NoReturn;
use SL\Link;

function getRequestPath(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    return ltrim(str_replace('/', '', $path), '/');
}


function getUrl($shortLink){
    $url = \SL\ShortLinkSQLite::getUrlByShortLink($shortLink);
    if (!empty($url)) {
        $url = trim($url);
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            header("Location: {$url}");
            exit();
        }
    }
}






#[NoReturn] function dd($obj, $message = null)
{
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
    die($message);
}

function dump($obj)
{
    echo '<pre>';
    print_r($obj);
    echo '</pre>';
}