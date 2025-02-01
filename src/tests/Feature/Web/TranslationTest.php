<?php

namespace Tests\Feature\Web;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Helpers\ApiLayerHelper as TestHelper;

class TranslationTest extends TestCase
{
    #[Test]
    public function it_returns_a_successful_response(): void
    {
        $body = [
            'locale' => 'es',
            'text' => 'animal',
        ];

        $response = $this->post('/translation', $body);
        $response->assertStatus(200);
    }

    #[Test]
    public function it_translates_a_text_with_config_illuminate(): void
    {
        Config::set(key: 'translator.default', value: 'illuminate');

        $body = [
            'locale' => 'es',
            'text' => 'cat',
        ];

        $response = $this->post('/translation', $body);
        $response->assertJson([
            'data' => [
                'translated_text' => 'gato',
            ],
        ]);
    }

    #[Test]
    public function it_translates_a_text_with_config_apilayer(): void
    {
        TestHelper::setUpHttpFakeWithAValidResponse('gato');

        Config::set(key: 'translator.default', value: 'apilayer');
        Config::set(key: 'translator.providers.apilayer.apikey', value: 'apilayer');

        $body = [
            'locale' => 'es',
            'text' => 'cat',
        ];

        $response = $this->post('/translation', $body);
        $response->assertJson([
            'data' => [
                'translated_text' => 'gato',
            ],
        ]);
    }

    #[Test]
    public function it_translates_a_text_with_config_unsupported_provider(): void
    {
        Config::set(key: 'translator.default', value: 'apilayer');
        Config::set(key: 'translator.providers.apilayer.provider', value: 'unsupported');

        $body = [
            'locale' => 'es',
            'text' => 'cat',
        ];

        $response = $this->post('/translation', $body);
        $response->assertJson([
            'data' => [
                'message' => 'unsupported provider',
            ],
        ]);
    }

    #[Test]
    public function it_returns_unsupported_translation(): void
    {
        $body = [
            'locale' => 'ficcious locale',
            'text' => 'animal',
        ];

        $response = $this->post('/translation', $body);
        $response->assertJson([
            'data' => [
                'message' => 'unsupported translation',
            ],
        ]);
    }
}
