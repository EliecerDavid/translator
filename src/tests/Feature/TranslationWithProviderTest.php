<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
        Http::fake([
            '*' => Http::response(body: [
                'character_count' => strlen('perro'),
                'detected_language' => 'en',
                'detected_language_confidence' => 1,
                'translations' => [
                    ['translation' => 'perro'],
                ],
                'word_count' => 1,
            ], status: 200),
        ]);

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
