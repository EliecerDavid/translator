<?php

namespace App\Services\Translator\Providers;

use App\Services\Translator\Contracts\Provider;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Http;

class ApiLayerProvider implements Provider
{
    protected string $apiUrl = 'https://api.apilayer.com/language_translation/translate';
    protected string $apiKey;
    protected HttpClient $httpClient;

    protected array $loadedTranslations = [];

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function has(string $text, string $locale): bool
    {
        $translateText = $this->get(text: $text, locale: $locale);

        if (!$translateText) {
            return false;
        }

        return true;
    }

    public function get(string $text, string $locale): string
    {
        if (isset($this->loadedTranslations[$text])) {
            return $this->loadedTranslations[$text];
        }

        $params = [
            'source' => 'en',
            'target' => $locale,
        ];

        // todo: inject http client like a dependency
        $response = Http::withHeader(name: 'apikey', value: $this->apiKey)
            ->withQueryParameters($params)
            ->withBody($text)
            ->post($this->apiUrl);

        $translateText = $response->json('translations.0.translation');

        if (is_null($translateText)) {
            return '';
        }

        $this->loadedTranslations[$text] = $translateText;
        return $translateText;
    }
}
