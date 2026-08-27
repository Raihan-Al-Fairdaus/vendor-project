<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Hapus data test lama
        DB::table('billboards')->truncate();

        // 2. Tambah kolom baru
        Schema::table('billboards', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
            $table->string('jenis')->default('billboard')->after('code');
            $table->string('ukuran')->nullable()->after('address');
            $table->string('orientasi')->default('landscape')->after('ukuran');
            $table->string('kepemilikan')->default('DNA Advertising')->after('orientasi');
            $table->unsignedTinyInteger('sisi')->default(1)->after('kepemilikan');
        });

        // 3. Buat name nullable (tidak wajib lagi, diganti code)
        Schema::table('billboards', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('billboards', function (Blueprint $table) {
            $table->dropColumn(['code', 'jenis', 'ukuran', 'orientasi', 'kepemilikan', 'sisi']);
        });
    }
};
