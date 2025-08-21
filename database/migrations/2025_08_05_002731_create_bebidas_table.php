<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bebidas')) {
            Schema::create('bebidas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 120)->unique();
                $table->decimal('precio_venta', 10, 2)->default(0);
                $table->decimal('volumen_litros', 8, 3)->default(0);
                $table->decimal('stock', 10, 3)->default(0);
                $table->timestamps();
            });
        } else {
            Schema::table('bebidas', function (Blueprint $table) {
                if (!Schema::hasColumn('bebidas', 'nombre')) {
                    $table->string('nombre', 120)->unique()->after('id');
                }
                if (!Schema::hasColumn('bebidas', 'precio_venta')) {
                    $table->decimal('precio_venta', 10, 2)->default(0)->after('nombre');
                }
                if (!Schema::hasColumn('bebidas', 'volumen_litros')) {
                    $table->decimal('volumen_litros', 8, 3)->default(0)->after('precio_venta');
                }
                if (!Schema::hasColumn('bebidas', 'stock')) {
                    $table->decimal('stock', 10, 3)->default(0)->after('volumen_litros');
                }
                if (!Schema::hasColumn('bebidas', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        // si querés poder revertir totalmente:
        // Schema::dropIfExists('bebidas');
    }
};
