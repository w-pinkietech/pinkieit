<?php

namespace App\Models;

use App\Enums\SensorType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * センサーイベントモデルクラス
 *
 * @property integer $sensor_event_id 主キー
 * @property integer $process_id 工程ID(外部キー)
 * @property integer $sensor_id センサーID(外部キー)
 * @property string $ip_address IPアドレス
 * @property integer $identification_number 識別番号
 * @property SensorType $sensor_type センサー種別
 * @property string $alarm_text アラームテキスト
 * @property boolean $trigger トリガー
 * @property boolean $signal 信号
 * @property float $value センサー値
 * @property Carbon $at 時刻
 * @property string $sensor_name センサー名
 * @property boolean $is_start イベントの開始フラグ
 */
class SensorEvent extends Model
{
    use HasFactory;

    /**
     * モデルの主キー名
     *
     * @var string
     */
    protected $primaryKey = 'sensor_event_id';

    /**
     * 代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'process_id',               // 工程ID
        'sensor_id',                // センサーID
        'ip_address',               // IPアドレス
        'identification_number',    // 識別番号
        'sensor_type',              // センサー種別
        'alarm_text',               // アラームテキスト
        'trigger',                  // トリガー
        'signal',                   // 信号
        'value',                    // センサー値
        'at',                       // 時刻
    ];

    /**
     * シリアライズ時に隠す属性
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'sensor_event_id',
        'ip_address',
        'identification_number',
        'sensor_type',
        'trigger',
        'signal',
        'value',
    ];

    /**
     * キャストする属性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sensor_type' => SensorType::class,
        'trigger' => 'boolean',
        'signal' => 'boolean',
        'at' => 'datetime:Y-m-d H:i:s.u',
    ];

    /**
     * モデルに追加するアクセサ
     *
     * @var array<int, string>
     */
    protected $appends = [
        'sensor_name',
        'is_start',
    ];

    /**
     * モデルにタイムスタンプを付けるかどうか
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * センサーイベントと関連する工程を取得する
     *
     * @return BelongsTo
     */
    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * センサーイベントが開始されたかどうかを取得する
     *
     * @return boolean trueであればイベントの開始
     */
    public function getIsStartAttribute(): bool
    {
        return $this->trigger === $this->signal;
    }

    /**
     * センサー種別のキー名称を取得する
     *
     * @return string
     */
    public function getSensorNameAttribute(): string
    {
        return $this->sensor_type->key;
    }
}
