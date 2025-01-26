<?php

namespace App\Services\Translator\Contracts;

interface Provider
{
    /**
     * Determine if a translation exists.
     *
     * @param  string  $text
     * @param  string  $locale
     * @return bool
     */
    public function has(string $text, string $locale): bool;

    /**
     * Get the translation for a given text.
     *
     * @param  string  $text
     * @param  string  $locale
     * @return string
     */
    public function get(string $text, string $locale): string;
}
