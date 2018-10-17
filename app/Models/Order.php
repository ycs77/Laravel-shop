<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Order extends Model
{
    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_APPLIED = 'applied';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_SUCCESS = 'success';
    const REFUND_STATUS_FAILED = 'failed';

    const SHIP_STATUS_PENDING = 'pending';
    const SHIP_STATUS_DELIVERED = 'delivered';
    const SHIP_STATUS_RECEIVED = 'received';

    public static $refundStatusMap = [
        self::REFUND_STATUS_PENDING    => 'primary',
        self::REFUND_STATUS_APPLIED    => 'info',
        self::REFUND_STATUS_PROCESSING => 'warning',
        self::REFUND_STATUS_SUCCESS    => 'success',
        self::REFUND_STATUS_FAILED     => 'danger',
    ];

    public static $shipStatusMap = [
        self::SHIP_STATUS_PENDING   => 'primary',
        self::SHIP_STATUS_DELIVERED => 'danger',
        self::SHIP_STATUS_RECEIVED  => 'success',
    ];

    protected $fillable = [
        'no',
        'address',
        'total_amount',
        'remark',
        'paid_at',
        'payment_method',
        'payment_no',
        'refund_status',
        'refund_no',
        'closed',
        'reviewed',
        'ship_status',
        'ship_data',
        'extra',
    ];

    protected $casts = [
        'closed'    => 'boolean',
        'reviewed'  => 'boolean',
        'address'   => 'json',
        'ship_data' => 'json',
        'extra'     => 'json',
    ];

    protected $dates = [
        'paid_at',
    ];

    protected static function boot()
    {
        parent::boot();
        // 監聽模型創建事件，在寫入數據庫之前觸發
        static::creating(function ($model) {
            // 如果模型的 no 字段為空
            if (!$model->no) {
                // 調用 findAvailableNo 生成訂單流水號
                $model->no = static::findAvailableNo();
                // 如果生成失敗，則終止創建訂單
                if (!$model->no) return false;
            }
        });
    }

    public static function findAvailableNo()
    {
        // 訂單流水號前綴
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 隨機生成 6 位的數字
            $no = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判斷是否已經存在
            if (!static::where('no', $no)->exists()) {
                return $no;
            }
        }
        \Log::warning('find order no failed');

        return false;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRefundStatusColorAttribute()
    {
        return static::$refundStatusMap[$this->refund_status];
    }

    public static function getAvailableRefundNo()
    {
        do {
            $no = Uuid::uuid4()->getHex();
        } while (self::where('refund_no', $no)->exists());

        return $no;
    }
}
