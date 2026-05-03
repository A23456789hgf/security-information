<?php

namespace App\Http\Controllers\Configuration;

use App\Models\ArrestingAuthority;

class ArrestingAuthorityController extends BaseConfigurationController
{
    protected $modelClass        = ArrestingAuthority::class;
    protected $viewPath          = 'configuration.arresting_authorities';
    protected $routeName         = 'configuration.arresting-authorities';
    protected $field             = 'name';
    protected $variableName      = 'arrestingAuthority';
    protected $pluralVariableName = 'arrestingAuthorities';
}
