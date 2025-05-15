<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Approver;
use App\Models\Approval;
use Illuminate\Support\Facades\DB;


class ExpensesService
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|Expense[]
     */
    public function list()
    {
        return Expense::all("*");
    }

    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    public function approve(int $id, array $data): ?Approval
    {
        $expense = Expense::findOrFail($id);

        // Ambil semua approver ID dalam urutan
        $allApprovers = Approver::orderBy('id')->pluck('id')->values();

        // Ambil semua approval yang sudah ada untuk expense ini
        $existingApprovers = Approval::where('expense_id', $id)
            ->orderBy('approver_id')
            ->pluck('approver_id')
            ->values();

        // Hitung next approver ID yang valid
        $nextApproverIndex = $existingApprovers->count();
        $expectedApproverId = $allApprovers->get($nextApproverIndex);

        if (!$expectedApproverId || $expectedApproverId !== $data['approver_id']) {
            throw new \Exception("Invalid approver. Expected approver_id: {$expectedApproverId}");
        }

        // Simpan approval baru
        $approval = Approval::create([
            'expense_id'   => $id,
            'approver_id'  => $data['approver_id'],
            'status_id'    => $data['status_id'], // harus disediakan di $data
        ]);

        return $approval;
    }
}
