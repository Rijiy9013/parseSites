<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\DomCrawler\Crawler;

class Controller extends BaseController
{
    public function check()
    {
        $name = 'iphone 12';
        $link = 'https://www.ebay.com/sch/i.html?_from=R40&_nkw=' . $name . '&_sacat=0&_ipg=120';
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

    private function parseHtml(string $html, string $link)
    {
        $crawler = new Crawler(null, $link);
        $crawler->addHtmlContent($html, 'UTF-8');
        $ads = $crawler->filter('li.s-item');
        dd($ads);
    }

}
