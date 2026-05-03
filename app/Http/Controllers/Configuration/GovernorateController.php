<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends BaseConfigurationController
{
    protected $modelClass        = Governorate::class;
    protected $viewPath          = 'configuration.governorates';
    protected $routeName         = 'configuration.governorates';
    protected $field             = 'name';
    protected $variableName      = 'governorate';
    protected $pluralVariableName = 'governorates';
    // ─── Import/Export Logic ──────────────────────────────────────────

    protected function getImportConfig()
    {
        return [
            'fields' => ['name' => 'المحافظة'],
            'identity' => ['name']
        ];
    }
}
