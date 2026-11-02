<?php

namespace App\Http\Controllers\System;

use App\Exports\UniversalExport;
use App\Http\Controllers\Controller;
use App\Models\InternalEntity;
use App\Models\PersonalData;
use App\Models\SecurityFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SecurityFileController extends Controller
{
    /**
     * Display a listing of the security files.
     */
    public function index(Request $request)
    {
        $query = SecurityFile::with(['personalData', 'preparingEntity']);
        \App\Services\UserScopeService::applyScope($query);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('file_number', 'like', "%{$search}%")
                    ->orWhere('file_preparer', 'like', "%{$search}%")
                    ->orWhere('summary_preparer', 'like', "%{$search}%")
                    ->orWhereHas('personalData', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('jihadi_name', 'like', "%{$search}%")
                            ->orWhere('military_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('preparing_entity_id')) {
            $query->where('preparing_entity_id', $request->preparing_entity_id);
        }

        $selectedPerson = null;
        if ($request->filled('personal_data_id')) {
            $query->where('personal_data_id', $request->personal_data_id);
            $selectedPerson = PersonalData::with([
                'governorate', 'directorate', 'subArea', 'village',
                'rank', 'jobStatus', 'bloodType', 'maritalStatus', 'securityFiles'
            ])->find($request->personal_data_id);
        }

        $perPage = $request->input('per_page', 25);
        $securityFiles = $query->latest()->paginate($perPage)->withQueryString();
        $entities = InternalEntity::orderBy('name')->pluck('name', 'id');
        $personalDataList = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();
        $totalCount = SecurityFile::count();

        return view('system.security_files.index', compact('securityFiles', 'entities', 'personalDataList', 'totalCount', 'selectedPerson'));
    }

    /**
     * Show the form for creating a new security file.
     */
    public function create(Request $request)
    {
        $allPeople = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();
        $entities = InternalEntity::orderBy('name')->pluck('name', 'id');
        $selectedPersonId = $request->input('personal_data_id');

        return view('system.security_files.create', compact('allPeople', 'entities', 'selectedPersonId'));
    }

    /**
     * Store a newly created security file in storage.
     */
    public function store(Request $request)
    {
        $this->sanitizeFileInputs($request);

        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'file_number' => 'nullable|string|max:255',
            'file_date_gregorian' => 'nullable|date',
            'file_date_hijri' => 'nullable|string|max:255',
            'preparing_entity_id' => 'nullable|exists:internal_entities,id',
            'file_preparer' => 'nullable|string|max:255',
            'highlight_summary' => 'nullable|string',
            'jihadist_status' => 'nullable|string',
            'summary_preparer' => 'nullable|string|max:255',
            'summary_date_gregorian' => 'nullable|date',
            'summary_date_hijri' => 'nullable|string|max:255',
            'file_attachments' => 'nullable|array',
            'file_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip,rar,txt|max:20480',
            'summary_attachments' => 'nullable|array',
            'summary_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip,rar,txt|max:20480',
        ], [
            'personal_data_id.required' => 'يرجى اختيار الفرد المرتبط بالملف من البيانات الشخصية.',
            'file_attachments.*.file' => 'الملف المرفق في مرفقات الملف غير صالح.',
            'file_attachments.*.mimes' => 'صيغة الملف غير مدعومة في مرفقات الملف. الصيغ المسموحة: pdf, doc, docx, jpg, jpeg, png, webp',
            'file_attachments.*.max' => 'حجم الملف في مرفقات الملف يجب ألا يتجاوز 20 ميجابايت.',
            'summary_attachments.*.file' => 'الملف المرفق في مرفقات الملخص غير صالح.',
            'summary_attachments.*.mimes' => 'صيغة الملف غير مدعومة في مرفقات الملخص. الصيغ المسموحة: pdf, doc, docx, jpg, jpeg, png, webp',
            'summary_attachments.*.max' => 'حجم الملف في مرفقات الملخص يجب ألا يتجاوز 20 ميجابايت.',
        ]);

        $data = $request->except(['file_attachments', 'summary_attachments']);

        // Handle file attachments upload
        if ($request->hasFile('file_attachments')) {
            $filePaths = [];
            foreach ($request->file('file_attachments') as $file) {
                if ($file && $file->isValid()) {
                    $filePaths[] = $file->store('security_files/files', 'public');
                }
            }
            $data['file_attachments'] = $filePaths;
        }

        // Handle summary attachments upload
        if ($request->hasFile('summary_attachments')) {
            $summaryPaths = [];
            foreach ($request->file('summary_attachments') as $file) {
                if ($file && $file->isValid()) {
                    $summaryPaths[] = $file->store('security_files/summaries', 'public');
                }
            }
            $data['summary_attachments'] = $summaryPaths;
        }

        SecurityFile::create($data);

        return redirect()->route('system.security-files.index')
            ->with('success', 'تم إضافة الملف الأمني بنجاح');
    }

    /**
     * Display the specified security file.
     */
    public function show(SecurityFile $securityFile)
    {
        $securityFile->load(['personalData.governorate', 'personalData.directorate', 'personalData.village', 'preparingEntity']);

        return view('system.security_files.show', compact('securityFile'));
    }

    /**
     * Show the form for editing the specified security file.
     */
    public function edit(SecurityFile $securityFile)
    {
        $allPeople = PersonalData::select('id', 'name', 'jihadi_name', 'military_number')->orderBy('name')->get();
        $entities = InternalEntity::orderBy('name')->pluck('name', 'id');

        return view('system.security_files.edit', compact('securityFile', 'allPeople', 'entities'));
    }

    /**
     * Update the specified security file in storage.
     */
    public function update(Request $request, SecurityFile $securityFile)
    {
        $this->sanitizeFileInputs($request);

        $request->validate([
            'personal_data_id' => 'required|exists:personal_data,id',
            'file_number' => 'nullable|string|max:255',
            'file_date_gregorian' => 'nullable|date',
            'file_date_hijri' => 'nullable|string|max:255',
            'preparing_entity_id' => 'nullable|exists:internal_entities,id',
            'file_preparer' => 'nullable|string|max:255',
            'highlight_summary' => 'nullable|string',
            'jihadist_status' => 'nullable|string',
            'summary_preparer' => 'nullable|string|max:255',
            'summary_date_gregorian' => 'nullable|date',
            'summary_date_hijri' => 'nullable|string|max:255',
            'file_attachments' => 'nullable|array',
            'file_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip,rar,txt|max:20480',
            'summary_attachments' => 'nullable|array',
            'summary_attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip,rar,txt|max:20480',
        ], [
            'personal_data_id.required' => 'يرجى اختيار الفرد المرتبط بالملف من البيانات الشخصية.',
            'file_attachments.*.file' => 'الملف المرفق في مرفقات الملف غير صالح.',
            'file_attachments.*.mimes' => 'صيغة الملف غير مدعومة في مرفقات الملف. الصيغ المسموحة: pdf, doc, docx, jpg, jpeg, png, webp',
            'file_attachments.*.max' => 'حجم الملف في مرفقات الملف يجب ألا يتجاوز 20 ميجابايت.',
            'summary_attachments.*.file' => 'الملف المرفق في مرفقات الملخص غير صالح.',
            'summary_attachments.*.mimes' => 'صيغة الملف غير مدعومة في مرفقات الملخص. الصيغ المسموحة: pdf, doc, docx, jpg, jpeg, png, webp',
            'summary_attachments.*.max' => 'حجم الملف في مرفقات الملخص يجب ألا يتجاوز 20 ميجابايت.',
        ]);

        $data = $request->except(['file_attachments', 'summary_attachments']);

        // Preserve existing or append new file attachments
        $filePaths = $securityFile->file_attachments ?? [];
        if ($request->hasFile('file_attachments')) {
            foreach ($request->file('file_attachments') as $file) {
                if ($file && $file->isValid()) {
                    $filePaths[] = $file->store('security_files/files', 'public');
                }
            }
        }
        $data['file_attachments'] = $filePaths;

        // Preserve existing or append new summary attachments
        $summaryPaths = $securityFile->summary_attachments ?? [];
        if ($request->hasFile('summary_attachments')) {
            foreach ($request->file('summary_attachments') as $file) {
                if ($file && $file->isValid()) {
                    $summaryPaths[] = $file->store('security_files/summaries', 'public');
                }
            }
        }
        $data['summary_attachments'] = $summaryPaths;

        $securityFile->update($data);

        return redirect()->route('system.security-files.index')
            ->with('success', 'تم تحديث الملف الأمني بنجاح');
    }

    /**
     * Filter out empty or un-uploaded file items from input arrays.
     */
    private function sanitizeFileInputs(Request $request)
    {
        foreach (['file_attachments', 'summary_attachments'] as $field) {
            if ($request->hasFile($field)) {
                $files = array_filter((array) $request->file($field), function ($file) {
                    return $file && $file->isValid();
                });
                if (empty($files)) {
                    $request->files->remove($field);
                } else {
                    $request->files->set($field, array_values($files));
                }
            }
        }
    }

    /**
     * Remove the specified security file from storage.
     */
    public function destroy(SecurityFile $securityFile)
    {
        // Clean up attached files
        if ($securityFile->file_attachments) {
            foreach ($securityFile->file_attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        if ($securityFile->summary_attachments) {
            foreach ($securityFile->summary_attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $securityFile->delete();

        return redirect()->route('system.security-files.index')
            ->with('success', 'تم حذف الملف الأمني بنجاح');
    }

    /**
     * Delete single attachment from security file.
     */
    public function deleteAttachment(Request $request, SecurityFile $securityFile)
    {
        $type = $request->input('type'); // 'file' or 'summary'
        $index = $request->input('index');

        if ($type === 'file') {
            $attachments = $securityFile->file_attachments ?? [];
            if (isset($attachments[$index])) {
                Storage::disk('public')->delete($attachments[$index]);
                unset($attachments[$index]);
                $securityFile->update(['file_attachments' => array_values($attachments)]);
            }
        } elseif ($type === 'summary') {
            $attachments = $securityFile->summary_attachments ?? [];
            if (isset($attachments[$index])) {
                Storage::disk('public')->delete($attachments[$index]);
                unset($attachments[$index]);
                $securityFile->update(['summary_attachments' => array_values($attachments)]);
            }
        }

        return back()->with('success', 'تم حذف المرفق بنجاح');
    }

    /**
     * AJAX endpoint to fetch person details.
     */
    public function getPersonDetails($id)
    {
        $person = PersonalData::with([
            'governorate', 'directorate', 'subArea', 'village',
            'rank', 'jobStatus', 'bloodType', 'maritalStatus', 'securityFiles'
        ])->findOrFail($id);

        $address = implode(' / ', array_filter([
            $person->governorate->name ?? null,
            $person->directorate->name ?? null,
            $person->subArea->name ?? null,
            $person->village->name ?? null,
        ]));

        return response()->json([
            'id' => $person->id,
            'name' => $person->name,
            'jihadi_name' => $person->jihadi_name ?? 'غير محدد',
            'military_number' => $person->military_number ?? 'غير مسجل',
            'national_id_number' => $person->national_id_number ?? 'غير مسجل',
            'phone' => $person->phone_1 ?: ($person->phone_2 ?: ($person->phone_3 ?: 'غير مسجل')),
            'job' => $person->job ?? 'غير مسجل',
            'workplace' => $person->workplace ?? 'غير مسجل',
            'unit' => $person->unit ?? 'غير مسجل',
            'rank' => $person->rank->name ?? 'غير محدد',
            'blood_type' => $person->bloodType->name ?? 'غير محدد',
            'marital_status' => $person->maritalStatus->name ?? 'غير محدد',
            'photo_url' => $person->photo_url,
            'address' => $address ?: 'غير محدد',
            'security_files_count' => $person->securityFiles->count(),
            'profile_url' => route('system.personal-data.show', $person->id),
        ]);
    }

    /**
     * Print single security file.
     */
    public function print(SecurityFile $securityFile)
    {
        $securityFile->load(['personalData.governorate', 'personalData.directorate', 'preparingEntity']);

        return view('system.security_files.print', compact('securityFile'));
    }

    /**
     * Print all security files listing.
     */
    public function printAll(Request $request)
    {
        $securityFiles = SecurityFile::with(['personalData', 'preparingEntity'])->get();

        return view('system.security_files.print', [
            'securityFiles' => $securityFiles,
            'isList' => true,
        ]);
    }

    /**
     * Export security files to Excel.
     */
    public function export(Request $request)
    {
        $query = SecurityFile::with(['personalData', 'preparingEntity']);

        if ($search = $request->input('search')) {
            $query->where('file_number', 'like', "%{$search}%")
                ->orWhereHas('personalData', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $records = $query->get()->map(function ($item) {
            return [
                'رقم الملف الأمني' => $item->file_number ?? '-',
                'اسم الفرد' => $item->personalData->name ?? '-',
                'الاسم الجهادي' => $item->personalData->jihadi_name ?? '-',
                'الرقم العسكري' => $item->personalData->military_number ?? '-',
                'تاريخ الملف' => $item->file_date_gregorian ? $item->file_date_gregorian->format('Y-m-d') : '-',
                'تاريخ الملف (هجري)' => $item->file_date_hijri ?? '-',
                'الجهة المعدة' => $item->preparingEntity->name ?? '-',
                'معد الملف' => $item->file_preparer ?? '-',
                'معد التلخيص' => $item->summary_preparer ?? '-',
                'تاريخ التلخيص' => $item->summary_date_gregorian ? $item->summary_date_gregorian->format('Y-m-d') : '-',
                'أبرز ملخص الملف' => $item->highlight_summary ?? '-',
                'الوضع الجهادي' => $item->jihadist_status ?? '-',
            ];
        });

        return (new UniversalExport($records, 'كشف_الملفات_الأمنية'))->download('Security_Files_Export.xlsx');
    }

    /**
     * Automatically detect connected scanners (USB / WIA & Network / eSCL).
     */
    public function detectScannerDevices(Request $request)
    {
        // 1. Detect USB / WIA devices
        $scriptPath = storage_path('app/scanner_detect.ps1');
        $wiaDevices = [];

        if (file_exists($scriptPath)) {
            $command = sprintf('powershell -ExecutionPolicy Bypass -File %s', escapeshellarg($scriptPath));
            $output = @shell_exec($command);
            $json = json_decode(trim($output), true);
            $wiaDevices = $json['devices'] ?? [];
        }

        // 2. Detect Network Scanners (eSCL / Mopria)
        // Candidate IPs: explicit query param, cache, 192.168.1.102, request IP, ARP table
        $candidateIps = [];
        if ($reqIp = trim($request->input('ip', ''))) {
            $candidateIps[] = $reqIp;
        }
        if ($cachedIp = \Illuminate\Support\Facades\Cache::get('last_scanner_ip')) {
            $candidateIps[] = $cachedIp;
        }
        $candidateIps[] = '192.168.1.102'; // Known Epson printer IP
        if ($clientIp = $request->ip()) {
            if ($clientIp !== '127.0.0.1' && !in_array($clientIp, $candidateIps)) {
                $candidateIps[] = $clientIp;
            }
        }

        // Add dynamic IPs from ARP table on Windows
        $arpOutput = @shell_exec('arp -a');
        if ($arpOutput && preg_match_all('/(\d+\.\d+\.\d+\.\d+)\s+([0-9a-fA-F-]+)\s+dynamic/', $arpOutput, $m)) {
            foreach ($m[1] as $ip) {
                if (!in_array($ip, $candidateIps) && !str_ends_with($ip, '.255')) {
                    $candidateIps[] = $ip;
                }
            }
        }

        $candidateIps = array_values(array_unique(array_filter($candidateIps)));

        $detectedNetworkScanner = null;
        foreach ($candidateIps as $ip) {
            // Prioritize HTTPS port 443 for eSCL (Epson, Canon, HP require HTTPS for modern eSCL)
            $endpoints = [
                ['proto' => 'https', 'port' => 443],
                ['proto' => 'http',  'port' => 80],
                ['proto' => 'http',  'port' => 8080],
                ['proto' => 'https', 'port' => 8080],
            ];

            foreach ($endpoints as $ep) {
                $ch = curl_init("{$ep['proto']}://{$ip}:{$ep['port']}/eSCL/ScannerCapabilities");
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_TIMEOUT => 2,
                    CURLOPT_CONNECTTIMEOUT => 1
                ]);
                $resp = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($code === 200 && str_contains($resp, 'ScannerCapabilities')) {
                    $model = 'طابعة شبكة (eSCL)';
                    if (preg_match('/<scan:MakeAndModel>([^<]+)<\/scan:MakeAndModel>/', $resp, $mm)) {
                        $model = trim($mm[1]);
                    } elseif (preg_match('/<pwg:MakeAndModel>([^<]+)<\/pwg:MakeAndModel>/', $resp, $mm)) {
                        $model = trim($mm[1]);
                    }

                    // Check status and ADF
                    $statusCh = curl_init("{$ep['proto']}://{$ip}:{$ep['port']}/eSCL/ScannerStatus");
                    curl_setopt_array($statusCh, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_TIMEOUT => 2,
                        CURLOPT_CONNECTTIMEOUT => 1
                    ]);
                    $statusXml = curl_exec($statusCh);
                    curl_close($statusCh);

                    $hasAdfLoaded = str_contains($statusXml, 'ScannerAdfLoaded');

                    $detectedNetworkScanner = [
                        'type' => 'network',
                        'ip' => $ip,
                        'port' => $ep['port'],
                        'protocol' => $ep['proto'],
                        'name' => $model,
                        'display_name' => "طابعة {$model} على الشبكة ({$ip})",
                        'adf_loaded' => $hasAdfLoaded,
                        'status' => 'جاهز للسحب المباشر'
                    ];

                    \Illuminate\Support\Facades\Cache::put('last_scanner_ip', $ip, now()->addDays(30));
                    break 2;
                }
            }
        }

        // 3. Auto-select the active connected device without requiring user intervention
        $autoSelected = null;
        if ($detectedNetworkScanner) {
            $autoSelected = $detectedNetworkScanner;
        } elseif (!empty($wiaDevices)) {
            $dev = $wiaDevices[0];
            $autoSelected = [
                'type' => 'usb',
                'device_id' => $dev['DeviceID'] ?? '',
                'name' => $dev['Name'] ?? 'ماسح ضوئي ويندوز / USB',
                'display_name' => ($dev['Name'] ?? 'ماسح ضوئي USB') . ' (متصل بالكمبيوتر)',
                'status' => 'جاهز للسحب'
            ];
        } else {
            // Default fallback if no device found
            $autoSelected = [
                'type' => 'network',
                'ip' => '192.168.1.102',
                'port' => 443,
                'protocol' => 'https',
                'name' => 'طابعة الشبكة الافتراضية (192.168.1.102)',
                'display_name' => 'طابعة شبكة (192.168.1.102)',
                'status' => 'غير مؤكد - اضغط فحص',
                'unverified' => true
            ];
        }

        return response()->json([
            'success' => true,
            'auto_selected' => $autoSelected,
            'network_scanner' => $detectedNetworkScanner,
            'wia_devices' => $wiaDevices,
            'devices' => $wiaDevices, // backward compatibility
            'count' => count($wiaDevices) + ($detectedNetworkScanner ? 1 : 0),
        ]);
    }

    /**
     * Trigger hardware scan via Windows Image Acquisition (WIA - USB / Local).
     */
    public function scanWia(Request $request)
    {
        $deviceId = $request->input('device_id', '');
        $dpi = (int) $request->input('dpi', 300);
        $colorMode = (int) $request->input('color_mode', 1); // 1: Color, 2: Grayscale, 4: B/W

        $outputDir = storage_path('app/public/scans');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $filename = 'scan_' . date('Ymd_His') . '_' . uniqid() . '.jpg';
        $outputPath = $outputDir . DIRECTORY_SEPARATOR . $filename;
        $scriptPath = storage_path('app/scanner_scan.ps1');

        if (!file_exists($scriptPath)) {
            return response()->json(['success' => false, 'error' => 'ملف سكربت المسح الضوئي غير موجود في النظام.']);
        }

        $command = sprintf(
            'powershell -ExecutionPolicy Bypass -File %s -DeviceId %s -OutputPath %s -Dpi %d -ColorMode %d',
            escapeshellarg($scriptPath),
            escapeshellarg($deviceId),
            escapeshellarg($outputPath),
            $dpi,
            $colorMode
        );

        $output = shell_exec($command);
        $json = json_decode(trim($output), true);

        if ($json && isset($json['success']) && $json['success']) {
            if (file_exists($outputPath)) {
                $imgData = file_get_contents($outputPath);
                $base64 = 'data:image/jpeg;base64,' . base64_encode($imgData);

                return response()->json([
                    'success' => true,
                    'image_url' => asset('storage/scans/' . $filename),
                    'base64' => $base64,
                    'filename' => $filename,
                    'device' => $json['device'] ?: 'الماسح الضوئي الافتراضي',
                    'source' => 'usb'
                ]);
            }
        }

        $errorMsg = 'تعذر تشغيل الماسح الضوئي.';
        if (isset($json['error'])) {
            if ($json['error'] === 'NO_SCANNER_CONNECTED') {
                $errorMsg = 'لم يتم العثور على ماسح ضوئي أو طابعة متصلة عبر USB. يرجى التأكد من تشغيل الطابعة وتوصيل الكابل، أو استخدام خيار المسح عبر الشبكة (IP).';
            } else {
                $errorMsg = 'تنبيه الماسح: ' . $json['error'];
            }
        }

        return response()->json([
            'success' => false,
            'error' => $errorMsg,
            'details' => $json
        ]);
    }

    /**
     * Trigger hardware scan via Network (eSCL / AirScan / Mopria).
     */
    public function scanNetwork(Request $request)
    {
        $ip = trim($request->input('ip'));
        if (!$ip) {
            $ip = \Illuminate\Support\Facades\Cache::get('last_scanner_ip') ?: '192.168.1.102';
        }

        $port = (int) $request->input('port', 0);
        $reqProto = trim($request->input('protocol', ''));
        $dpi = (int) $request->input('dpi', 300);
        if ($dpi < 100 || $dpi > 1200) $dpi = 300;
        
        $colorModeInput = $request->input('color_mode', 'color');
        if (in_array($colorModeInput, ['RGB24', 'Color', 'color'])) {
            $colorMode = 'RGB24';
        } elseif (in_array($colorModeInput, ['Grayscale8', 'gray', 'grayscale'])) {
            $colorMode = 'Grayscale8';
        } elseif (in_array($colorModeInput, ['BlackAndWhite1', 'bw'])) {
            $colorMode = 'BlackAndWhite1';
        } else {
            $colorMode = 'RGB24';
        }

        // Determine working protocol and port by testing eSCL endpoints
        // Epson, Canon, HP and Brother expose eSCL over HTTPS port 443
        $endpointsToTry = [];
        if ($port > 0 && $reqProto) {
            $endpointsToTry[] = ['proto' => $reqProto, 'port' => $port];
        }
        $endpointsToTry[] = ['proto' => 'https', 'port' => 443];
        $endpointsToTry[] = ['proto' => 'http',  'port' => 80];
        $endpointsToTry[] = ['proto' => 'http',  'port' => 8080];
        $endpointsToTry[] = ['proto' => 'https', 'port' => 8080];

        $workingBaseUrl = null;
        $capabilitiesXml = '';

        foreach ($endpointsToTry as $ep) {
            $testUrl = "{$ep['proto']}://{$ip}:{$ep['port']}/eSCL/ScannerCapabilities";
            $ch = curl_init($testUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_CONNECTTIMEOUT => 2
            ]);
            $resp = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($code === 200 && str_contains($resp, 'ScannerCapabilities')) {
                $workingBaseUrl = "{$ep['proto']}://{$ip}:{$ep['port']}";
                $capabilitiesXml = $resp;
                break;
            }
        }

        if (!$workingBaseUrl) {
            return response()->json([
                'success' => false,
                'error' => "تعذر الاتصال بماسح الطابعة على العنوان ({$ip}). يرجى التأكد من تشغيل الطابعة وتواجدها على نفس الشبكة المحلية، ودعمها لبروتوكول eSCL عبر منفذ HTTPS (443)."
            ]);
        }

        \Illuminate\Support\Facades\Cache::put('last_scanner_ip', $ip, now()->addDays(30));

        // Get Scanner Model Name
        $modelName = "طابعة شبكة ({$ip})";
        if (preg_match('/<scan:MakeAndModel>([^<]+)<\/scan:MakeAndModel>/', $capabilitiesXml, $m)) {
            $modelName = trim($m[1]);
        } elseif (preg_match('/<pwg:MakeAndModel>([^<]+)<\/pwg:MakeAndModel>/', $capabilitiesXml, $m)) {
            $modelName = trim($m[1]);
        }

        // Check ScannerStatus and release any stuck/canceled job to avoid 409 Conflict / 503 Busy
        $statusCh = curl_init("{$workingBaseUrl}/eSCL/ScannerStatus");
        curl_setopt_array($statusCh, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_CONNECTTIMEOUT => 2
        ]);
        $statusXml = curl_exec($statusCh);
        curl_close($statusCh);

        // Delete any existing job to free the scanner queue
        if ($statusXml && preg_match_all('/<pwg:JobUri>([^<]+)<\/pwg:JobUri>/', $statusXml, $matches)) {
            foreach ($matches[1] as $jobUri) {
                $delUrl = rtrim($workingBaseUrl, '/') . '/' . ltrim($jobUri, '/');
                $delCh = curl_init($delUrl);
                curl_setopt_array($delCh, [
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_TIMEOUT => 3
                ]);
                @curl_exec($delCh);
                curl_close($delCh);
            }
        }

        // Determine InputSource: Platen (glass) or Adf
        $inputSource = 'Platen';
        $requestedSource = $request->input('input_source', 'auto');
        if ($requestedSource === 'adf') {
            $inputSource = 'Adf';
        } elseif ($requestedSource === 'auto' && str_contains($statusXml, 'ScannerAdfLoaded')) {
            $inputSource = 'Adf';
        }

        // Build valid eSCL ScanSettings XML
        $scanXml = '<?xml version="1.0" encoding="UTF-8"?>
<scan:ScanSettings xmlns:scan="http://schemas.hp.com/imaging/escl/2011/05/03" xmlns:pwg="http://www.pwg.org/schemas/2010/12/sm">
  <pwg:Version>2.6</pwg:Version>
  <scan:Intent>Document</scan:Intent>
  <pwg:ScanRegions>
    <pwg:ScanRegion>
      <pwg:Height>3508</pwg:Height>
      <pwg:Width>2480</pwg:Width>
      <pwg:XOffset>0</pwg:XOffset>
      <pwg:YOffset>0</pwg:YOffset>
    </pwg:ScanRegion>
  </pwg:ScanRegions>
  <pwg:InputSource>' . $inputSource . '</pwg:InputSource>
  <scan:ColorMode>' . htmlspecialchars($colorMode) . '</scan:ColorMode>
  <scan:XResolution>' . $dpi . '</scan:XResolution>
  <scan:YResolution>' . $dpi . '</scan:YResolution>
  <pwg:DocumentFormat>image/jpeg</pwg:DocumentFormat>
</scan:ScanSettings>';

        $ch = curl_init("{$workingBaseUrl}/eSCL/ScanJobs");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $scanXml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/xml',
            'Content-Length: ' . strlen($scanXml)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        curl_close($ch);

        // Fallback: If Adf failed or 409 Conflict, retry with Platen
        if (($httpCode === 409 || $httpCode === 503) && $inputSource === 'Adf') {
            $inputSource = 'Platen';
            $scanXml = str_replace('<pwg:InputSource>Adf</pwg:InputSource>', '<pwg:InputSource>Platen</pwg:InputSource>', $scanXml);
            $ch = curl_init("{$workingBaseUrl}/eSCL/ScanJobs");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $scanXml);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: text/xml',
                'Content-Length: ' . strlen($scanXml)
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($response, 0, $headerSize);
            curl_close($ch);
        }

        if ($httpCode === 201) {
            if (preg_match('/Location:\s*([^\r\n]+)/i', $headers, $matches)) {
                $jobLocation = trim($matches[1]);
                if (!str_starts_with($jobLocation, 'http')) {
                    $jobLocation = rtrim($workingBaseUrl, '/') . '/' . ltrim($jobLocation, '/');
                }

                $nextDocUrl = rtrim($jobLocation, '/') . '/NextDocument';
                
                $imgCh = curl_init($nextDocUrl);
                curl_setopt($imgCh, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($imgCh, CURLOPT_TIMEOUT, 60);
                curl_setopt($imgCh, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($imgCh, CURLOPT_SSL_VERIFYHOST, false);
                $imgData = curl_exec($imgCh);
                $imgCode = curl_getinfo($imgCh, CURLINFO_HTTP_CODE);
                $contentType = curl_getinfo($imgCh, CURLINFO_CONTENT_TYPE);
                curl_close($imgCh);

                // Release scanner job immediately
                $delCh = curl_init($jobLocation);
                curl_setopt_array($delCh, [
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_TIMEOUT => 4
                ]);
                @curl_exec($delCh);
                curl_close($delCh);

                if ($imgCode === 200 && !empty($imgData)) {
                    $outputDir = storage_path('app/public/scans');
                    if (!file_exists($outputDir)) {
                        mkdir($outputDir, 0755, true);
                    }

                    $filename = 'scan_' . date('Ymd_His') . '_' . uniqid() . '.jpg';
                    $filePath = $outputDir . DIRECTORY_SEPARATOR . $filename;
                    file_put_contents($filePath, $imgData);

                    $mime = str_contains($contentType, 'pdf') ? 'application/pdf' : 'image/jpeg';
                    $base64 = 'data:' . $mime . ';base64,' . base64_encode($imgData);

                    return response()->json([
                        'success' => true,
                        'image_url' => asset('storage/scans/' . $filename),
                        'base64' => $base64,
                        'filename' => $filename,
                        'is_pdf' => str_contains($contentType, 'pdf'),
                        'source' => 'network',
                        'device' => "{$modelName} ({$ip})",
                        'input_source' => $inputSource
                    ]);
                }
            }
        }

        $errorHint = '';
        if ($httpCode === 404) {
            $errorHint = 'المنفذ المستهدف لا يقدم خدمة السحب eSCL (رمز 404). يرجى تمكين eSCL عبر منفذ HTTPS 443.';
        } elseif ($httpCode === 503) {
            $errorHint = 'الماسح الضوئي مشغول حالياً بمهمة سابقة (رمز 503). يرجى الانتظار ثوانٍ معدودة والمحاولة مرة أخرى.';
        } elseif ($httpCode === 409) {
            $errorHint = 'تعارض في مدخل الورق أو حالة الطابعة (رمز 409). تأكد من وضع الوثيقة على زجاج الماسح.';
        }

        return response()->json([
            'success' => false,
            'error' => "الطابعة متصلة على ({$ip}) ولكن لم يتم سحب الوثيقة (الاستجابة: {$httpCode}). {$errorHint}"
        ]);
    }

    /**
     * Test connection to a network printer/scanner.
     */
    public function testNetworkScanner(Request $request)
    {
        $ip = trim($request->input('ip'));
        if (!$ip) {
            return response()->json(['success' => false, 'error' => 'يرجى إدخال عنوان IP.']);
        }

        // Prioritize HTTPS 443 then 80, 8080
        $endpoints = [
            ['proto' => 'https', 'port' => 443],
            ['proto' => 'http',  'port' => 80],
            ['proto' => 'http',  'port' => 8080],
            ['proto' => 'https', 'port' => 8080],
        ];

        $esclSupported = false;
        $foundPort = null;
        $foundProto = null;
        $printerModel = "طابعة شبكة ({$ip})";

        foreach ($endpoints as $ep) {
            $ch = curl_init("{$ep['proto']}://{$ip}:{$ep['port']}/eSCL/ScannerCapabilities");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ]);
            $resp = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($code === 200 && str_contains($resp, 'ScannerCapabilities')) {
                $esclSupported = true;
                $foundPort = $ep['port'];
                $foundProto = $ep['proto'];
                if (preg_match('/<scan:MakeAndModel>([^<]+)<\/scan:MakeAndModel>/', $resp, $mm)) {
                    $printerModel = trim($mm[1]);
                } elseif (preg_match('/<pwg:MakeAndModel>([^<]+)<\/pwg:MakeAndModel>/', $resp, $mm)) {
                    $printerModel = trim($mm[1]);
                }
                break;
            }
        }

        if ($esclSupported) {
            \Illuminate\Support\Facades\Cache::put('last_scanner_ip', $ip, now()->addDays(30));
            return response()->json([
                'success' => true,
                'ip' => $ip,
                'port' => $foundPort,
                'protocol' => $foundProto,
                'model' => $printerModel,
                'escl_supported' => true,
                'message' => "تم الاتصال بنجاح! طابعة {$printerModel} جاهزة للسحب المباشر (eSCL عبر {$foundProto})."
            ]);
        }

        // If eSCL failed, check if raw ports are open
        $portsToTry = [80, 443, 631, 9100];
        $openPorts = [];
        foreach ($portsToTry as $p) {
            $fp = @fsockopen($ip, $p, $errno, $errstr, 1.5);
            if ($fp) {
                fclose($fp);
                $openPorts[] = $p;
            }
        }

        if (empty($openPorts)) {
            return response()->json([
                'success' => false,
                'error' => "لا توجد استجابة من الطابعة على العنوان ({$ip}). يرجى التأكد من تشغيلها وتواجدها على نفس الشبكة."
            ]);
        }

        return response()->json([
            'success' => true,
            'ip' => $ip,
            'open_ports' => $openPorts,
            'escl_supported' => false,
            'message' => "الطابعة متصلة على الشبكة (المنافذ المفتوحة: " . implode(', ', $openPorts) . ")، ولكن لم تستجب لبروتوكول eSCL."
        ]);
    }
}
