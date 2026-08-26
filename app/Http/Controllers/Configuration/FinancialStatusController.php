<?php

namespace App\Http\Controllers\Configuration;

use App\Models\FinancialStatus;

class FinancialStatusController extends BaseConfigurationController
{
    protected $modelClass = FinancialStatus::class;

    protected $viewPath = 'configuration.financial_status';

    protected $routeName = 'configuration.financial-statuses';

    protected $field = 'name';

    protected function getImportConfig()
    {
        return [
            'fields' => ['name' => 'الحالة المالية'],
            'identity' => ['name'],
        ];
    }
}
