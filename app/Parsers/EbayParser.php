<?php

namespace App\Parsers;

use App\BaseClasses\BaseParser;
use App\Jobs\EbayParseJob;

class EbayParser extends BaseParser
{
    public function startParse(bool $withJob = true)
    {
        $results = $this->parseHtml();
//        $this->saveResults($results);
    }
}
