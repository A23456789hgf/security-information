<?php
namespace App\Http\Controllers\Configuration;
use App\Models\IdeologicalAffiliation;
class IdeologicalAffiliationController extends BaseConfigurationController
{
    protected $modelClass = IdeologicalAffiliation::class;
    protected $viewPath = 'configuration.ideological_affiliations';
    protected $routeName = 'configuration.ideological-affiliations';
    protected $field = 'name';
    protected function getImportConfig()
    {
        return [
            'fields' => ['name' => 'الانتماء العقائدي'],
            'identity' => ['name'],
        ];
    }
}
