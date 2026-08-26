<?php
namespace App\Http\Controllers\Configuration;
use App\Models\HealthStatus;
class HealthStatusController extends BaseConfigurationController
{
    protected $modelClass = HealthStatus::class;
    protected $viewPath = 'configuration.health_statuses';
    protected $routeName = 'configuration.health-statuses';
    protected $field = 'name';
    protected function getImportConfig()
    {
        return [
            'fields' => ['name' => 'الحالة الصحية'],
            'identity' => ['name'],
        ];
    }
}
