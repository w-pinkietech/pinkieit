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
        Schema::create('raspberry_pis', function (Blueprint $table) {
            // ラズパイID
            $table->id('raspberry_pi_id')->comment(__('pinkieit.target_id', ['target' => __('pinkieit.raspberry_pi')]));
            // ラズパイ名
            $table->string('raspberry_pi_name', 32)->unique()->index()->comment(__('pinkieit.target_name', ['target' => __('pinkieit.raspberry_pi')]));
            // IPアドレス
            $table->ipAddress()->unique()->index()->comment(__('pinkieit.ip_address'));
            // CPU温度
            $table->double('cpu_temperature', 6, 1)->nullable()->comment(__('pinkieit.cpu_temperature'));
            // CPU使用率
            $table->unsignedFloat('cpu_utilization', 5, 1)->nullable()->comment(__('pinkieit.cpu_utilization'));
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
        Schema::dropIfExists('raspberry_pis');
    }
};
