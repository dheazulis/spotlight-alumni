<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DatabaseBackupController extends Controller
{
    /**
     * Menampilkan daftar backup ZIP.
     */
    public function index()
    {
        $backupPath = config('backup.backup.name');

        $files = Storage::disk('local')->files($backupPath);

        $backups = collect($files)
            ->filter(fn ($file) => strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'zip')
            ->map(fn ($file) => [
                'name' => basename($file),
                'path' => $file,
                'size' => Storage::disk('local')->size($file),
                'date' => Storage::disk('local')->lastModified($file),
            ])
            ->sortByDesc('date')
            ->values();

        return view('admin.database-backup.index', compact('backups'));
    }

    /**
     * Membuat backup database tanpa menjalankan mysqldump.
     */
    public function backup()
    {
        $disk = Storage::disk('local');
        $backupFolder = config('backup.backup.name');

        $timestamp = now()->format('Y-m-d-H-i-s');
        $filename = $timestamp . '.zip';

        $tempSql = storage_path('app/' . $timestamp . '.sql');
        $tempZip = storage_path('app/' . $timestamp . '.zip');

        try {
            $pdo = DB::connection()->getPdo();

            $tables = DB::select(
                "SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'"
            );

            $sql = "-- Database backup\n";
            $sql .= "-- Database: " . DB::connection()->getDatabaseName() . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];

                $create = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTable = array_values((array) $create[0])[1];

                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable . ";\n\n";

                $rows = DB::table($tableName)->get();

                foreach ($rows as $row) {
                    $values = [];

                    foreach ((array) $row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = $pdo->quote((string) $value);
                        }
                    }

                    $sql .= "INSERT INTO `{$tableName}` VALUES ("
                        . implode(', ', $values)
                        . ");\n";
                }

                $sql .= "\n";
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            file_put_contents($tempSql, $sql);

            $zip = new ZipArchive();

            if ($zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('File ZIP tidak dapat dibuat.');
            }

            $zip->addFile(
                $tempSql,
                'db-dumps/mysql-' . DB::connection()->getDatabaseName() . '.sql'
            );

            $zip->close();

            $disk->put(
                $backupFolder . '/' . $filename,
                file_get_contents($tempZip)
            );

            return back()->with(
                'status',
                'Backup database berhasil dibuat.'
            );

        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'backup' => 'Backup gagal dibuat: ' . $e->getMessage(),
            ]);

        } finally {
            if (file_exists($tempSql)) {
                unlink($tempSql);
            }

            if (file_exists($tempZip)) {
                unlink($tempZip);
            }
        }
    }

    /**
     * Mengunduh file backup ZIP.
     */
    public function download($filename)
    {
        abort_unless(
            preg_match('/^[a-zA-Z0-9_-]+\.zip$/', $filename),
            404
        );

        $backupPath = config('backup.backup.name') . '/' . $filename;

        abort_unless(
            Storage::disk('local')->exists($backupPath),
            404
        );

        return Storage::disk('local')->download($backupPath);
    }
}