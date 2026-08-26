<?php

namespace Modules\Differences\Engines;

interface MatchingStrategyInterface
{
    /**
     * Calculate similarity percentage between two fields of data.
     * Returns an array with keys:
     * - 'percentage': float (0 to 100)
     * - 'details': array (details about calculations: diffs, type, etc.)
     */
    public function calculateSimilarity(string $source, string $target, array $options = []): array;
}
