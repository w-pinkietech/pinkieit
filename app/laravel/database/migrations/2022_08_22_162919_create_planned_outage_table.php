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
        Schema::create('planned_outages', function (Blueprint $table) {
            // 計画停止時間ID
            $table->id('planned_outage_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.planned_outage')]));
            // 計画停止時間名
            $table->string('planned_outage_name', 32)->unique()->index()->comment(__('pinkieit.target_name', ['target' => __('pinkieit.planned_outage')]));
            // 開始時間
            $table->time('start_time')->comment(__('pinkieit.start_time'));
            // 終了時間
            $table->time('end_time')->comment(__('pinkieit.end_time'));
            // タイムスタンプ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planned_outages');
    }
};
