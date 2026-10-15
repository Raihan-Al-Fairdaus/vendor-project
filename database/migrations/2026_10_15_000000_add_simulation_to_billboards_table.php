<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE billboards ADD COLUMN IF NOT EXISTS simulation_path TEXT NULL;');
        DB::statement('ALTER TABLE billboards ADD COLUMN IF NOT EXISTS simulation_type VARCHAR(20) NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE billboards DROP COLUMN IF EXISTS simulation_path;');
        DB::statement('ALTER TABLE billboards DROP COLUMN IF EXISTS simulation_type;');
    }
};
