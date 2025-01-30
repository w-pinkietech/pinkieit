<?php

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
        Schema::create('producers', function (Blueprint $table) {
            // 生産者ID
            $table->id('producer_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.producer')]));
            // 作業者ID
            $table->unsignedBigInteger('worker_id')->nullable()->comment(__('pinkieit.target_id', ['target' => __('pinkieit.worker')]));
            // 生産ラインID
            $table->unsignedBigInteger('production_line_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.production_line')]));
            // 識別番号
            $table->string('identification_number', 32)->comment(__('pinkieit.identification_number'));
            // 作業者名
            $table->string('worker_name', 32)->comment(__('pinkieit.target_name', ['target' => __('pinkieit.worker')]));
            // 開始
            $table->dateTime('start')->default(DB::raw('CURRENT_TIMESTAMP'))->comment(__('pinkieit.start'));
            // 停止
            $table->dateTime('stop')->index()->nullable()->comment(__('pinkieit.stop'));
            // タイムスタンプ
            $table->timestamps();
            // 外部キー
            $table->foreign('worker_id')->references('worker_id')->on('workers')->nullOnDelete();
            $table->foreign('production_line_id')->references('production_line_id')->on('production_lines')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producers');
    }
};
