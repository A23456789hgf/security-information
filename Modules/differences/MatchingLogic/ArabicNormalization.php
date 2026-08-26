<?php

namespace Modules\Differences\MatchingLogic;

class ArabicNormalization
{
    /**
     * Normalize Arabic string for comparison
     */
    public static function normalize(?string $text, array $excludedWords = []): string
    {
        if ($text === null) {
            return '';
        }

        // 1. Trim and convert to lowercase
        $text = mb_strtolower(trim($text), 'UTF-8');

        // 2. Remove standard Arabic diacritics (harakat)
        $diacritics = [
            'ً', 'ٌ', 'ٍ', 'َ', 'ُ', 'ِ', 'ْ', 'ّ', 'ـ'
        ];
        $text = str_replace($diacritics, '', $text);

        // 3. Normalize Hamzas
        $hamzas = ['أ', 'إ', 'آ', 'ؤ', 'ئ'];
        $text = str_replace($hamzas, 'ا', $text);

        // 4. Unify Ta Marbouta & Haa
        $text = str_replace('ة', 'ه', $text);

        // 5. Unify Alif Maqsoura & Ya
        $text = str_replace('ى', 'ي', $text);

        // 6. Strip spaces after common prefixes
        $prefixes = ['عبد', 'ابو', 'أبو', 'ام', 'أم', 'ابن', 'اب', 'بن', 'ال', 'آل'];
        foreach ($prefixes as $p) {
            // Replace spaces after prefixes
            $text = preg_replace('/\b' . $p . '\s+/', $p, $text);
        }

        // 7. Remove excluded words (stop words / titles)
        if (!empty($excludedWords)) {
            foreach ($excludedWords as $word) {
                $wordNorm = self::normalizeBasic($word);
                if ($wordNorm !== '') {
                    $text = preg_replace('/\b' . preg_quote($wordNorm, '/') . '\b/u', '', $text);
                }
            }
        }

        // 8. Clean up non-alphanumeric characters, except spaces
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);

        // 9. Normalize spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    /**
     * Basic normalization without excluding words (used internally)
     */
    private static function normalizeBasic(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $diacritics = ['ً', 'ٌ', 'ٍ', 'َ', 'ُ', 'ِ', 'ْ', 'ّ', 'ـ'];
        $text = str_replace($diacritics, '', $text);
        $hamzas = ['أ', 'إ', 'آ', 'ؤ', 'ئ'];
        $text = str_replace($hamzas, 'ا', $text);
        $text = str_replace('ة', 'ه', $text);
        $text = str_replace('ى', 'ي', $text);
        return trim($text);
    }

    /**
     * Sort words alphabetically to handle word order variations
     */
    public static function normalizeAndSort(?string $text, array $excludedWords = []): string
    {
        $norm = self::normalize($text, $excludedWords);
        if ($norm === '') {
            return '';
        }

        $words = explode(' ', $norm);
        sort($words);
        return implode(' ', $words);
    }
}
