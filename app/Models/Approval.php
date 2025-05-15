<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $table = 'approvals';
    protected $fillable = ['expense_id', 'approver_id', 'status_id'];

     protected static function boot()
    {
        parent::boot();

        static::creating(function ($approvals) {
            if (is_null($approvals->status_id)) {
                $defaultStatus = Status::where('is_default', true)->first();

                if (! $defaultStatus) {
                    throw new \Exception("No default status found. Please insert a status with is_default = true.");
                }

                $approvals->status_id = $defaultStatus->id;
            }
        });
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function approver()
    {
        return $this->belongsTo(Approver::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
