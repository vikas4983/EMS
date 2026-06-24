<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'integer',
    ];
    use SoftDeletes;
    protected $fillable = ['user_id', 'category_id', 'title', 'status', 'amount', 'expense_date', 'description', 'manager_comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected function statusBadgeClass(): Attribute
    {
        return Attribute::make(
            get: fn() => match (strtolower($this->status)) {
                'pending' => 'bg-secondary-100 text-secondary-600',
                'approved' => 'bg-success-100 text-success-600',
                'rejected' => 'bg-danger-100 text-danger-600',
            },
        );
    }
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
    public function receipts()
    {
        return $this->hasMany(ExpenseReceipt::class, 'expense_id');
    }

    public function scopeApprovedThisMonth($query)
    {
        return $query->whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

   
    public function scopeForTeam($query, $teamIds)
    {
        return $query->whereIn('user_id', $teamIds);
    }
}
