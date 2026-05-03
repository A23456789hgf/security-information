<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Qualification;

class QualificationController extends BaseConfigurationController
{
    protected $modelClass = Qualification::class;
    protected $viewPath   = 'configuration.qualifications';
    protected $routeName  = 'configuration.qualifications';
    protected $field      = 'name';

    protected function getImportConfig()
    {
        return [
            'fields' => ['name' => 'المؤهل'],
            'identity' => ['name']
        ];
    }
}
