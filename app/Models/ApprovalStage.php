<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStage extends Model
{
    protected $table = "approval_stages";
    protected $fillable = ['approver_id'];

    public function approver()
    {
        return $this->belongsTo(Approver::class);
    }
}
