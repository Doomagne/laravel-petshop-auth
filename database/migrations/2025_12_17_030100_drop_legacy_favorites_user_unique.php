<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Some MySQL versions can leave behind a legacy unique index name after dropping dog_id,
        // resulting in a UNIQUE(user_id) constraint with name favorites_user_id_dog_id_unique.
        // That breaks favorites because a user can only have 1 favorite total.

        $idx = DB::selectOne("
            SELECT INDEX_NAME AS name
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'favorites'
              AND INDEX_NAME = 'favorites_user_id_dog_id_unique'
            LIMIT 1
        ");

        if ($idx && !empty($idx->name)) {
            DB::statement("ALTER TABLE `favorites` DROP INDEX `favorites_user_id_dog_id_unique`");
        }
    }

    public function down(): void
    {
        // No-op: we don't want to reintroduce the broken constraint.
    }
};


