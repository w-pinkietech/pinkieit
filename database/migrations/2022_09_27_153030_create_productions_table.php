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
        Schema::create('productions', function (Blueprint $table) {
            // 生産ラインID (主キーではない)
            $table->unsignedBigInteger('production_line_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.production_line')]));
            // 作成日時
            $table->dateTime('at', 3)->index()->default(DB::raw('CURRENT_TIMESTAMP'))->comment(__('pinkieit.created_at'));
            // カウント
            $table->integer('count')->comment(__('pinkieit.count'));
            // 段取り替え
            $table->boolean('changeover')->nullable()->comment(__('pinkieit.changeover'));
            // 外部キー
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
        Schema::dropIfExists('productions');
    }
};
