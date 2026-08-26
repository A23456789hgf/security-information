<?php

namespace App\Http\Controllers\Configuration;

use App\Models\ElectronicInput;

class ElectronicInputController extends BaseConfigurationController
{
    protected $modelClass = ElectronicInput::class;

    protected $viewPath = 'configuration.electronic_inputs';

    protected $routeName = 'configuration.electronic-inputs';

    protected $field = 'name';

    protected $variableName = 'electronicInput';

    protected $pluralVariableName = 'electronicInputs';
}
