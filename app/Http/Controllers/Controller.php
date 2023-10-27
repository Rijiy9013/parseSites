<?php

namespace App\Http\Controllers;

use App\Parsers\EbayParser;
use GuzzleHttp\Client;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\DomCrawler\Crawler;

class Controller extends BaseController
{
    public function check()
    {
        $name = 'URAX-B1R';
        $link = 'https://www.ebay.com/sch/i.html?_from=R40&_nkw=' . $name . '&_sacat=0&_ipg=120';
        $config = [
            "item" => "li.s-item",
            "name" => "div.s-item__title",
            "price" => "div.s-item__details > div.s-item__detail",
            "link" => "div.s-item__info.clearfix a.s-item__link",
        ];
        $ebayParser = new EbayParser($link, $name, $config);
        $ebayParser->startParse();
//        $link = 'https://aliexpress.ru/wholesale?SearchText=ek+1100';
//        $link = 'https://www.avito.ru/all?q=ek1100';
        $content = $this->req($link);
        $this->parseHtml($content, $link);
    }

    private function req($link)
    {
        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->get($link);

        $content = $response->getBody()->getContents();

        return $content;
    }

    protected function getStopWords()
    {
        if (isset($this->config['stop_words'])) {
            return $this->config['stop_words'];
        }
        return [];
    }

    private function parseHtml(string $html, string $link)
    {
        $crawler = new Crawler(null, $link);
        $crawler->addHtmlContent($html, 'UTF-8');
        $ads = $crawler->filter("li.s-item");
        $results = collect();
        foreach ($ads as $ad) {
            $node = new Crawler($ad);
            $result = collect();
            $name = $node->filter("div.s-item__title")->text();
            if (!$this->checkForStopWords($name)) {
                continue;
            }
            $price = $node->filter("div.s-item__details > div.s-item__detail")->text(0);
            if ($name == "Shop on eBay") {
                continue;
            }
            $link = $node->filter("div.s-item__info.clearfix a.s-item__link")->attr("href");
        }
    }

    private function checkForStopWords(string $name): bool
    {
        return true;
    }
}
