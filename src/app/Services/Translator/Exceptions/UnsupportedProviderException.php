<?php

namespace App\Services\Translator\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UnsupportedProviderException extends Exception
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
    public function render(): Response
    {
        return response(content: ['data' => ['message' => 'unsupported provider']], status: 404);
    }
}
