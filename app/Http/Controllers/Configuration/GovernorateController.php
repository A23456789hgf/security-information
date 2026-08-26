<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Governorate;

class GovernorateController extends BaseConfigurationController
{
    protected $modelClass = Governorate::class;

    protected $viewPath = 'configuration.governorates';

    protected $routeName = 'configuration.governorates';

    protected $field = 'name';

    protected $variableName = 'governorate';

    protected $pluralVariableName = 'governorates';
    // ─── Import/Export Logic ──────────────────────────────────────────

    protected function getImportConfig()
    {
        return [
            'fields' => ['name' => 'المحافظة'],
            'identity' => ['name'],
        ];
    }
}
