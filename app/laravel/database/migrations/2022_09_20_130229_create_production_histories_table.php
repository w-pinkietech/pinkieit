<?php

use App\Enums\ProductionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('production_histories', function (Blueprint $table) {
            // 生産履歴ID
            $table->id('production_history_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.production_history')]));
            // 工程ID
            $table->unsignedBigInteger('process_id')->nullable()->comment(__('pinkieit.target_id', ['target' => __('pinkieit.process')]));
            // 工程名
            $table->string('process_name', 32)->comment(__('pinkieit.target_name', ['target' => __('pinkieit.process')]));
            // 品番ID
            $table->unsignedBigInteger('part_number_id')->nullable()->comment(__('pinkieit.target_id', ['target' => __('pinkieit.part_number')]));
            // 計画値色
            $table->char('plan_color', 7)->comment(__('pinkieit.plan_color'));
            // 品番名
            $table->string('part_number_name', 32)->comment(__('pinkieit.target_name', ['target' => __('pinkieit.part_number')]));
            // サイクルタイム
            $table->unsignedFloat('cycle_time', 8, 3)->comment(__('pinkieit.cycle_time'));
            // オーバータイム
            $table->unsignedFloat('over_time', 8, 3)->comment(__('pinkieit.over_time'));
            // 目標値
            $table->integer('goal')->nullable()->comment(__('pinkieit.goal'));
            // 開始
            $table->dateTime('start')->default(DB::raw('CURRENT_TIMESTAMP'))->comment(__('pinkieit.start'));
            // 停止
            $table->dateTime('stop')->nullable()->comment(__('pinkieit.stop'));
            // ステータス
            $table->tinyInteger('status', false, true)->comment(__('pinkieit.status'));
            // タイムスタンプ
            $table->timestamps();
            // 外部キー
            $table->foreign('process_id')->references('process_id')->on('processes')->cascadeOnDelete();
            $table->foreign('part_number_id')->references('part_number_id')->on('part_numbers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_histories');
    }
};
