<?php

namespace Modules\Differences\Engines;

use Modules\Differences\MatchingLogic\ArabicNormalization;

class NlpSimilarityStrategy implements MatchingStrategyInterface
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

        // Calculate token/word overlap (Jaccard similarity)
        $sourceTokens = explode(' ', $sourceNorm);
        $targetTokens = explode(' ', $targetNorm);

        $intersection = array_intersect($sourceTokens, $targetTokens);
        $union = array_unique(array_merge($sourceTokens, $targetTokens));

        $jaccard = (count($union) > 0) ? count($intersection) / count($union) : 0;

        // Combine Jaccard similarity and similar_text percentage
        similar_text($sourceNorm, $targetNorm, $simPercent);

        $finalPercent = ($jaccard * 60) + (($simPercent / 100) * 40);

        return [
            'percentage' => round($finalPercent, 2),
            'details' => [
                'match_type' => 'nlp_similarity',
                'label' => 'تشابه معالجة اللغات الطبيعية (NLP)',
                'jaccard_similarity' => round($jaccard, 2),
                'text_overlap' => round($simPercent, 2)
            ]
        ];
    }
}
