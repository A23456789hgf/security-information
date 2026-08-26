<?php

namespace Modules\Differences\Engines;

use Modules\Differences\MatchingLogic\ArabicNormalization;

class ArabicSoundexStrategy implements MatchingStrategyInterface
{
    public function calculateSimilarity(string $source, string $target, array $options = []): array
    {
        $excludedWords = $options['excluded_words'] ?? [];
        $sourceNorm = ArabicNormalization::normalize($source, $excludedWords);
        $targetNorm = ArabicNormalization::normalize($target, $excludedWords);

        if ($sourceNorm === '' || $targetNorm === '') {
            return [
                'percentage' => 0.00,
                'details' => [
                    'match_type' => 'not_found',
                    'label' => 'لا يوجد تطابق'
                ]
            ];
        }

        $sourceCode = $this->soundexArabic($sourceNorm);
        $targetCode = $this->soundexArabic($targetNorm);

        if ($sourceCode === $targetCode) {
            return [
                'percentage' => 90.00,
                'details' => [
                    'match_type' => 'soundex_match',
                    'label' => 'تشابه لفظي (صوتيك)',
                    'source_soundex' => $sourceCode,
                    'target_soundex' => $targetCode
                ]
            ];
        }

        // Percentage based on similar soundex codes
        $distance = levenshtein($sourceCode, $targetCode);
        $percentage = (1 - ($distance / 8)) * 100; // soundex code up to 8 chars
        $percentage = max(0, $percentage);

        return [
            'percentage' => round($percentage, 2),
            'details' => [
                'match_type' => 'soundex_partial',
                'label' => 'تشابه لفظي جزئي',
                'source_soundex' => $sourceCode,
                'target_soundex' => $targetCode
            ]
        ];
    }

    private function soundexArabic(string $word): string
    {
        if ($word === '') {
            return '00000000';
        }

        // Map letters to soundex values
        $map = [
            'ا'=>'', 'أ'=>'', 'إ'=>'', 'آ'=>'', 'ء'=>'', 'ه'=>'', 'ح'=>'', 'خ'=>'', 'ع'=>'', 'غ'=>'',
            'ب'=>'1', 'ف'=>'1',
            'ج'=>'2', 'ش'=>'2', 'ي'=>'2', 'و'=>'',
            'د'=>'3', 'ت'=>'3', 'ض'=>'3', 'ط'=>'3', 'ظ'=>'3',
            'ر'=>'4', 'ل'=>'4',
            'م'=>'5', 'ن'=>'5',
            'س'=>'6', 'ز'=>'6', 'ص'=>'6',
            'ق'=>'7', 'ك'=>'7'
        ];

        // Process words
        $words = explode(' ', $word);
        $code = '';
        foreach ($words as $w) {
            if (mb_strlen($w) === 0) continue;
            $first = mb_substr($w, 0, 1);
            $rest = mb_substr($w, 1);
            
            $res = $first;
            $len = mb_strlen($rest);
            for ($i = 0; $i < $len; $i++) {
                $char = mb_substr($rest, $i, 1);
                if (isset($map[$char]) && $map[$char] !== '') {
                    $res .= $map[$char];
                }
            }
            // Keep unique contiguous digits
            $resClean = $first;
            $resLen = strlen($res);
            for ($i = 1; $i < $resLen; $i++) {
                $digit = $res[$i];
                if ($digit !== $res[$i - 1]) {
                    $resClean .= $digit;
                }
            }
            $code .= str_pad(substr($resClean, 0, 4), 4, '0');
        }

        return substr($code, 0, 8); // Keep up to 8 characters soundex signature
    }
}
