<?php

namespace App\Http\Controllers;

use App\Services\Translator\Exceptions\UnsupportedProviderException;
use App\Services\Translator\Providers\ApiLayerProvider;
use App\Services\Translator\Providers\IlluminateProvider;
use App\Services\Translator\Translator;
use Illuminate\Http\Request;

class TranslationWithProviderController extends Controller
{
    public function __invoke(Request $request, string $providerName)
    {
        $request->validate([
            'locale' => 'required|string',
            'text' => 'required|string',
        ]);

        $locale = $request->input('locale');
        $text = $request->input('text');

        $provider = $this->initializeProvider(providerName: $providerName);
        $translator = new Translator(provider: $provider);

        return [
            'data' => [
                'translated_text' => $translator->translate(text: $text, locale: $locale),
            ],
        ];
    }

    protected function initializeProvider(string $providerName)
    {
        if ($providerName == 'illuminate') {
            return $this->initializeIlluminateProvider();
        } elseif ($providerName == 'apilayer') {
            return $this->initializeApiLayerProvider();
        } else {
            throw new UnsupportedProviderException();
        }
    }

    protected function initializeIlluminateProvider(): IlluminateProvider
    {
        $illuminateTranslator = app('translator');
        return new IlluminateProvider(translator: $illuminateTranslator);
    }

    protected function initializeApiLayerProvider(): ApiLayerProvider
    {
        $apiKey = env('TRANSLATOR_APILAYER_APIKEY');
        return new ApiLayerProvider(apiKey: $apiKey);
    }
}
