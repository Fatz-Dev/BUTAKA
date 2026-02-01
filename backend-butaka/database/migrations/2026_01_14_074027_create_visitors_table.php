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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nama pengunjung
            $table->string('phone', 20)->nullable(); 
            $table->string('email')->nullable();
            $table->string('purpose')->nullable(); // tujuan kunjungan
            $table->string('host_name')->nullable(); // nama yang ingin dikunjungi
            $table->string('institution')->nullable(); // pengunjung dari instansi atau lembaga mana
            $table->enum('status', ['menunggu', 'berkunjung', 'selesai'])->default('menunggu');
            $table->timestamp('check_in_time')->useCurrent();
            $table->timestamp('check_out_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
