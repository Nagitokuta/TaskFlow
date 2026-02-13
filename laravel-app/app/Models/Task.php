<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'created_by',
        'assigned_to',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_WAIT_APPROVAL = 'wait_approval';
    public const STATUS_COMPLETED = 'completed';

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => '未対応',
            self::STATUS_IN_PROGRESS => '対応中',
            self::STATUS_WAIT_APPROVAL => '承認待ち',
            self::STATUS_COMPLETED => '完了',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
        ]);
    }

    public function scopeWaitApproval($query)
    {
        return $query->where('status', self::STATUS_WAIT_APPROVAL);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at');
    }
}
