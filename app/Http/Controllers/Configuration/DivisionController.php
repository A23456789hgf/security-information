<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Division;
use App\Models\MilitaryDistrict;
use Illuminate\Http\Request;

class DivisionController extends BaseConfigurationController
{
    protected $modelClass = Division::class;

    protected $viewPath = 'configuration.divisions';

    protected $routeName = 'configuration.divisions';

    protected $field = 'name';

    protected $variableName = 'division';

    protected $pluralVariableName = 'divisions';

    public function index(Request $request)
    {
        $query = Division::with('district');

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%$search%")
                ->orWhereHas('district', fn ($q) => $q->where('name', 'like', "%$search%"));
        }

        if ($sort = $request->query('sort')) {
            $query->orderBy($sort, $request->query('direction', 'asc'));
        } else {
            $query->latest();
        }

        $divisions = $query->paginate($request->input('per_page', 20));

        return view($this->viewPath.'.index', compact('divisions'));
    }

    public function create()
    {
        $districts = MilitaryDistrict::all();

        return view($this->viewPath.'.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'military_district_id' => 'required|exists:military_districts,id',
            'name' => 'required|string|max:255',
        ]);

        if (Division::where('military_district_id', $request->military_district_id)->where('name', $request->name)->exists()) {
            return back()->withErrors(['name' => 'الشعبة موجودة مسبقاً في هذه الدائرة'])->withInput();
        }

        Division::create($request->only('military_district_id', 'name'));

        return redirect()->route($this->routeName.'.index')->with('success', 'تمت الإضافة');
    }

    public function edit($id)
    {
        $division = Division::findOrFail($id);
        $districts = MilitaryDistrict::all();

        return view($this->viewPath.'.edit', compact('division', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $request->validate([
            'military_district_id' => 'required|exists:military_districts,id',
            'name' => 'required|string|max:255',
        ]);

        if (Division::where('military_district_id', $request->military_district_id)
            ->where('name', $request->name)
            ->where('id', '!=', $id)
            ->exists()) {
            return back()->withErrors(['name' => 'الشعبة موجودة مسبقاً في هذه الدائرة'])->withInput();
        }

        $division->update($request->only('military_district_id', 'name'));

        return redirect()->route($this->routeName.'.index')->with('success', 'تم التحديث');
    }

    public function destroy($id)
    {
        Division::findOrFail($id)->delete();

        return redirect()->route($this->routeName.'.index')->with('success', 'تم الحذف');
    }
}
