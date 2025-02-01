<?php

namespace Tests\Unit\Services\Translator\Providers;

use App\Services\Translator\Providers\IlluminateProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator as IlluminateTranslator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IlluminateProviderTest extends TestCase
{
    protected $stubTranslator;

    protected function setUp(): void
    {
        $translator = $this->createStub(IlluminateTranslator::class);
        $translator->method('get')
            ->willReturnMap([
                ['translator.dog', [], 'es', 'perro'],
                ['translator.cat', [], 'es', 'gato'],
            ]);

        $translator->method('has')
            ->willReturnMap([
                ['translator.dog', 'es', true],
                ['translator.cat', 'es', true],
                ['translator.animal', 'es', false],
            ]);

        $this->stubTranslator = $translator;
    }

    #[Test]
    public function it_returns_a_translated_text(): void
    {
        $provider = new IlluminateProvider($this->stubTranslator);
        $this->assertEquals('perro', $provider->get(text: 'dog', locale: 'es'));
    }

    #[Test]
    public function it_return_true_if_text_is_supported(): void
    {

        $provider = new IlluminateProvider($this->stubTranslator);
        $this->assertTrue($provider->has(text: 'cat', locale: 'es'));
    }

    #[Test]
    public function it_return_false_if_text_is_unsupported(): void
    {
        $provider = new IlluminateProvider($this->stubTranslator);
        $this->assertFalse($provider->has(text: 'animal', locale: 'es'));
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
    public function it_return_translations_from_lang_files(string $locale, string $text, string $translatedText): void
    {
        $files = new Filesystem();
        $loader = new FileLoader(files: $files, path: './lang');

        $illuminateTranslator = new IlluminateTranslator(loader: $loader, locale: $locale);

        $provider = new IlluminateProvider($illuminateTranslator);
        $this->assertEquals($translatedText, $provider->has(text: $text, locale: $locale));
    }
}
