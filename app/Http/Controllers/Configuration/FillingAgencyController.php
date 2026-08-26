<?php

namespace App\Http\Controllers\Configuration;

use App\Models\FillingAgency;

class FillingAgencyController extends BaseConfigurationController
{
    protected $modelClass = FillingAgency::class;

    protected $viewPath = 'configuration.filling_agencies';

    protected $routeName = 'configuration.filling-agencies';

    protected $field = 'name';

    protected $variableName = 'fillingAgency';

    protected $pluralVariableName = 'fillingAgencies';
}
