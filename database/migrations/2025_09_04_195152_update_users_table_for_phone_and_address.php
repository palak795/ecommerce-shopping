<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableForPhoneAndAddress extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Make email nullable (if not already)
            if (Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->change();
            }

            // Add unique phone_number
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->unique()->after('name');
            }

            // Add current_address_id foreign key
            if (!Schema::hasColumn('users', 'current_address_id')) {
                $table->unsignedBigInteger('current_address_id')->nullable()->after('phone_number');
                $table->foreign('current_address_id')->references('id')->on('addresses')->onDelete('set null');
            }

            // Add soft deletes (deleted_at column)
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Revert email to not nullable
            if (Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable(false)->change();
            }

            // Remove phone_number
            if (Schema::hasColumn('users', 'phone_number')) {
                $table->dropUnique(['phone_number']);
                $table->dropColumn('phone_number');
            }

            // Remove current_address_id foreign key and column
            if (Schema::hasColumn('users', 'current_address_id')) {
                $table->dropForeign(['current_address_id']);
                $table->dropColumn('current_address_id');
            }

            // Remove soft deletes
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
