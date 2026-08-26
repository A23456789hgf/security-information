<?php

namespace Modules\Differences\Engines;

use Modules\Differences\Models\DifferenceAnalysisSetting;

class MatchingEngine
{
    protected $strategy;
    protected $threshold;
    protected $rules;
    protected $excludedWords;

    public function __construct()
    {
        // Load settings from database or defaults
        $setting = DifferenceAnalysisSetting::first();
        if (!$setting) {
            $setting = DifferenceAnalysisSetting::create([
                'similarity_threshold' => 80,
                'excluded_words' => ['السيد', 'الدكتور', 'الدكتورة', 'الاستاذ', 'الاستاذة', 'الشيخ', 'الشيخه', 'ابو', 'ام'],
                'selected_strategy' => 'standard',
                'matching_rules' => [
                    ['id' => 1, 'columns' => ['name', 'id'], 'result_status' => 'match', 'label' => 'الاسم + الهوية (مطابقة مؤكدة)'],
                    ['id' => 2, 'columns' => ['name'], 'result_status' => 'similarity', 'label' => 'الاسم فقط (تشابه)'],
                    ['id' => 3, 'columns' => ['phone'], 'result_status' => 'similarity', 'label' => 'الهاتف فقط (يحتاج مراجعة)']
                ]
            ]);
        }

        $this->threshold = $setting->similarity_threshold;
        $this->rules = $setting->matching_rules;
        $this->excludedWords = $setting->excluded_words ?? [];

        switch ($setting->selected_strategy) {
            case 'soundex':
                $this->strategy = new ArabicSoundexStrategy();
                break;
            case 'nlp':
                $this->strategy = new NlpSimilarityStrategy();
                break;
            case 'standard':
            default:
                $this->strategy = new StandardArabicStrategy();
                break;
        }
    }

    /**
     * Compare an external Excel row with a system target record.
     * $externalRow: ['name' => ..., 'id' => ..., 'phone' => ..., 'address' => ...]
     * $systemRow: ['name' => ..., 'id' => ..., 'phone' => ..., 'address' => ...]
     */
    public function match(array $externalRow, array $systemRow): array
    {
        $matches = [];
        $calculations = [];

        // 1. Calculate similarity for each mapped column type
        // Name similarity
        $nameSim = 0.00;
        $nameDetails = [];
        if (!empty($externalRow['name']) && !empty($systemRow['name'])) {
            $res = $this->strategy->calculateSimilarity($externalRow['name'], $systemRow['name'], [
                'excluded_words' => $this->excludedWords
            ]);
            $nameSim = $res['percentage'];
            $nameDetails = $res['details'];
        }
        $matches['name'] = ($nameSim >= $this->threshold);
        $calculations['name'] = [
            'similarity' => $nameSim,
            'details' => $nameDetails
        ];

        // ID match
        $idMatched = false;
        if (!empty($externalRow['id']) && !empty($systemRow['id'])) {
            $extId = preg_replace('/\D/', '', $externalRow['id']);
            $sysId = preg_replace('/\D/', '', $systemRow['id']);
            $idMatched = ($extId !== '' && $extId === $sysId);
        }
        $matches['id'] = $idMatched;
        $calculations['id'] = ['matched' => $idMatched];

        // Phone match
        $phoneMatched = false;
        if (!empty($externalRow['phone']) && !empty($systemRow['phone'])) {
            $extPhone = preg_replace('/\D/', '', $externalRow['phone']);
            $sysPhone = preg_replace('/\D/', '', $systemRow['phone']);
            // Compare last 9 digits to handle country code variants
            $phoneMatched = ($extPhone !== '' && substr($extPhone, -9) === substr($sysPhone, -9));
        }
        $matches['phone'] = $phoneMatched;
        $calculations['phone'] = ['matched' => $phoneMatched];

        // Address match
        $addressSim = 0.00;
        if (!empty($externalRow['address']) && !empty($systemRow['address'])) {
            similar_text(mb_strtolower($externalRow['address']), mb_strtolower($systemRow['address']), $addressSim);
        }
        $matches['address'] = ($addressSim >= $this->threshold);
        $calculations['address'] = ['similarity' => $addressSim];

        // 2. Evaluate composite rules in order
        $finalStatus = 'not_found';
        $triggeredRule = null;

        foreach ($this->rules as $rule) {
            $ruleColumns = $rule['columns'];
            $ruleSatisfied = true;
            foreach ($ruleColumns as $col) {
                if (empty($matches[$col])) {
                    $ruleSatisfied = false;
                    break;
                }
            }
            if ($ruleSatisfied) {
                $finalStatus = $rule['result_status'];
                $triggeredRule = $rule;
                break;
            }
        }

        return [
            'status' => $finalStatus, // match, similarity, duplicate, not_found
            'percentage' => $nameSim, // primary similarity percentage is name-based
            'triggered_rule' => $triggeredRule,
            'calculations' => $calculations
        ];
    }

    public function getThreshold(): int
    {
        return $this->threshold;
    }

    public function getExcludedWords(): array
    {
        return $this->excludedWords;
    }
}
