<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SystemBackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole('super-admin')) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('system.backup.index');
    }

    public function print()
    {
        $tables = DB::select('SHOW TABLE STATUS');
        $databaseName = config('database.connections.mysql.database');

        return view('system.backup.print', compact('tables', 'databaseName'));
    }

    public function export()
    {
        $databaseName = config('database.connections.mysql.database');
        $filename = "backup-" . $databaseName . "-" . date('Y-m-d-H-i-s') . ".sql.enc";

        // Generate full SQL string
        $sql = "SET FOREIGN_KEY_CHECKS=0;\n\n";

        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . config('database.connections.mysql.database');

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            // Use CREATE TABLE IF NOT EXISTS to avoid errors but preserve existing tables
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
            $createTable = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $createTable);
            $sql .= $createTable . ";\n\n";

            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $row = (array)$row;
                $keys = array_keys($row);
                $values = array_values($row);
                
                $escapedValues = array_map(function($value) {
                    if ($value === null) return 'NULL';
                    return "'" . addslashes($value) . "'";
                }, $values);

                // Build ON DUPLICATE KEY UPDATE part
                $updateParts = [];
                foreach ($keys as $key) {
                    $updateParts[] = "`$key` = VALUES(`$key`)";
                }

                $sql .= "INSERT INTO `$tableName` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $escapedValues) . ") ON DUPLICATE KEY UPDATE " . implode(", ", $updateParts) . ";\n";
            }
            $sql .= "\n\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Encrypt the SQL
        try {
            $encryptedSql = encrypt($sql);
            
            return response($encryptedSql)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل تشفير البيانات: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file',
        ]);

        $file = $request->file('backup_file');
        $encryptedContent = file_get_contents($file->getRealPath());

        try {
            // Decrypt the content
            try {
                $sql = decrypt($encryptedContent);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'فشل فك تشفير الملف. تأكد من أن الملف صحيح ومن مصدر موثوق.');
            }

            DB::unprepared("SET FOREIGN_KEY_CHECKS=0;");
            DB::unprepared($sql);
            DB::unprepared("SET FOREIGN_KEY_CHECKS=1;");

            return redirect()->back()->with('success', 'تم فك التشفير واستعادة النظام بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }
}
