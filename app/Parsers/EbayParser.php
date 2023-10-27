<?php

namespace App\Parsers;

use App\BaseClasses\BaseParser;

class EbayParser extends BaseParser
{
    public function startParse()
    {
//        $content = $this->fetchHtml();
        $results = $this->parseHtml();
        dd($results);
    }
}
