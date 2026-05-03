<?php

namespace App\Http\Controllers\Configuration;

use App\Models\CourseType;

class CourseTypeController extends BaseConfigurationController
{
    protected $modelClass        = CourseType::class;
    protected $viewPath          = 'configuration.course_types';
    protected $routeName         = 'configuration.course-types';
    protected $field             = 'name';
    protected $variableName      = 'courseType';
    protected $pluralVariableName = 'courseTypes';
}
