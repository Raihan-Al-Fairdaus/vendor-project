<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Hanya tambahkan kolom map_link jika belum ada
        if (!Schema::hasColumn('billboards', 'map_link')) {
            Schema::table('billboards', function (Blueprint $table) {
                $table->text('map_link')->nullable()->after('address');
            });
        }

        // Hapus kolom latitude/longitude jika masih ada
        if (Schema::hasColumn('billboards', 'latitude')) {
            Schema::table('billboards', function (Blueprint $table) {
                $table->dropColumn('latitude');
            });
        }

        if (Schema::hasColumn('billboards', 'longitude')) {
            Schema::table('billboards', function (Blueprint $table) {
                $table->dropColumn('longitude');
            });
        }
    }

    public function down(): void
    {
        Schema::table('billboards', function (Blueprint $table) {
            $table->dropColumnIfExists('map_link');
        });
    }
};
