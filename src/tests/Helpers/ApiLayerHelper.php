<?php

namespace Tests\Helpers;

use Illuminate\Support\Facades\Http;

class ApiLayerHelper
{
    public static function setUpHttpFakeWithAValidResponse(string $translatedText): void
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

    public static function setUpHttpFakeWithAnInvalidResponse(int $statusCode): void
    {
        Http::fake([
            '*' => Http::response(body: [
                'message' => 'It has an error.',
            ], status: $statusCode),
        ]);
    }
}

