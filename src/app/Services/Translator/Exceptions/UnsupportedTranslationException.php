<?php

namespace App\Services\Translator\Exceptions;

use Exception;

class UnsupportedTranslationException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): bool
    {
        return true;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(): array
    {
        return [
            'data' => [
                'message' => 'unsupported translation',
            ],
        ];
    }
}
