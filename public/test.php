<?php

use SL\ShortLinkSQLite;

require_once '../vendor/autoload.php';

$url = 'https://Andre_Lav@bitbucket.org/sevenpowerx/paradam.git';


$link = ShortLinkSQLite::addLink($url);

//$link = \SL\Writer\ShortLinkWriter::getInstanceByID(1);
//$link = \SL\Writer\ShortLinkWriter::getUrlByShortLink('_erms_awvo/6071a744cdc76');
//$link = \SL\Writer\ShortLinkWriter::getShortLinksByUrl($url);

//$link = ShortLinkSQLite::getIdUrl($url);


echo '<pre>';
print_r($link);
echo '</pre>';
die();
