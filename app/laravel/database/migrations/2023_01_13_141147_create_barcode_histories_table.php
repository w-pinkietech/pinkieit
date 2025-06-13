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
        Schema::create('barcode_histories', function (Blueprint $table) {
            // バーコード履歴ID
            $table->id('barcode_history_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.barcode')]));
            // IPアドレス
            $table->ipAddress()->comment(__('pinkieit.ip_address'));
            // MACアドレス
            $table->macAddress()->comment(__('pinkieit.mac_address'));
            // バーコード
            $table->string('barcode', 64)->comment(__('pinkieit.barcode'));
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
        Schema::dropIfExists('barcode_histories');
    }
};
