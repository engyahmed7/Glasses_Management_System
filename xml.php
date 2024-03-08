<?php
$items = ['banana', 'apple', 'milk', 'cheese'];

$xml = new SimpleXMLElement('<list/>');

foreach ($items as $item) {
    $xml->addChild('item', $item);
}

header('Content-Type: text/xml');
echo $xml->asXML();
exit();
