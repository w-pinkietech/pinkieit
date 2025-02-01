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
        Schema::create('workers', function (Blueprint $table) {
            // 作業者ID
            $table->id('worker_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.worker')]));
            // 識別番号
            $table->string('identification_number', 32)->unique()->index()->comment(__('pinkieit.identification_number'));
            // 作業者名
            $table->string('worker_name', 32)->comment(__('pinkieit.target_name', ['target' => __('pinkieit.worker')]));
            // MACアドレス
            $table->macAddress()->nullable()->unique()->index()->comment(__('pinkieit.mac_address'));
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
        Schema::dropIfExists('workers');
    }
};
