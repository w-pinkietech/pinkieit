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
        Schema::create('on_off_events', function (Blueprint $table) {
            // イベントID
            $table->id('on_off_event_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.event')]));
            // 工程ID
            $table->unsignedBigInteger('process_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.process')]));
            // メッセージID
            $table->unsignedBigInteger('on_off_id')->comment(__('pinkieit.target_id', ['target' => 'ON-OFF']));
            // イベント名
            $table->string('event_name', 64)->comment(__('pinkieit.target_name', ['target' => __('pinkieit.event')]));
            // メッセージ
            $table->string('message', 64)->nullable()->comment(__('pinkieit.message'));
            // ON-OFF
            $table->boolean('on_off')->comment('ON-OFF');
            // ピン番号
            $table->tinyInteger('pin_number', false, true)->comment(__('pinkieit.pin_number'));
            // タイムスタンプ
            $table->timestamp('at', 3)->index()->default(DB::raw('CURRENT_TIMESTAMP(3)'));
            // 外部キー
            $table->foreign('process_id')->references('process_id')->on('processes')->cascadeOnDelete();
            $table->foreign('on_off_id')->references('on_off_id')->on('on_offs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('on_off_events');
    }
};
