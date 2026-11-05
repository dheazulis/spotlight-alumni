<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Throwable;

class DatabaseRestoreController extends Controller
{
    /**
     * Menampilkan daftar file backup ZIP.
     */
    public function index()
    {
        $backupFolder = config('backup.backup.name');

        $backups = Storage::disk('local')->files($backupFolder);

        $backups = array_values(array_filter($backups, function ($file) {
            return strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'zip';
        }));

        return view('admin.database-restore', compact('backups'));
    }

    /**
     * Memulihkan database dari file backup ZIP.
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup' => ['required', 'string'],
        ]);

        $disk = Storage::disk('local');
        $backupFolder = config('backup.backup.name');

        $availableBackups = $disk->files($backupFolder);

        // Memastikan file yang dipilih benar-benar tersedia.
        if (!in_array($request->backup, $availableBackups, true)) {
            return back()->with(
                'error',
                'File backup tidak valid atau tidak ditemukan.'
            );
        }

        $backupPath = $request->backup;

        if (strtolower(pathinfo($backupPath, PATHINFO_EXTENSION)) !== 'zip') {
            return back()->with('error', 'Format file backup harus ZIP.');
        }

        $zipPath = $disk->path($backupPath);

        if (!file_exists($zipPath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        try {
            /*
             * 1. Memeriksa keberadaan backup pengaman.
             */
            $safetyBackups = array_filter(
                $availableBackups,
                function ($file) {
                    return str_starts_with(
                        basename($file),
                        'backup-pengaman-'
                    ) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'zip';
                }
            );

            if (empty($safetyBackups)) {
                return back()->with(
                    'error',
                    'Backup pengaman belum tersedia. Restore dibatalkan.'
                );
            }

            // Mengambil backup pengaman terbaru.
            usort($safetyBackups, function ($a, $b) use ($disk) {
                return $disk->lastModified($b)
                    <=> $disk->lastModified($a);
            });

            /*
             * 2. Memastikan backup pengaman memiliki file SQL.
             */
            $safetyZip = new ZipArchive();

            if ($safetyZip->open($disk->path($safetyBackups[0])) !== true) {
                return back()->with(
                    'error',
                    'File backup pengaman tidak dapat dibuka.'
                );
            }

            $safetySqlFound = false;

            for ($i = 0; $i < $safetyZip->numFiles; $i++) {
                $entry = $safetyZip->getNameIndex($i);

                if (
                    $entry &&
                    strtolower(pathinfo($entry, PATHINFO_EXTENSION)) === 'sql'
                ) {
                    $safetySql = $safetyZip->getFromIndex($i);

                    if (
                        is_string($safetySql) &&
                        trim($safetySql) !== ''
                    ) {
                        $safetySqlFound = true;
                    }

                    break;
                }
            }

            $safetyZip->close();

            if (!$safetySqlFound) {
                return back()->with(
                    'error',
                    'Isi backup pengaman tidak valid. Restore dibatalkan.'
                );
            }

            /*
             * 3. Membaca file SQL dari backup yang dipilih.
             */
            $zip = new ZipArchive();

            if ($zip->open($zipPath) !== true) {
                return back()->with(
                    'error',
                    'File ZIP tidak dapat dibuka.'
                );
            }

            $sqlContent = null;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);

                if (
                    $entry &&
                    strtolower(pathinfo($entry, PATHINFO_EXTENSION)) === 'sql'
                ) {
                    $sqlContent = $zip->getFromIndex($i);
                    break;
                }
            }

            $zip->close();

            if (
                !is_string($sqlContent) ||
                trim($sqlContent) === ''
            ) {
                return back()->with(
                    'error',
                    'File SQL tidak ditemukan atau kosong.'
                );
            }

            /*
             * 4. Mengambil konfigurasi database.
             */
            $databaseConfig = config('database.connections.mysql');

            $database = $databaseConfig['database'] ?? null;

            if (!$database) {
                return back()->with(
                    'error',
                    'Konfigurasi database tidak ditemukan.'
                );
            }

            /*
             * 5. Membuat koneksi langsung menggunakan mysqli.
             */
            $mysqli = new \mysqli(
                $databaseConfig['host'] ?? '127.0.0.1',
                $databaseConfig['username'] ?? 'root',
                $databaseConfig['password'] ?? '',
                $database,
                (int) ($databaseConfig['port'] ?? 3307)
            );

            if ($mysqli->connect_error) {
                throw new \RuntimeException(
                    'Koneksi database gagal: ' . $mysqli->connect_error
                );
            }

            $mysqli->set_charset('utf8mb4');

            /*
             * 6. Menjalankan SQL untuk memulihkan database.
             */
            if (!$mysqli->multi_query($sqlContent)) {
                $error = $mysqli->error;
                $mysqli->close();

                throw new \RuntimeException(
                    'Restore gagal: ' . $error
                );
            }

            /*
             * 7. Memastikan semua perintah SQL selesai.
             */
            do {
                if ($result = $mysqli->store_result()) {
                    $result->free();
                }

                if ($mysqli->errno) {
                    $error = $mysqli->error;
                    $mysqli->close();

                    throw new \RuntimeException(
                        'Restore gagal: ' . $error
                    );
                }
            } while (
                $mysqli->more_results() &&
                $mysqli->next_result()
            );

            if ($mysqli->errno) {
                $error = $mysqli->error;
                $mysqli->close();

                throw new \RuntimeException(
                    'Restore gagal: ' . $error
                );
            }

            $mysqli->close();

            /*
             * 8. Menampilkan pesan berhasil.
             */
            return redirect()
                ->route('admin.database-restore.index')
                ->with(
                    'success',
                    'Database berhasil dipulihkan dari file backup.'
                );

        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Terjadi kesalahan saat restore: ' . $e->getMessage()
            );
        }
    }
}
