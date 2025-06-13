<?php

namespace App\Models;

use App\Enums\SensorType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * センサーモデルクラス
 *
 * @property int $sensor_id 主キー
 * @property int $process_id 工程ID(外部キー)
 * @property int $raspberry_pi_id ラズパイID(外部キー)
 * @property int $identification_number 識別番号
 * @property SensorType $sensor_type センサー種別
 * @property string $alarm_text アラームテキスト
 * @property bool $trigger トリガー
 */
class Sensor extends Pivot
{
    use HasFactory;

    /**
     * モデルに関連付けられたテーブル名
     *
     * @var string
     */
    protected $table = 'sensors';

    /**
     * モデルの主キーが自動インクリメントされるかどうかを示すフラグ
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * モデルの主キー名
     *
     * @var string
     */
    protected $primaryKey = 'sensor_id';

    /**
     * 代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'process_id',               // 工程ID
        'raspberry_pi_id',          // ラズパイID
        'identification_number',    // 識別番号
        'sensor_type',              // センサー種別
        'alarm_text',               // アラームテキスト
        'trigger',                  // トリガー
    ];

    /**
     * シリアライズ時に隠す属性
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',   // 作成時刻を隠す
        'updated_at',   // 更新時刻を隠す
    ];

    /**
     * キャストする属性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sensor_type' => SensorType::class,
        'trigger' => 'boolean',
    ];

    /**
     * センサーと関連するラズベリーパイを取得する
     */
    public function raspberryPi(): BelongsTo
    {
        return $this->belongsTo(RaspberryPi::class, 'raspberry_pi_id');
    }

    /**
     * センサーイベントと関連する工程を取得する
     */
    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }
}
