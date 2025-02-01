<?php

namespace Tests\Feature\Console;

use App\Services\Translator\Exceptions\UnsupportedTranslationException;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Helpers\ApiLayerHelper as TestHelper;

class TranslationTest extends TestCase
{
    #[Test]
    public function it_translates_a_text_with_illuminate_provider(): void
    {
        $this->artisan('app:translate')
            ->expectsQuestion('Write the text', 'dog')
            ->expectsQuestion('Which language would you like to translate it to?', 'es')
            ->expectsQuestion('Which provider would you like to use?', 'illuminate')
            ->expectsOutput('The translation is: perro')
            ->assertSuccessful();
    }

    #[Test]
    public function it_translates_a_text_with_apilayer_provider(): void
    {
        TestHelper::setUpHttpFakeWithAValidResponse(translatedText: 'perro');

        $this->artisan('app:translate')
            ->expectsQuestion('Write the text', 'dog')
            ->expectsQuestion('Which language would you like to translate it to?', 'es')
            ->expectsQuestion('Which provider would you like to use?', 'apilayer')
            ->expectsOutput('The translation is: perro')
            ->assertSuccessful();
    }

    #[Test]
    public function it_returns_unsupported_translation_when_text_is_not_supported(): void
    {
        $this->artisan('app:translate')
            ->expectsQuestion('Write the text', 'dog')
            ->expectsQuestion('Which language would you like to translate it to?', 'fake language')
            ->expectsQuestion('Which provider would you like to use?', 'illuminate')
            ->expectsOutput('The translation is unsupported')
            ->assertSuccessful();
    }

    #[Test]
    public function it_returns_unsupported_provider_when_provider_is_not_supported(): void
    {
        $this->artisan('app:translate')
            ->expectsQuestion('Write the text', 'dog')
            ->expectsQuestion('Which language would you like to translate it to?', 'es')
            ->expectsQuestion('Which provider would you like to use?', 'fake provider')
            ->expectsOutput('The provider is unsupported')
            ->assertSuccessful();
    }
}
