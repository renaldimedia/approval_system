<?php

namespace App\Services;

use App\Models\Approver;

class ApproversService
{
    public function create(array $data): Approver
    {
        return Approver::create($data);
    }

    public function list()
    {
        return Approver::all();
    }
}
