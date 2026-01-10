<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = ['category_id', 'amount', 'month', 'year'];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAttribute(): float
    {
        return Transaction::where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date', $this->year)
            ->sum('amount');
    }

    public function getPercentageAttribute(): float
    {
        if ($this->amount == 0) return 0;
        return min(($this->spent / $this->amount) * 100, 100);
    }

    public function getRemainingAttribute(): float
    {
        return max($this->amount - $this->spent, 0);
    }
}