<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('curriculums', function (Blueprint $table) {
            $table->dropUnique(['rank_name', 'title']);
            $table->dropColumn('rank_name');
        });
    }

    public function down()
    {
        Schema::table('curriculums', function (Blueprint $table) {
        });
    }
};
