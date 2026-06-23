<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseReceipt extends Model
{
    protected $fillable = [
        'expense_id',
        'file_name',
        'file_path',
    ];

   
}
