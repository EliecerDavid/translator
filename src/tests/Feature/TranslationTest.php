<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

    public static function wordsProvider(): array
    {
        return [
            ['es', 'animal', 'animal'],
            ['es', 'dog', 'perro'],
            ['es', 'cat', 'gato'],

            ['fr', 'animal', 'animal'],
            ['fr', 'dog', 'chien'],
            ['fr', 'cat', 'chat'],
        ];
    }

    #[Test]
    #[DataProvider('wordsProvider')]
    public function it_translates_a_text(string $locale, string $text, string $translatedText): void
    {
        $body = [
            'locale' => $locale,
            'text' => $text,
        ];

        $response = $this->post('/translation', $body);
        $response->assertJson([
            'data' => [
                'translated_text' => $translatedText,
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
