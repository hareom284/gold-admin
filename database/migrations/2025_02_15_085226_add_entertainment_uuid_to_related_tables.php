<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = [
            'entertainment_country_mapping',
            'entertainment_downloads',
            'entertainment_download_mapping',
            'entertainment_gener_mapping',
            'entertainment_stream_content_mapping',
            'entertainment_tag_mapping',
            'entertainment_talent_mapping',
            'entertainment_views',
            'seasons',
            'episodes',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->char('entertainment_uuid', 36)->nullable()->after('entertainment_id');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'entertainment_country_mapping',
            'entertainment_downloads',
            'entertainment_download_mapping',
            'entertainment_gener_mapping',
            'entertainment_stream_content_mapping',
            'entertainment_tag_mapping',
            'entertainment_talent_mapping',
            'entertainment_views',
            'seasons',
            'episodes',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('entertainment_uuid');
            });
        }
    }
};


