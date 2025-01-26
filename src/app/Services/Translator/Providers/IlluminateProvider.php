<?php

namespace App\Services\Translator\Providers;

use App\Services\Translator\Contracts\Provider;
use Illuminate\Translation\Translator as IlluminateTranslator;

class IlluminateProvider implements Provider
{
    protected IlluminateTranslator $translator;

    public function __construct(IlluminateTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function has(string $text, string $locale): bool
    {
        $translatorKey = 'translator.' . $text;
        return $this->translator->has(key: $translatorKey, locale: $locale);
    }

    public function get(string $text, string $locale): string
    {
        $translatorKey = 'translator.' . $text;
        return $this->translator->get(key: $translatorKey, locale: $locale);
    }
}
