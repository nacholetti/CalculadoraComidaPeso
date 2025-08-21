<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ingredientes')) {
            Schema::create('ingredientes', function (Blueprint $t) {
                $t->id();
                $t->string('nombre', 120)->unique();
                $t->enum('unidad', ['kg','gramo','litro','unidad'])->default('kg');
                $t->decimal('costo_unitario', 10, 3)->default(0);
                $t->decimal('stock', 10, 3)->default(0);
                $t->timestamps();
            });
        } else {
            Schema::table('ingredientes', function (Blueprint $t) {
                if (!Schema::hasColumn('ingredientes', 'nombre')) {
                    $t->string('nombre', 120)->unique()->after('id');
                }
                if (!Schema::hasColumn('ingredientes', 'unidad')) {
                    $t->enum('unidad', ['kg','gramo','litro','unidad'])->default('kg')->after('nombre');
                }
                if (!Schema::hasColumn('ingredientes', 'costo_unitario')) {
                    $t->decimal('costo_unitario', 10, 3)->default(0)->after('unidad');
                }
                if (!Schema::hasColumn('ingredientes', 'stock')) {
                    $t->decimal('stock', 10, 3)->default(0)->after('costo_unitario');
                }
                if (!Schema::hasColumn('ingredientes', 'created_at')) {
                    $t->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        // Si querés poder revertir por completo:
        // Schema::dropIfExists('ingredientes');
    }
};
