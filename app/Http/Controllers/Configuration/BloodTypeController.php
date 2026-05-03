<?php

namespace App\Http\Controllers\Configuration;

use App\Models\BloodType;

class BloodTypeController extends BaseConfigurationController
{
    protected $modelClass = BloodType::class;
    protected $viewPath   = 'configuration.blood_types';
    protected $routeName  = 'configuration.blood-types';
    protected $field      = 'type';

    protected function getImportConfig()
    {
        return [
            'fields' => ['type' => 'فصيلة الدم'],
            'identity' => ['type']
        ];
    }
}
