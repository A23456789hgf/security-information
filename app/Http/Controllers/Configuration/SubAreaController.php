<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\SubArea;
use App\Traits\HandlesConfigurationImportExport;
use Illuminate\Http\Request;

class SubAreaController extends Controller
{
    use HandlesConfigurationImportExport;

    protected $modelClass = SubArea::class;

    protected $routeName = 'configuration.sub-areas';

    protected $field = 'name';

    public function index(Request $request)
    {
        $query = SubArea::with(['governorate', 'directorate']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('governorate', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('directorate', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        if ($request->has('governorate_id') && $request->governorate_id) {
            $query->where('governorate_id', $request->governorate_id);
        }

        if ($request->has('directorate_id') && $request->directorate_id) {
            $query->where('directorate_id', $request->directorate_id);
        }

        $subAreas = $query->paginate(15);
        $governorates = Governorate::all();
        $directorates = collect();
        if ($request->has('governorate_id') && $request->governorate_id) {
            $directorates = Directorate::where('governorate_id', $request->governorate_id)->get();
        }

        return view('configuration.sub_areas.index', compact('subAreas', 'governorates', 'directorates'));
    }

    public function create()
    {
        $governorates = Governorate::all();

        return view('configuration.sub_areas.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'directorate_id' => 'required|exists:directorates,id',
            'name' => 'required|string|max:255|unique:sub_areas,name,NULL,id,directorate_id,'.$request->directorate_id,
        ]);

        SubArea::create($request->all());

        return redirect()->route('configuration.sub-areas.index')->with('success', 'تمت إضافة العزلة/المنطقة بنجاح');
    }

    public function edit(SubArea $subArea)
    {
        $governorates = Governorate::all();
        $directorates = Directorate::where('governorate_id', $subArea->governorate_id)->get();

        return view('configuration.sub_areas.edit', compact('subArea', 'governorates', 'directorates'));
    }

    public function update(Request $request, SubArea $subArea)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'directorate_id' => 'required|exists:directorates,id',
            'name' => 'required|string|max:255|unique:sub_areas,name,'.$subArea->id.',id,directorate_id,'.$request->directorate_id,
        ]);

        $subArea->update($request->all());

        return redirect()->route('configuration.sub-areas.index')->with('success', 'تم تحديث العزلة/المنطقة بنجاح');
    }

    public function destroy(SubArea $subArea)
    {
        $subArea->delete();

        return redirect()->route('configuration.sub-areas.index')->with('success', 'تم حذف العزلة/المنطقة بنجاح');
    }

    public function getDirectorates($governorateId)
    {
        $directorates = Directorate::where('governorate_id', $governorateId)->get();

        return response()->json($directorates);
    }

    protected function getResourceSlug(): string
    {
        return 'sub_areas';
    }

    protected function getImportConfig()
    {
        return [
            'fields' => [
                'governorate_id' => 'المحافظة',
                'directorate_id' => 'المديرية',
                'name' => 'العزلة / المنطقة',
            ],
            'identity' => ['directorate_id', 'name'],
        ];
    }

    /**
     * Override importStore to use the optimized SubAreaImporter
     */
    public function importStore(Request $request)
    {
        $slug = 'sub_areas';
        $filePath = session("pending_config_import_{$slug}_file");
        $mapping = session("pending_config_import_{$slug}_mapping");
        $mode = session("pending_config_import_{$slug}_mode", 'append');
        $startRow = session("pending_config_import_{$slug}_start_row", 2);

        if (! $filePath || ! file_exists(storage_path('app/'.$filePath))) {
            return response()->json(['success' => false, 'message' => 'الملف غير موجود، حاول التحديث وإعادة الرفع.']);
        }

        try {
            $sessionId = uniqid('import_', true);

            \App\Models\ImportProgress::create([
                'session_id' => $sessionId,
                'type' => $slug,
                'status' => 'pending',
                'processed_rows' => 0,
                'total_rows' => 0,
            ]);

            $config = [
                'model' => $this->modelClass,
                'fields' => $this->getImportConfig()['fields'],
                'identity' => $this->getImportConfig()['identity'] ?? [],
                'routeName' => $this->routeName ?? "configuration.$slug",
            ];

            \App\Jobs\ProcessConfigurationImportJob::dispatch(
                $sessionId,
                $filePath,
                $mapping,
                $mode,
                $config,
                $startRow,
                \App\Imports\SubAreaImporter::class // Use optimized importer
            );

            session()->forget([
                "pending_config_import_{$slug}_file",
                "pending_config_import_{$slug}_headers",
                "pending_config_import_{$slug}_mapping",
                "pending_config_import_{$slug}_mode",
                "pending_config_import_{$slug}_start_row",
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم بدء الاستيراد السريع في الخلفية.',
                'session_id' => $sessionId,
                'is_async' => true,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء بدء الاستيراد: '.$e->getMessage()]);
        }
    }
}
