<?php

namespace App\Providers;

use App\Services\Translator\Exceptions\UnsupportedProviderException;
use App\Services\Translator\Providers\ApiLayerProvider;
use App\Services\Translator\Providers\IlluminateProvider;
use App\Services\Translator\Translator;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TranslatorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Translator::class, function (Application $app) {
            $providerName = config('translator.default');
            $providerConfig = config('translator.providers.' . $providerName);

            if ($providerConfig['provider'] == 'illuminate') {
                $provider = $this->initializeIlluminateProvider();
            } elseif ($providerConfig['provider'] == 'apilayer') {
                $provider = $this->initializeApiLayerProvider($providerConfig);
            } else {
                throw new UnsupportedProviderException();
            }

            return new Translator($provider);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    protected function initializeIlluminateProvider(): IlluminateProvider
    {
        $illuminateTranslator = app('translator');
        return new IlluminateProvider($illuminateTranslator);
    }

    protected function initializeApiLayerProvider($config): ApiLayerProvider
    {
        $httpClient = new HttpClient();
        return new ApiLayerProvider($httpClient, $config['apikey']);
    }
}
