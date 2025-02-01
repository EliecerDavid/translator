<?php

namespace App\Console\Commands;

use App\Services\Translator\Exceptions\UnsupportedProviderException;
use App\Services\Translator\Exceptions\UnsupportedTranslationException;
use App\Services\Translator\Providers\ApiLayerProvider;
use App\Services\Translator\Providers\IlluminateProvider;
use App\Services\Translator\Translator;
use Illuminate\Console\Command;

class TranslateAText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate a text';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $text = $this->ask(question: 'Write the text');
        $locale = $this->ask(question: 'Which language would you like to translate it to?');
        $providerName = $this->choice(question: 'Which provider would you like to use?', choices: ['illuminate', 'apilayer'], default: 'illuminate');

        try {
            $provider = $this->initializeProvider(providerName: $providerName);
            $translator = new Translator(provider: $provider);

            $translatedText = $translator->translate(text: $text, locale: $locale);
            $this->info(string: 'The translation is: ' . $translatedText);
        } catch (UnsupportedProviderException $e) {
            $this->info(string: 'The provider is unsupported');
        } catch (UnsupportedTranslationException $e) {
            $this->info(string: 'The translation is unsupported');
        }
    }

    // TODO: avoid duplicate code to initialize providers
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
