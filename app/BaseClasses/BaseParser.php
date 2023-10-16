<?php

namespace App\BaseClasses;

use App\Models\ParserSettings;
use App\Models\Platform;
use GuzzleHttp\Client;

class BaseParser
{
    protected string $url, $whatToFind, $platform;
    protected array $config, $headers = [

    ];

    public function __construct(string $url, string $whatToFind, array $config, string $platform = null, int $platformId = null)
    {
        if (empty($url) || empty($whatToFind)) {
            throw new \InvalidArgumentException("URL and whatToFind cannot be empty.");
        }

        if (!is_array($config)) {
            throw new \InvalidArgumentException("Invalid config parameters. Config not an array");
        }
        if (empty($config['name']) || empty($config['price']) || empty($config['link'])) {
            throw new \InvalidArgumentException("Invalid config parameters. Name, price and link are required");
        }
        $this->url = $url;
        $this->whatToFind = $whatToFind;
        $this->platform = $platform;
        if ($platformId) {
            $this->config = $this->getConfigByPlatformId($platformId);
        } else {
            $this->config = $config;
        }
    }

    protected function getConfigByPlatformId(int $id)
    {
        $config = ParserSettings::where('platform_id', $id)->first();
        if (!$config){
            throw new \InvalidArgumentException("Invalid config parameters. There is no settings for this platform");
        }
        return $config;
    }

    protected function getContent(string $link): string
    {
        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->get($link);

        $content = $response->getBody()->getContents();

        return $content;
    }

    protected function parseHtml(string $link)
    {
        $content = $this->getContent($link);
    }

    protected function saveResults(array $results)
    {

    }

}
