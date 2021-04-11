<?php
require_once '../vendor/autoload.php';
use SL\ShortLinkSQLite;

if (!empty($_POST['url'])) {
    $url = $_POST['url'];

    $link = ShortLinkSQLite::addLink($url);
    echo $link->shortUrl;
}else {
   return null;
}
