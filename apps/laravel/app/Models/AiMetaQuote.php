<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiMetaQuote extends Model
{
    use HasFactory;

    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED  = 'completed';
    public const STATUS_FAILED     = 'failed';

    protected $fillable = [
        'client_name',
        'industry',
        'budget_cop',
        'duration_days',
        'daily_budget_cop',
        'age_range',
        'location',
        'interests',
        'campaign_type',
        'platform',
        'destination',
        'whatsapp_number',
        'ai_result',
    ];

    protected $casts = [
        'interests'        => 'array',
        'ai_result'        => 'array',
        'budget_cop'       => 'integer',
        'duration_days'    => 'integer',
        'daily_budget_cop' => 'integer',
    ];

    public function scopeCompleted($query)
    {
        return $query->where('generation_status', self::STATUS_COMPLETED);
    }

    public function getDailyBudgetFormattedAttribute(): string
    {
        return '$' . number_format($this->daily_budget_cop, 0, ',', '.');
    }

    public function getBudgetFormattedAttribute(): string
    {
        return '$' . number_format($this->budget_cop, 0, ',', '.');
    }
}
