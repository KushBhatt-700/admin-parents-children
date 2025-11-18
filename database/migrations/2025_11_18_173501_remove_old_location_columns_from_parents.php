<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('parents', function (Blueprint $table) {
            if (Schema::hasColumn('parents', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('parents', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('parents', 'city')) {
                $table->dropColumn('city');
            }
        });
    }

    public function down(): void {
        Schema::table('parents', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
        });
    }
};