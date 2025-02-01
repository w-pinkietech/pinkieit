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
        Schema::create('production_planned_outages', function (Blueprint $table) {
            // 生産計画停止時間ID
            $table->id('production_planned_outage_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.production_planned_outage')]));
            // 生産履歴ID
            $table->unsignedBigInteger('production_history_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.production_history')]));
            // 計画停止時間名
            $table->string('planned_outage_name', 32)->comment(__('pinkieit.target_name', ['target' => __('pinkieit.planned_outage')]));
            // 開始時間
            $table->time('start_time')->comment(__('pinkieit.start_time'));
            // 終了時間
            $table->time('end_time')->comment(__('pinkieit.end_time'));
            // タイムスタンプ
            $table->timestamps();
            // 外部キー
            $table->foreign('production_history_id')->references('production_history_id')->on('production_histories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_planned_outages');
    }
};
