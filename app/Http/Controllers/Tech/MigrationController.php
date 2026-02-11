<?php

namespace App\Http\Controllers\Tech;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class MigrationController extends Controller
{
    public function index(): View
    {
        $migrationFiles = $this->migrationFiles();
        $ranMigrations = $this->ranMigrations();
        $pendingMigrations = $migrationFiles->diff($ranMigrations->keys())->values();

        return view('tech.migrations.index', [
            'dbDefaultConnection' => config('database.default'),
            'dbName' => DB::connection()->getDatabaseName(),
            'databaseSummary' => $this->databaseSummary(),
            'tableStatus' => $this->tableStatus(),
            'migrationFiles' => $migrationFiles,
            'ranMigrations' => $ranMigrations,
            'pendingMigrations' => $pendingMigrations,
            'lastMigrateOutput' => session('last_migrate_output'),
            'lastMigrateStatus' => session('last_migrate_status'),
        ]);
    }

    public function runPending(Request $request): RedirectResponse
    {
        $pendingBefore = $this->migrationFiles()->diff($this->ranMigrations()->keys())->values();

        if ($pendingBefore->isEmpty()) {
            return back()->with('status', 'Nu exista migrari pending.');
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = trim(Artisan::output());

            $pendingAfter = $this->migrationFiles()->diff($this->ranMigrations()->keys())->values();
            $ranCount = max(0, $pendingBefore->count() - $pendingAfter->count());

            return redirect()->route('tech.migrations.index')->with([
                'status' => 'Migrate finalizat. Migrari rulate: ' . $ranCount . '.',
                'last_migrate_status' => 'success',
                'last_migrate_output' => $output,
            ]);
        } catch (Throwable $throwable) {
            return redirect()->route('tech.migrations.index')->with([
                'error' => 'Eroare la rularea migrarilor: ' . $throwable->getMessage(),
                'last_migrate_status' => 'failed',
                'last_migrate_output' => (string) $throwable,
            ]);
        }
    }

    protected function migrationFiles(): Collection
    {
        return collect(File::files(database_path('migrations')))
            ->map(function ($file) {
                return Str::replaceLast('.php', '', $file->getFilename());
            })
            ->sort()
            ->values();
    }

    protected function ranMigrations(): Collection
    {
        if (!DB::getSchemaBuilder()->hasTable('migrations')) {
            return collect();
        }

        return DB::table('migrations')
            ->select(['migration', 'batch'])
            ->orderBy('batch')
            ->orderBy('migration')
            ->get()
            ->keyBy('migration');
    }

    protected function databaseSummary(): array
    {
        $tableCount = 0;
        $columnCount = 0;

        try {
            $databaseName = DB::connection()->getDatabaseName();
            $tableCount = (int) DB::table('information_schema.tables')
                ->where('table_schema', $databaseName)
                ->count();

            $columnCount = (int) DB::table('information_schema.columns')
                ->where('table_schema', $databaseName)
                ->count();
        } catch (Throwable $throwable) {
            // If INFORMATION_SCHEMA is not accessible, keep default counters.
        }

        return [
            'table_count' => $tableCount,
            'column_count' => $columnCount,
        ];
    }

    protected function tableStatus(): array
    {
        try {
            return collect(DB::select('SHOW TABLE STATUS'))
                ->map(function ($row) {
                    return [
                        'name' => $row->Name,
                        'engine' => $row->Engine,
                        'rows' => $row->Rows,
                        'collation' => $row->Collation,
                        'updated_at' => $row->Update_time,
                    ];
                })
                ->sortBy('name')
                ->values()
                ->all();
        } catch (Throwable $throwable) {
            return [];
        }
    }
}
