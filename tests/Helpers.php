<?php

function normaliseXml(string $xml): string
{
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = false;
    @$dom->loadXML($xml);

    return trim($dom->saveXML() ?: $xml);
}
