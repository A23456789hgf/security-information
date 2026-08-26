<?php

namespace Modules\Differences\Services;

use Modules\Differences\Models\DifferenceAnalysisLog;

class AnalysisLogService
{
    public static function log(int $analysisId, string $level, string $message, ?array $details = null): void
    {
        DifferenceAnalysisLog::create([
            'analysis_id' => $analysisId,
            'level' => $level,
            'message' => $message,
            'details' => $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null
        ]);
    }

    public static function info(int $analysisId, string $message, ?array $details = null): void
    {
        self::log($analysisId, 'info', $message, $details);
    }

    public static function warning(int $analysisId, string $message, ?array $details = null): void
    {
        self::log($analysisId, 'warning', $message, $details);
    }

    public static function error(int $analysisId, string $message, ?array $details = null): void
    {
        self::log($analysisId, 'error', $message, $details);
    }
}
