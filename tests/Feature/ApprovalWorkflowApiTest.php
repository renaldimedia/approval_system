<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Status;

class ApprovalWorkflowApiTest extends TestCase
{
    use RefreshDatabase;

    private function approveExpenseInOrder($expenseId, $stages, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            $response = $this->patchJson('/api/expense/' . $expenseId . '/approve', [
                'expense_id'  => $expenseId,
                'approver_id' => $stages[$i]['approver_id'], // pakai dari approval stages
                'status_id'   => Status::where('name', 'Disetujui')->first()->id,
            ]);

            $response->assertStatus(200);
        }
    }


    public function test_approval_workflow_api()
    {
        // Insert statuses
        Status::create(['name' => 'Menunggu Persetujuan', 'is_default' => true]);
        Status::create(['name' => 'Disetujui', 'is_default' => false]);

        $statusDisetujui = Status::where('name', 'Disetujui')->first();
        $statusMenunggu = Status::where('name', 'Menunggu Persetujuan')->first();

        // Buat approvers
        $approvers = collect(['Ana', 'Ani', 'Ina'])->map(function ($name) {
            return $this->postJson('/api/approvers', ['name' => $name])->json('data');
        });

        // Buat approval stages sesuai urutan approver id
        $approvalStages = $approvers->map(function ($approver) {
            return $this->postJson('/api/approval-stages', [
                'approver_id' => $approver['id']
            ])->json('data');
        })->sortBy('id')->values(); // pastikan urutan kecil ke besar

        // Buat 4 expenses
        $expenses = collect(range(1, 4))->map(function ($i) {
            return $this->postJson('/api/expense', ['amount' => $i * 1000])->json('data');
        });

        // Expense 1 - approve semua stage
        $this->approveExpenseInOrder($expenses[0]['id'], $approvalStages, 3);

        // Expense 2 - approve 2 stage
        $this->approveExpenseInOrder($expenses[1]['id'], $approvalStages, 2);

        // Expense 3 - approve 1 stage
        $this->approveExpenseInOrder($expenses[2]['id'], $approvalStages, 1);

        // Expense 4 - tidak di-approve

        // Validasi status tiap expense
        $this->getJson("/api/expense/{$expenses[0]['id']}")->assertJsonFragment(['status_id' => $statusDisetujui->id]);
        $this->getJson("/api/expense/{$expenses[1]['id']}")->assertJsonFragment(['status_id' => $statusMenunggu->id]);
        $this->getJson("/api/expense/{$expenses[2]['id']}")->assertJsonFragment(['status_id' => $statusMenunggu->id]);
        $this->getJson("/api/expense/{$expenses[3]['id']}")->assertJsonFragment(['status_id' => $statusMenunggu->id]);
    }
}
