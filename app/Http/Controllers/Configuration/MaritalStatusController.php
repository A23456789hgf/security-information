<?php

namespace App\Http\Controllers\Configuration;

use App\Models\MaritalStatus;

class MaritalStatusController extends BaseConfigurationController
{
    protected $modelClass = MaritalStatus::class;

    protected $viewPath = 'configuration.marital_statuses';

    protected $routeName = 'configuration.marital-statuses';

    protected $field = 'status';
}
