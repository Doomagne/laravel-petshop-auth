<?php

use App\Models\Dog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasFavoritableType = Schema::hasColumn('favorites', 'favoritable_type');
        $hasFavoritableId = Schema::hasColumn('favorites', 'favoritable_id');
        $hasDogId = Schema::hasColumn('favorites', 'dog_id');

        // 1) Add polymorphic columns (nullable for safe transition)
        if (! $hasFavoritableType || ! $hasFavoritableId) {
            Schema::table('favorites', function (Blueprint $table) use ($hasFavoritableType, $hasFavoritableId) {
                if (! $hasFavoritableType) {
                    $table->string('favoritable_type')->nullable()->after('user_id');
                }
                if (! $hasFavoritableId) {
                    $table->unsignedBigInteger('favoritable_id')->nullable()->after('favoritable_type');
                }
            });
        }

        // 2) Backfill existing dog favorites into polymorphic columns
        if ($hasDogId) {
            DB::table('favorites')
                ->whereNull('favoritable_type')
                ->whereNotNull('dog_id')
                ->update([
                    'favoritable_type' => Dog::class,
                    'favoritable_id' => DB::raw('dog_id'),
                ]);
        }

        // 3) Drop old constraints/column (order matters on MySQL: drop FK first, then index, then column)
        if ($hasDogId) {
            // Drop FK first (must only run if FK exists; Schema builder can't "try/catch" this safely on MySQL)
            $fk = DB::selectOne("
                SELECT CONSTRAINT_NAME AS name
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'favorites'
                  AND COLUMN_NAME = 'dog_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
                LIMIT 1
            ");
            if ($fk && !empty($fk->name)) {
                DB::statement("ALTER TABLE `favorites` DROP FOREIGN KEY `{$fk->name}`");
            }

            // Drop unique index next (only if exists)
            try { DB::statement("ALTER TABLE `favorites` DROP INDEX `favorites_user_id_dog_id_unique`"); } catch (\Throwable $e) {}
            try { DB::statement("ALTER TABLE `favorites` DROP INDEX `favorites_dog_id_foreign`"); } catch (\Throwable $e) {}

            // Finally drop the column
            Schema::table('favorites', function (Blueprint $table) {
                try { $table->dropColumn('dog_id'); } catch (\Throwable $e) {}
            });
        }

        // 4) Add new unique index (user + item)
        Schema::table('favorites', function (Blueprint $table) {
            // If any records somehow didn't backfill, keep nullable to avoid migration failure,
            // but in normal use these columns will be set.
            try { $table->unique(['user_id', 'favoritable_type', 'favoritable_id']); } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        // Recreate dog_id for rollback (dog-only favorites)
        Schema::table('favorites', function (Blueprint $table) {
            if (!Schema::hasColumn('favorites', 'dog_id')) {
                $table->unsignedBigInteger('dog_id')->nullable()->after('user_id');
            }
        });

        DB::table('favorites')
            ->where('favoritable_type', Dog::class)
            ->update(['dog_id' => DB::raw('favoritable_id')]);

        Schema::table('favorites', function (Blueprint $table) {
            try { $table->dropUnique(['user_id', 'favoritable_type', 'favoritable_id']); } catch (\Throwable $e) {}
            if (Schema::hasColumn('favorites', 'favoritable_type')) {
                try { $table->dropColumn('favoritable_type'); } catch (\Throwable $e) {}
            }
            if (Schema::hasColumn('favorites', 'favoritable_id')) {
                try { $table->dropColumn('favoritable_id'); } catch (\Throwable $e) {}
            }
        });
    }
};


