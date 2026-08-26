<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Rank;

class RankController extends BaseConfigurationController
{
    protected $modelClass = Rank::class;

    protected $viewPath = 'configuration.ranks';

    protected $routeName = 'configuration.ranks';

    protected $field = 'rank';

    protected function getImportConfig()
    {
        return [
            'fields' => ['rank' => 'الرتبة'],
            'identity' => ['rank'],
        ];
    }
}
