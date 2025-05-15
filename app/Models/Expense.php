<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = "expenses";
    protected $fillable = ['amount', 'status_id'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            if (is_null($expense->status_id)) {
                $defaultStatus = Status::where('is_default', true)->first();

                if (! $defaultStatus) {
                    throw new \Exception("No default status found. Please insert a status with is_default = true.");
                }

                $expense->status_id = $defaultStatus->id;
            }
        });
    }
}
