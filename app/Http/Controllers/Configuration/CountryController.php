<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Country;

class CountryController extends BaseConfigurationController
{
    protected $modelClass = Country::class;
    protected $viewPath   = 'configuration.countries';
    protected $routeName  = 'configuration.countries';
    protected $field      = 'name';
    protected $pluralVariableName = 'countries';
}
