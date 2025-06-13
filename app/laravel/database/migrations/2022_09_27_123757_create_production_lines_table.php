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
        Schema::create('production_lines', function (Blueprint $table) {
            // 生産ラインID
            $table->id('production_line_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.production_line')]));
            // 生産履歴ID
            $table->unsignedBigInteger('production_history_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.production_history')]));
            // ラインID
            $table->unsignedBigInteger('line_id')->nullable()->comment(__('pinkieit.target_id', ['target' => __('pinkieit.line')]));
            // ライン名
            $table->string('line_name', 32)->comment(__('pinkieit.target_name', ['target' => __('pinkieit.line')]));
            // チャートカラー
            $table->char('chart_color', 7)->comment(__('pinkieit.chart_color'));
            // IPアドレス
            $table->ipAddress()->comment(__('pinkieit.ip_address'));
            // ピン番号
            $table->tinyInteger('pin_number', false, true)->comment(__('pinkieit.pin_number'));
            // 不良品
            $table->boolean('defective')->default(false)->comment(__('pinkieit.defective'));
            // 統計フラグ
            $table->boolean('indicator')->default(false)->comment(__('pinkieit.indicator'));
            // オフセットカウント
            $table->integer('offset_count', false, true)->nullable()->comment(__('pinkieit.offset').__('pinkieit.count'));
            // 現在のカウント
            $table->integer('count', false, true)->default(0)->comment(__('pinkieit.count'));
            // オフセット秒
            $table->integer('offset_second')->default(0)->comment(__('pinkieit.offset').'sec');
            // タイムスタンプ
            $table->timestamps();
            // 複合インデックス
            $table->index(['ip_address', 'pin_number']);
            // 外部キー
            $table->foreign('production_history_id')->references('production_history_id')->on('production_histories')->cascadeOnDelete();
            $table->foreign('line_id')->references('line_id')->on('lines')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_lines');
    }
};
