<?php

namespace App\Services;

use App\Models\ApprovalStage;

class ApprovalStagesService
{
    public function create(array $data): ApprovalStage
    {
        return ApprovalStage::create($data);
    }

    public function update(int $id, array $data): ?ApprovalStage
    {
        $approval_stage = ApprovalStage::findOrFail($id);
        $approval_stage->approver_id = $data['approver_id'];

        if ($approval_stage->save()) {
            return $approval_stage;
        }

        return null; // gagal update
    }
}
