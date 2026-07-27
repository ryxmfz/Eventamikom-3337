<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role: superadmin, organizer, buyer
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['superadmin', 'organizer', 'buyer'])->default('buyer')->after('email');
            }
            // Status Kelayakan Organisasi: pending, approved, rejected
            if (!Schema::hasColumn('users', 'organizer_status')) {
                $table->enum('organizer_status', ['pending', 'approved', 'rejected'])->default('approved')->after('role');
            }
            // Nama Organisasi/HIMA
            if (!Schema::hasColumn('users', 'organization_name')) {
                $table->string('organization_name')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'organizer_status', 'organization_name']);
        });
    }
};
