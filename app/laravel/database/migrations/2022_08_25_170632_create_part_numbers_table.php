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
        Schema::create('part_numbers', function (Blueprint $table) {
            // 品番ID
            $table->id('part_number_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.part_number')]));
            // 品番名
            $table->string('part_number_name', 32)->unique()->index()->comment(__('pinkieit.target_name', ['target' => __('pinkieit.part_number')]));
            // バーコード
            $table->string('barcode', 64)->nullable()->unique()->index()->comment(__('pinkieit.barcode'));
            // 備考
            $table->string('remark', 256)->nullable()->comment(__('pinkieit.remark'));
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
        Schema::dropIfExists('part_numbers');
    }
};
