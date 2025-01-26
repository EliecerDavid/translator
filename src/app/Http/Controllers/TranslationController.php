<?php

namespace App\Http\Controllers;

use App\Services\Translator\Translator;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'locale' => 'required|string',
            'text' => 'required|string',
        ]);

        $locale = $request->input('locale');
        $text = $request->input('text');

        return [
            'data' => [
                'translated_text' => $this->translator->translate(text: $text, locale: $locale),
            ],
        ];
    }
}
