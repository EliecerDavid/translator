<?php

namespace Tests\Unit\Service\Translator;

use App\Services\Translator\Providers\ApiLayerProvider;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiLayerProviderTest extends TestCase
{
    public static function wordsProvider(): array
    {
        return [
            ['es', 'dog', 'perro'],
            ['es', 'cat', 'gato'],
        ];
    }

    #[Test]
    #[DataProvider('wordsProvider')]
    public function it_returns_a_translated_text($locale, $text, $translatedText): void
    {
        $this->setValidResponseForHttpFake(translatedText: $translatedText);

        $apiKey = 'fakeapikey';
        $provider = new ApiLayerProvider($apiKey);
        $this->assertEquals($translatedText, $provider->get(text: $text, locale: $locale));
    }

    #[Test]
    public function it_return_true_if_text_is_supported(): void
    {
        $text = 'cat';
        $this->setValidResponseForHttpFake(translatedText: $text);

        $apiKey = 'fakeapikey';
        $provider = new ApiLayerProvider($apiKey);
        $this->assertTrue($provider->has(text: $text, locale: 'es'));
    }

    #[Test]
    public function it_return_false_if_text_is_unsupported(): void
    {
        $this->setInvalidResponseForHttpFake();

        $apiKey = 'fakeapikey';
        $provider = new ApiLayerProvider($apiKey);
        $this->assertFalse($provider->has(text: 'animal', locale: 'es'));
    }

    #[Test]
    public function it_dont_get_request_more_times_for_the_same_word(): void
    {
        $text = 'dog';
        $translatedText = 'perro';

        $this->setValidResponseForHttpFake(translatedText: $translatedText);

        $apiKey = 'fakeapikey';
        $provider = new ApiLayerProvider($apiKey);
        $this->assertEquals($translatedText, $provider->get(text: $text, locale: 'es'));
        $this->assertEquals($translatedText, $provider->get(text: $text, locale: 'es'));

        Http::assertSentCount(1);
    }

    public function setValidResponseForHttpFake(string $translatedText): void
    {
        Http::fake([
            '*' => Http::response(body: [
                'character_count' => strlen($translatedText),
                'detected_language' => 'en',
                'detected_language_confidence' => 1,
                'translations' => [
                    ['translation' => $translatedText],
                ],
                'word_count' => 1,
            ], status: 200),
        ]);
    }

    public function setInvalidResponseForHttpFake(): void
    {
        Http::fake([
            '*' => Http::response(body: [
                'message' => 'It has an error.',
            ], status: 404),
        ]);
    }
}
