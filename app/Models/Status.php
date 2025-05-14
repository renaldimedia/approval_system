<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = "statuses";
    protected $fillable = ['name', 'is_default'];

    protected $attributes = [
        'is_default' => false, // nilai default
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
}
