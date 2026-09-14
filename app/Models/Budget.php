<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'month',
        'year',
        'amount'
    ];

    protected $casts = [
        'amount'=>'decimal:2',
        'month'=>'integer',
        'year'=>'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAmount(): float
    {
        if($this->category_id)
        {
            return $this->category()->getTotalSpentForMonth($this->month, $this->year);
        }

        return Expense::forUser($this->user_id)
            ->inMonth($this->month, $this->year)
            ->sum('amount');
    }

    public function remainingAmount(): float
    {
        return $this->amount - $this->getSpentAmount();
    }

    public function getPercentageUsed():float
    {
        if($this->amount == 0)
        {
            return 0;
        }

        return ($this->getSpentAmount()/$this->amount)*100;
    }

    public function isOverDue(): bool
    {
        return $this->getSpentAmount()>$this->amount;
    }
}
