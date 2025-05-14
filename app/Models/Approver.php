<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    protected $table = "approvers";
    protected $fillable = ['name'];

    public function approvalStages()
    {
        return $this->hasMany(ApprovalStage::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
}
