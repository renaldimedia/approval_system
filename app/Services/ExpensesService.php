<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Status;
use App\Models\Approval;
use App\Models\ApprovalStage;
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

    public function show($id): Expense
    {
        return Expense::findOrFail($id);
    }

    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    public function approve(int $id, array $data): ?Approval
    {
        $expense = Expense::findOrFail($id);

        // Ambil semua approver ID dalam urutan di approval stage
        $allApprovers = ApprovalStage::orderBy('id')->pluck('approver_id')->values();

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


        // Hitung jumlah approval stage & approval existing
        $totalStages = ApprovalStage::count();
        $approvedCount = Approval::where('expense_id', $id)->count();

        // Jika semua tahap sudah disetujui, ubah status expense
        if ($approvedCount >= $totalStages) {
            $statusApproved = Status::where('name', 'Disetujui')->first();
            if (!$statusApproved) {
                throw new \Exception('Status Disetujui tidak ditemukan');
            }

            $expense = Expense::findOrFail($id);
            $expense->status_id = $statusApproved->id;
            $expense->save();
        }

        return $approval;
    }
}
