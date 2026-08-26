<?php

namespace Modules\Differences\Engines;

use Modules\Differences\MatchingLogic\ArabicNormalization;

class StandardArabicStrategy implements MatchingStrategyInterface
{
    public function calculateSimilarity(string $source, string $target, array $options = []): array
    {
        $excludedWords = $options['excluded_words'] ?? [];

        // 1. Normalize both strings
        $sourceNorm = ArabicNormalization::normalize($source, $excludedWords);
        $targetNorm = ArabicNormalization::normalize($target, $excludedWords);

        // If either is empty after normalization
        if ($sourceNorm === '' || $targetNorm === '') {
            return [
                'percentage' => 0.00,
                'details' => [
                    'match_type' => 'not_found',
                    'label' => 'لا يوجد تطابق',
                    'letter_difference' => null
                ]
            ];
        }

        // 2. Complete Match
        if ($sourceNorm === $targetNorm) {
            return [
                'percentage' => 100.00,
                'details' => [
                    'match_type' => 'complete_match',
                    'label' => 'طابق تام',
                    'letter_difference' => 0,
                    'source_normalized' => $sourceNorm,
                    'target_normalized' => $targetNorm
                ]
            ];
        }

        // 3. Word Order Swaps
        $sourceSorted = ArabicNormalization::normalizeAndSort($source, $excludedWords);
        $targetSorted = ArabicNormalization::normalizeAndSort($target, $excludedWords);
        if ($sourceSorted === $targetSorted) {
            return [
                'percentage' => 95.00,
                'details' => [
                    'match_type' => 'word_order',
                    'label' => 'اختلاف ترتيب الكلمات',
                    'letter_difference' => 0,
                    'source_normalized' => $sourceNorm,
                    'target_normalized' => $targetNorm
                ]
            ];
        }

        // 4. Phonetic / Spelling Similarity
        // Map phonetically similar letters
        $phoneticMap = [
            'ص' => 'س', 'س' => 'ص',
            'ض' => 'ظ', 'ظ' => 'ض',
            'ق' => 'ك', 'ك' => 'ق',
            'ت' => 'ه', 'ة' => 'ه',
            'ذ' => 'ز', 'ز' => 'ذ',
            'ث' => 'س'
        ];
        $sourcePhonetic = strtr($sourceNorm, $phoneticMap);
        $targetPhonetic = strtr($targetNorm, $phoneticMap);
        if ($sourcePhonetic === $targetPhonetic) {
            return [
                'percentage' => 90.00,
                'details' => [
                    'match_type' => 'spelling_similarity',
                    'label' => 'تشابه إملائي وصوتي',
                    'letter_difference' => levenshtein($sourceNorm, $targetNorm),
                    'source_normalized' => $sourceNorm,
                    'target_normalized' => $targetNorm
                ]
            ];
        }

        // 5. Levenshtein edit distance & Levenshtein Percentage
        $levDistance = levenshtein($sourceNorm, $targetNorm);
        $maxLength = max(mb_strlen($sourceNorm), mb_strlen($targetNorm));
        $levPercent = ($maxLength > 0) ? (1 - ($levDistance / $maxLength)) * 100 : 0;

        $matchType = 'partial_similarity';
        $label = 'تشابه جزئي';

        // Check exact letter difference labels (up to 6 letters)
        if ($levDistance === 1) {
            $matchType = 'one_letter_diff';
            $label = 'اختلاف حرف واحد';
        } elseif ($levDistance === 2) {
            $matchType = 'two_letter_diff';
            $label = 'اختلاف حرفين';
        } elseif ($levDistance === 3) {
            $matchType = 'three_letter_diff';
            $label = 'اختلاف ثلاثة أحرف';
        } elseif ($levDistance === 4) {
            $matchType = 'four_letter_diff';
            $label = 'اختلاف أربعة أحرف';
        } elseif ($levDistance === 5) {
            $matchType = 'five_letter_diff';
            $label = 'اختلاف خمسة أحرف';
        } elseif ($levDistance === 6) {
            $matchType = 'six_letter_diff';
            $label = 'اختلاف ستة أحرف';
        }

        return [
            'percentage' => round($levPercent, 2),
            'details' => [
                'match_type' => $matchType,
                'label' => $label,
                'letter_difference' => $levDistance,
                'source_normalized' => $sourceNorm,
                'target_normalized' => $targetNorm
            ]
        ];
    }
}
