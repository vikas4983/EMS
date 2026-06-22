<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'status'];

    public function scopeActive($query)
    {
        $query->where('status', '1')->latest();
    }
    public function scopeInactive($query)
    {
        return $query->where('status', 0)->latest();
    }
    protected function name(): Attribute
    {
        return Attribute::make(get: fn($value) => ucfirst($value), set: fn($value) => strtolower($value));
    }
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status ? 'Active' : 'Inactive'
        );
    }
}
