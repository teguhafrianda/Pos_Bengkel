<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1️⃣ Tabel services (header)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')
                  ->constrained('kendaraans')
                  ->cascadeOnDelete();
            $table->foreignId('teknisi_id')
                  ->constrained('teknisis')
                  ->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('jenis_service');
            $table->text('keluhan')->nullable();
            
            // --- KOLOM BARU ---
            $table->enum('status_servis', ['Proses', 'Selesai'])->default('Proses');
            $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');
            // ------------------

            $table->decimal('total_jasa', 15, 2)->default(0);
            $table->decimal('total_sparepart', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->timestamps();
        });

        // 2️⃣ Tabel service_items (detail jasa)
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
                  ->constrained('services')
                  ->cascadeOnDelete();
            $table->string('nama_jasa');
            $table->decimal('harga', 15, 2);
            $table->timestamps();
        });

        // 3️⃣ Tabel service_spareparts (detail sparepart)
        Schema::create('service_spareparts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();
            $table->foreignId('sparepart_id')
                ->constrained('spareparts')
                ->cascadeOnDelete();

            $table->integer('qty')->default(1);
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('harga', 15, 2); // subtotal (qty x harga_satuan)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_spareparts');
        Schema::dropIfExists('service_items');
        Schema::dropIfExists('services');
    }
};