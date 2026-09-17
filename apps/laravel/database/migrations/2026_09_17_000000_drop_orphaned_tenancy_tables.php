<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('roles_and_permissions_tables');
        Schema::dropIfExists('recurring_documents');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // These tables belonged to the removed tenancy/roles feature and are not restored.
    }
};
