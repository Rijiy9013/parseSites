<?php

namespace App\BaseClasses;

use App\Models\ParserSettings;
use App\Models\Result;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseParser
{
    protected int $platformId;
    protected string $url, $whatToFind, $platform;
    protected array $config, $headers = [

    ];

    public function __construct(string $url, string $whatToFind, array $config, string $platform = "", int $platformId = null)
    {
        if (empty($url) || empty($whatToFind)) {
            throw new \InvalidArgumentException("URL and whatToFind cannot be empty.");
        }

        if (!is_array($config)) {
            throw new \InvalidArgumentException("Invalid config parameters. Config not an array");
        }
        if (empty($config['name']) || empty($config['price']) || empty($config['link']) || empty($config['item'])) {
            throw new \InvalidArgumentException("Invalid config parameters. Name, price, item and link are required");
        }
        $this->url = $url;
        $this->whatToFind = $whatToFind;
        $this->platform = $platform;
        if ($platformId) {
            $this->config = $this->getConfigByPlatformId($platformId);
            $this->platformId = $platformId;
        } else {
            $this->config = $config;
        }
    }

    protected function getConfigByPlatformId(int $id): array
    {
        try {
            $config = ParserSettings::where('platform_id', $id)->first();
            if (!$config) {
                throw new \InvalidArgumentException("Invalid config parameters. There is no settings for this platform");
            }
            return $config;
        } catch (\Exception $e) {
            abort(500);
        }
    }

    protected function fetchHtml(): string
    {
        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->get($this->url);

        return $response->getBody()->getContents();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function parseHtml(): Collection
    {
        $content = $this->fetchHtml();
        $crawler = new Crawler(null, $this->url);
        $crawler->addHtmlContent($content, 'UTF-8');
        $ads = $crawler->filter($this->config["item"]);
        $results = collect();
        $stopWords = $this->getStopWords();
        foreach ($ads as $ad) {
            $node = new Crawler($ad);
            $result = collect();
            $name = $node->filter($this->config["name"])->text();
            if (!$this->checkForStopWords($name, $stopWords)) {
                continue;
            }
            $result->add($name);
            $price = $node->filter($this->config["price"])->text(0);
            $result->add($price);
            $link = $node->filter($this->config["link"])->text("");
            $result->add($link);
            $results->add($result);
        }
        return $results;
    }

    protected function getStopWords()
    {
        if (isset($this->config['stop_words'])) {
            return $this->config['stop_words'];
        }
        return [];
    }

    protected function checkForStopWords(string $name, array $stopWords): bool
    {
        return true;
    }

    protected function saveResults(Collection $results): void
    {
        foreach ($results as $result) {
            try {
                Result::create([
                    "name" => $result->name,
                    "price" => $result->price,
                    "link" => $result->link,
                ]);
            } catch (\Exception $e) {

            }
        }
    }

}
