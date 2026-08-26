<?php
namespace App\Http\Controllers\Configuration;
use App\Models\PoliticalAffiliation;
class PoliticalAffiliationController extends BaseConfigurationController
{
    protected $modelClass = PoliticalAffiliation::class;
    protected $viewPath = 'configuration.political_affiliations';
    protected $routeName = 'configuration.political-affiliations';
    protected $field = 'name';
    protected function getImportConfig()
    {
        return [
            'fields' => ['name' => 'الانتماء السياسي'],
            'identity' => ['name'],
        ];
    }
}
