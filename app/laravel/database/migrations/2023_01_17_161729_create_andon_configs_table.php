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
        Schema::create('andon_configs', function (Blueprint $table) {
            // アンドン設定ID
            $table->id('andon_config_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.target_config', ['target' => __('pinkieit.andon')])]));
            // ユーザーID
            $table->unsignedBigInteger('user_id')->unique()->comment(__('pinkieit.target_id', ['target' => __('pinkieit.user')]));
            // 行数
            $table->integer('row_count')->comment(__('pinkieit.row_count'));
            // 列数
            $table->integer('column_count')->comment(__('pinkieit.column_count'));
            // 自動再生
            $table->boolean('auto_play')->comment(__('pinkieit.auto_play'));
            // 自動再生速度
            $table->integer('auto_play_speed')->comment(__('pinkieit.auto_play_speed'));
            // スライド速度
            $table->integer('slide_speed')->comment(__('pinkieit.slide_speed'));
            // 緩急
            $table->string('easing', 16)->comment(__('pinkieit.easing'));
            // フェード
            $table->boolean('fade')->comment(__('pinkieit.fade'));
            // タイムスタンプ
            $table->timestamps();
            // 外部キー設定
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('andon_configs');
    }
};
