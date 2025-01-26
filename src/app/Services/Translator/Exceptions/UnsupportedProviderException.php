<?php

namespace App\Services\Translator\Exceptions;

use Exception;

class UnsupportedProviderException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(): array
    {
        return [
            'data' => [
                'message' => 'unsupported provider',
            ],
        ];
    }
}
