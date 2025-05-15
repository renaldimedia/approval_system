<?php

namespace App\Services;

use App\Models\Status;

class StatusesService
{
    public function create(array $data): Status
    {
        return Status::create($data);
    }
}
    