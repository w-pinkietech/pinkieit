<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

// ホーム
Breadcrumbs::for('home', function ($trail) {
    $trail->push(__('pinkieit.home'), route('home'));
});

// アンドン設定
Breadcrumbs::for('andon.config', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.target_config', ['target' => __('pinkieit.andon')]), route('andon.config'));
});

// 品番切り替え
Breadcrumbs::for('switch.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.switch_part_number') . ' & ' . __('pinkieit.switch_producer'), route('switch.index'));
});

// アバウト
Breadcrumbs::for('about', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.about'), route('about'));
});

// プロフィール
Breadcrumbs::for('user.show', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.profile'), route('user.show'));
});

// プロフィール編集
Breadcrumbs::for('user.edit', function ($trail) {
    $trail->parent('user.show');
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.profile')]));
});

// ユーザー一覧
Breadcrumbs::for('user.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.target_list', ['target' => __('pinkieit.user')]), route('user.index'));
});

// ユーザー作成
Breadcrumbs::for('user.create', function ($trail) {
    $trail->parent('user.index');
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.user')]));
});

// パスワード編集
Breadcrumbs::for('user.password', function ($trail) {
    $trail->parent('user.show');
    $trail->push(__('pinkieit.change_password'));
});

// 工程一覧
Breadcrumbs::for('process.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.target_list', ['target' => __('pinkieit.process')]), route('process.index'));
});

// 工程追加
Breadcrumbs::for('process.create', function ($trail) {
    $trail->parent('process.index');
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.process')]));
});

// 工程詳細
Breadcrumbs::for('process.show', function ($trail, $process) {
    $trail->parent('process.index');
    $trail->push($process->process_name, route('process.show', ['process' => $process]));
});

// 工程編集
Breadcrumbs::for('process.edit', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.process')]));
});

// 生産履歴品番切り替え
Breadcrumbs::for('production.create', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.switch_part_number'));
});

// 生産履歴
Breadcrumbs::for('production.index', function ($trail, $process) {
    $trail->parent('process.index', $process);
    $trail->push($process->process_name . '：' . __('pinkieit.production_history'), route('production.index', ['process' => $process]));
});

// 履歴詳細
Breadcrumbs::for('production.show', function ($trail, $process) {
    $trail->parent('production.index', $process);
    $trail->push(__('pinkieit.display_chart'));
});

// 計画停止時間一覧
Breadcrumbs::for('planned-outage.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.target_list', ['target' => __('pinkieit.planned_outage')]), route('planned-outage.index'));
});

// 計画停止時間追加
Breadcrumbs::for('planned-outage.create', function ($trail) {
    $trail->parent('planned-outage.index');
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.planned_outage')]));
});

// 計画停止時間編集
Breadcrumbs::for('planned-outage.edit', function ($trail) {
    $trail->parent('planned-outage.index');
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.planned_outage')]));
});

// 品番一覧
Breadcrumbs::for('part-number.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.target_list', ['target' => __('pinkieit.part_number')]), route('part-number.index'));
});

// 品番追加
Breadcrumbs::for('part-number.create', function ($trail) {
    $trail->parent('part-number.index');
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.part_number')]));
});

// 品番編集
Breadcrumbs::for('part-number.edit', function ($trail) {
    $trail->parent('part-number.index');
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.part_number')]));
});

// サイクルタイム追加
Breadcrumbs::for('cycle-time.create', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.cycle_time')]));
});

// サイクルタイム編集
Breadcrumbs::for('cycle-time.edit', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.cycle_time')]));
});

// 作業者一覧
Breadcrumbs::for('worker.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.target_list', ['target' => __('pinkieit.worker')]), route('worker.index'));
});

// 作業者追加
Breadcrumbs::for('worker.create', function ($trail) {
    $trail->parent('worker.index');
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.worker')]));
});

// 作業者編集
Breadcrumbs::for('worker.edit', function ($trail) {
    $trail->parent('worker.index');
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.worker')]));
});

// 工程計画停止時間追加
Breadcrumbs::for('process.planned-outage.create', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.process_planned_outage')]));
});

// ラズベリーパイ一覧
Breadcrumbs::for('raspberry-pi.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('pinkieit.target_list', ['target' => __('pinkieit.raspberry_pi')]), route('raspberry-pi.index'));
});

// ラズベリーパイ追加
Breadcrumbs::for('raspberry-pi.create', function ($trail) {
    $trail->parent('raspberry-pi.index');
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.raspberry_pi')]));
});

// ラズベリーパイ編集
Breadcrumbs::for('raspberry-pi.edit', function ($trail) {
    $trail->parent('raspberry-pi.index');
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.raspberry_pi')]));
});

// アラーム追加
Breadcrumbs::for('alarm.create', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.alarm')]));
});

// アラーム編集
Breadcrumbs::for('alarm.edit', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.alarm')]));
});

// ON-OFFメッセージ一覧
Breadcrumbs::for('onoff.index', function ($trail, $process) {
    $trail->parent('process.index', $process);
    $trail->push($process->process_name . '：' . __('pinkieit.notification'));
});

// ON-OFF追加
Breadcrumbs::for('onoff.create', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_add', ['target' =>  __('pinkieit.notification')]));
});

// ON-OFF編集
Breadcrumbs::for('onoff.edit', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.notification')]));
});

// ライン追加
Breadcrumbs::for('line.create', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_add', ['target' => __('pinkieit.line')]));
});

// ライン編集
Breadcrumbs::for('line.edit', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.target_edit', ['target' => __('pinkieit.line')]));
});

// ライン並べ替え
Breadcrumbs::for('line.sorting', function ($trail, $process) {
    $trail->parent('process.show', $process);
    $trail->push(__('pinkieit.sort'));
});
