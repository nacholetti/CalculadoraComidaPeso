<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('comidas')) {
            Schema::create('comidas', function (Blueprint $t) {
                $t->id();
                $t->string('nombre', 120)->unique();
                $t->decimal('precio_venta_kg', 10, 2)->default(0);
                $t->timestamps();
            });
        } else {
            Schema::table('comidas', function (Blueprint $t) {
                if (!Schema::hasColumn('comidas', 'nombre')) {
                    $t->string('nombre', 120)->unique()->after('id');
                }
                if (!Schema::hasColumn('comidas', 'precio_venta_kg')) {
                    $t->decimal('precio_venta_kg', 10, 2)->default(0)->after('nombre');
                }
                if (!Schema::hasColumn('comidas', 'created_at')) {
                    $t->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        // Schema::dropIfExists('comidas');
    }
};
