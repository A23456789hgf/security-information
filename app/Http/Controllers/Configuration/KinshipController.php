<?php
namespace App\Http\Controllers\Configuration;
use App\Models\Kinship;
class KinshipController extends BaseConfigurationController
{
    protected $modelClass = Kinship::class;
    protected $viewPath = 'configuration.kinships';
    protected $routeName = 'configuration.kinships';
    protected $field = 'name';
    protected function getImportConfig()
    {
        return [
            'fields' => ['name' => 'صلة القرابة'],
            'identity' => ['name'],
        ];
    }
}
