<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('vendors', 'office_photos')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->json('office_photos')->nullable()->after('id_card_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('vendors', 'office_photos')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropColumn('office_photos');
            });
        }
    }
};