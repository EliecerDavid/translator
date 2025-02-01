<?php

namespace Tests\Unit\Services\Translator;

use App\Services\Translator\Contracts\Provider;
use App\Services\Translator\Exceptions\UnsupportedTranslationException;
use App\Services\Translator\Translator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
    protected $stubProvider;

    protected function setUp(): void
    {
        $provider = $this->createStub(Provider::class);
        $provider->method('get')
            ->willReturnMap([
                ['dog', 'es', 'perro'],
                ['cat', 'es', 'gato'],
            ]);

        $provider->method('has')
            ->willReturnMap([
                ['dog', 'es', true],
                ['cat', 'es', true],
                ['animal', 'es', false],
            ]);

        $this->stubProvider = $provider;
    }

    public static function wordsProvider(): array
    {
        return [
            ['es', 'dog', 'perro'],
            ['es', 'cat', 'gato'],
        ];
    }

    #[Test]
    #[DataProvider('wordsProvider')]
    public function it_translates_a_text(string $locale, string $text, string $translatedText): void
    {
        $translator = new Translator($this->stubProvider);
        $this->assertEquals($translatedText, $translator->translate(text: $text, locale: $locale));
    }

    #[Test]
    public function it_throws_unsupported_translation_exception(): void
    {
        $this->expectException(UnsupportedTranslationException::class);

        $translator = new Translator($this->stubProvider);
        $translator->translate(text: 'animal', locale: 'es');
    }
}
