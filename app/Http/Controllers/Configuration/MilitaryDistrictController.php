<?php

namespace App\Http\Controllers\Configuration;

use App\Models\MilitaryDistrict;

class MilitaryDistrictController extends BaseConfigurationController
{
    protected $modelClass        = MilitaryDistrict::class;
    protected $viewPath          = 'configuration.military_districts';
    protected $routeName         = 'configuration.military-districts';
    protected $field             = 'name';
    protected $variableName      = 'district';
    protected $pluralVariableName = 'districts';
}
