<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Helpers\ApiLayerHelper as TestHelper;

class TranslationWithProviderTest extends TestCase
{
    #[Test]
    public function it_translate_a_text_with_illuminate_provider(): void
    {
        $body = [
            'locale' => 'es',
            'text' => 'animal',
        ];

        $response = $this->post('/translation/provider/illuminate', $body);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'translated_text' => 'animal',
                ],
            ]);
    }

    #[Test]
    public function it_translate_a_text_with_apilayer_provider(): void
    {
        TestHelper::setUpHttpFakeWithAValidResponse(translatedText: 'perro');

        $body = [
            'locale' => 'es',
            'text' => 'dog',
        ];

        $response = $this->post('/translation/provider/apilayer', $body);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'translated_text' => 'perro',
                ],
            ]);
    }

    #[Test]
    public function it_return_error_404_with_an_unsupported_provider(): void
    {
        $body = [
            'locale' => 'es',
            'text' => 'animal',
        ];

        $response = $this->post('/translation/provider/unsupported', $body);
        $response->assertStatus(404);
    }
}
