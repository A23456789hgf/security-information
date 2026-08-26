<?php

namespace App\Http\Controllers\Configuration;

use App\Models\CaseType;

class CaseTypeController extends BaseConfigurationController
{
    protected $modelClass = CaseType::class;

    protected $viewPath = 'configuration.case_types';

    protected $routeName = 'configuration.case-types';

    protected $field = 'name';

    protected $variableName = 'caseType';

    protected $pluralVariableName = 'caseTypes';
}
