<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Axis;
use App\Models\MilitaryDistrict;
use Illuminate\Http\Request;

class AxisController extends BaseConfigurationController
{
    protected $modelClass        = Axis::class;
    protected $viewPath          = 'configuration.department.axis';
    protected $routeName         = 'configuration.axes';
    protected $field             = 'name';
    protected $variableName      = 'axis';
    protected $pluralVariableName = 'axes';

    public function index(Request $request)
    {
        $query = Axis::with('district');

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%$search%")
                  ->orWhereHas('district', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        if ($sort = $request->query('sort')) {
            $query->orderBy($sort, $request->query('direction', 'asc'));
        } else {
            $query->latest();
        }

        $axes = $query->paginate($request->input('per_page', 50));

        return view($this->viewPath . '.index', compact('axes'));
    }

    public function create()
    {
        $districts = MilitaryDistrict::all();
        return view($this->viewPath . '.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'military_district_id' => 'required|exists:military_districts,id',
            'name'                 => 'required|string|max:255',
        ]);

        if (Axis::where('military_district_id', $request->military_district_id)->where('name', $request->name)->exists()) {
            return back()->withErrors(['name' => 'المحور موجود مسبقاً في هذه الدائرة'])->withInput();
        }

        Axis::create($request->only('military_district_id', 'name'));
        return redirect()->route($this->routeName . '.index')->with('success', 'تمت الإضافة');
    }

    public function edit($id)
    {
        $axis      = Axis::findOrFail($id);
        $districts = MilitaryDistrict::all();
        return view($this->viewPath . '.edit', compact('axis', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $axis = Axis::findOrFail($id);
        $request->validate([
            'military_district_id' => 'required|exists:military_districts,id',
            'name'                 => 'required|string|max:255',
        ]);

        if (Axis::where('military_district_id', $request->military_district_id)
                ->where('name', $request->name)
                ->where('id', '!=', $id)
                ->exists()) {
            return back()->withErrors(['name' => 'المحور موجود مسبقاً في هذه الدائرة'])->withInput();
        }

        $axis->update($request->only('military_district_id', 'name'));
        return redirect()->route($this->routeName . '.index')->with('success', 'تم التحديث');
    }

    public function destroy($id)
    {
        Axis::findOrFail($id)->delete();
        return redirect()->route($this->routeName . '.index')->with('success', 'تم الحذف');
    }
}
