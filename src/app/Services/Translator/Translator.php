<?php

namespace App\Services\Translator;

use App\Services\Translator\Contracts\Provider;
use App\Services\Translator\Exceptions\UnsupportedTranslationException;

class Translator
{
    protected Provider $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function translate(string $text, string $locale): string
    {
        if (!$this->provider->has(text: $text, locale: $locale)) {
            throw new UnsupportedTranslationException();
        }

        return $this->provider->get(text: $text, locale: $locale);
    }
}
