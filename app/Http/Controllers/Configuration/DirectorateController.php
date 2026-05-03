<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Directorate;
use App\Models\Governorate;
use Illuminate\Http\Request;

class DirectorateController extends BaseConfigurationController
{
    protected $modelClass        = Directorate::class;
    protected $viewPath          = 'configuration.directorates';
    protected $routeName         = 'configuration.directorates';
    protected $field             = 'name';
    protected $variableName      = 'directorate';
    protected $pluralVariableName = 'directorates';

    public function index(Request $request)
    {
        $query = Directorate::with('governorate');

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%$search%");
        }
        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }
        if ($sort = $request->query('sort')) {
            $query->orderBy($sort, $request->query('direction', 'asc'));
        } else {
            $query->latest();
        }

        $directorates = $query->paginate($request->input('per_page', 20));
        $governorates = Governorate::all();

        $routeName = $this->routeName;
        return view($this->viewPath . '.index', compact('directorates', 'governorates', 'routeName'));
    }

    public function create()
    {
        $governorates = Governorate::all();
        return view($this->viewPath . '.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name'           => 'required|string|max:255|unique:directorates,name,NULL,id,governorate_id,' . $request->governorate_id,
        ]);

        Directorate::create($request->only('governorate_id', 'name'));
        return redirect()->route($this->routeName . '.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit($id)
    {
        $directorate  = Directorate::findOrFail($id);
        $governorates = Governorate::all();
        return view($this->viewPath . '.edit', compact('directorate', 'governorates'));
    }

    public function update(Request $request, $id)
    {
        $directorate = Directorate::findOrFail($id);
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name'           => 'required|string|max:255|unique:directorates,name,' . $id . ',id,governorate_id,' . $request->governorate_id,
        ]);

        $directorate->update($request->only('governorate_id', 'name'));
        return redirect()->route($this->routeName . '.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy($id)
    {
        Directorate::findOrFail($id)->delete();
        return redirect()->route($this->routeName . '.index')->with('success', 'تم الحذف بنجاح');
    }

    protected function getImportConfig()
    {
        return [
            'fields' => ['governorate_id' => 'المحافظة', 'name' => 'المديرية'],
            'identity' => ['governorate_id', 'name']
        ];
    }
}
