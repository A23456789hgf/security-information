<?php

namespace App\Http\Controllers\Configuration;

use App\Models\JobStatus;

class JobStatusController extends BaseConfigurationController
{
    protected $modelClass        = JobStatus::class;
    protected $viewPath          = 'configuration.job_status';
    protected $routeName         = 'configuration.job-statuses';
    protected $field             = 'job_status';
    protected $variableName      = 'jobStatus';
    protected $pluralVariableName = 'jobStatuses';
}
