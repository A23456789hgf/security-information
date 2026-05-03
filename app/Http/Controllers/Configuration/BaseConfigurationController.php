<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseConfigurationController extends Controller
{
    protected $modelClass;
    protected $viewPath;
    protected $routeName;
    protected $field = 'name';
    protected $perPage = 50;
    protected $variableName;
    protected $pluralVariableName;

    protected function getPluralVar()
    {
        return $this->pluralVariableName ?? Str::camel(Str::plural(class_basename($this->modelClass)));
    }

    protected function getSingularVar()
    {
        return $this->variableName ?? Str::camel(class_basename($this->modelClass));
    }

    public function index(Request $request)
    {
        $query = $this->modelClass::query();

        if ($search = $request->input('search')) {
            $query->where($this->field, 'like', "%$search%");
        }

        if ($filter = $request->input('filter')) {
            $query->where($this->field, $filter);
        }

        if ($sort = $request->input('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $items = $query->paginate($request->input('per_page', $this->perPage));
        
        return view($this->viewPath . '.index', [
            $this->getPluralVar() => $items,
            'routeName' => $this->routeName
        ]);
    }

    public function create()
    {
        return view($this->viewPath . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            $this->field => 'required|unique:' . (new $this->modelClass)->getTable() . ',' . $this->field,
        ]);

        $this->modelClass::create($request->only($this->field));

        return redirect()->route($this->routeName . '.index')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit($id)
    {
        $item = $this->modelClass::findOrFail($id);
        return view($this->viewPath . '.edit', [
            $this->getSingularVar() => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->modelClass::findOrFail($id);
        
        $request->validate([
            $this->field => 'required|unique:' . (new $this->modelClass)->getTable() . ',' . $this->field . ',' . $id,
        ]);

        $item->update($request->only($this->field));

        return redirect()->route($this->routeName . '.index')->with('success', 'تم التحديث بنجاح');
    }

    public function destroy($id)
    {
        $item = $this->modelClass::findOrFail($id);
        $item->delete();
        
        return redirect()->route($this->routeName . '.index')->with('success', 'تم الحذف بنجاح');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $items = $this->modelClass::where($this->field, 'like', "%$query%")
            ->limit(10)
            ->get(['id', $this->field . ' as text']);
            
        return response()->json($items);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $this->modelClass::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح']);
        }
        return response()->json(['success' => false, 'message' => 'لم يتم تحديد أي سجلات']);
    }

    public function changeStatus(Request $request, $id)
    {
        $item = $this->modelClass::findOrFail($id);
        if (isset($item->status)) {
            $item->status = $request->input('status');
            $item->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'هذا النموذج لا يدعم تغيير الحالة']);
    }

    public function bulkChangeStatus(Request $request)
    {
        $ids    = $request->input('ids', []);
        $status = $request->input('status');

        if (!empty($ids)) {
            $this->modelClass::whereIn('id', $ids)->update(['status' => $status]);
            return response()->json(['success' => true, 'message' => 'تم تحديث الحالة بنجاح']);
        }
        return response()->json(['success' => false, 'message' => 'لم يتم تحديد أي سجلات']);
    }

    use \App\Traits\HandlesConfigurationImportExport;

    protected function getResourceSlug(): string
    {
        $routeName = $this->routeName;
        return str_replace(['configuration.', '-'], ['', '_'], $routeName);
    }
}
