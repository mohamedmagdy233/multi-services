<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ResetMigration extends Command
{
    protected $signature = 'migration:reset {model}';
    protected $description = 'Rollback, delete, and recreate migration by model name';

    public function handle()
    {
        $modelName = $this->argument('model');
        $tableName = Str::plural(Str::snake($modelName)); // تحويل اسم الموديل لاسم الجدول

        // البحث عن المايجريشن الخاص بالموديل
        $migration = DB::table('migrations')->where('migration', 'like', "%{$tableName}%")->first();

        if (!$migration) {
            $this->error("No migration found for model: $modelName (Table: $tableName)");
            return;
        }

        $migrationName = $migration->migration;

        // تشغيل rollback لهذا المايجريشن فقط
        Artisan::call('migrate:rollback', ['--step' => 1]);
        $this->info("Rolled back: $migrationName");

        // حذف السجل من جدول migrations
        DB::table('migrations')->where('migration', $migrationName)->delete();
        $this->info("Deleted migration entry from database: $migrationName");

        // حذف ملف المايجريشن الفعلي
        $migrationFile = glob(database_path("migrations/*{$migrationName}.php"));

        if (!empty($migrationFile)) {
            File::delete($migrationFile);
            $this->info("Deleted migration file: " . basename($migrationFile[0]));
        }

        // إعادة إنشاء المايجريشن
        Artisan::call("make:migration create_{$tableName}_table --create={$tableName}");
        $this->info("Recreated migration for model: $modelName (Table: $tableName)");

        // تشغيل المايجريشن الجديد
        Artisan::call('migrate');
        $this->info("Migration for model '{$modelName}' has been reset and re-run.");
    }
}
