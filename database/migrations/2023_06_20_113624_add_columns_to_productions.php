<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productions', function (Blueprint $table) {
            // 不良品カウント
            $table->integer('defective_count')->comment(__('pinkieit.defective_count'));
            // ステータス
            $table->tinyInteger('status', false, true)->comment(__('pinkieit.status'));
            // 計画停止中かどうか
            $table->boolean('in_planned_outage')->comment(__('pinkieit.in_planned_outage'));
            // 操業時間
            $table->integer('working_time', false, true)->comment(__('pinkieit.working_time'));
            // 負荷時間
            $table->integer('loading_time', false, true)->comment(__('pinkieit.loading_time'));
            // 稼働時間
            $table->integer('operating_time', false, true)->comment(__('pinkieit.operating_time'));
            // 正味稼働時間
            $table->integer('net_time', false, true)->comment(__('pinkieit.net_time'));
            // チョコ停回数
            $table->integer('breakdown_count', false, true)->comment(__('pinkieit.breakdown_count'));
            // 段取り替え自動復帰回数
            $table->integer('auto_resume_count', false, true)->comment(__('pinkieit.auto_resume_count'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn('defective_count');
            $table->dropColumn('status');
            $table->dropColumn('in_planned_outage');
            $table->dropColumn('working_time');
            $table->dropColumn('loading_time');
            $table->dropColumn('operating_time');
            $table->dropColumn('net_time');
        });
    }
};
