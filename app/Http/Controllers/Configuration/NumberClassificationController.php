<?php

namespace App\Http\Controllers\Configuration;

use App\Models\NumberClassification;

class NumberClassificationController extends BaseConfigurationController
{
    protected $modelClass = NumberClassification::class;

    protected $viewPath = 'configuration.number_classification';

    protected $routeName = 'configuration.number-classifications';

    protected $field = 'classification';

    protected $variableName = 'numberClassification';

    protected $pluralVariableName = 'numberClassifications';
}
