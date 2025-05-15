<?php


namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="Ardi API",
 *     version="1.0.0"
 * )
 */

use App\Http\Requests\ApprovalPostRequest;
use App\Http\Requests\ExpensePostRequest;
use App\Http\Requests\ExpensePutRequest;

use App\Services\ExpensesService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class ExpensesController extends BaseController
{
    public function __construct(private ExpensesService $service) {}


     /**
     * @OA\Get(
     *     path="/api/expense/{id}",
     *     tags={"Expenses"},
     *     summary="Get detail expense",
     *     @OA\Response(response=200, description="successfully getting expenses data")
     * )
     */

    function show($id)
    {
        try {
            $Expense = $this->service->show($id);

            return response()->json(['message' => "Data retrieved", 'data' => $Expense], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

     /**
     * @OA\Get(
     *     path="/api/expense",
     *     tags={"Expenses"},
     *     summary="Get list expenses",
     *     @OA\Response(response=200, description="successfully getting expenses data")
     * )
     */

    function list()
    {
        try {
            $Expenses = $this->service->list();

            return response()->json(['message' => "Data retrieved", 'data' => $Expenses], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * @OA\Post(
     *     path="/api/expense",
     *     tags={"Expenses"},
     *     summary="Create expenses",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="integer", example=1000, description="Amount is required")
     *         )
     *     ),
     *     @OA\Response(response=201, description="expense created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    function store(ExpensePostRequest $request)
    {
        try {
            //code...
            $Expensess = $this->service->create($request->validated());

            return response()->json(['message' => "Expenses created", 'data' => $Expensess], 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/expense/{id}/approve",
     *     tags={"Expenses"},
     *     summary="Approve expense",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the expense to approve",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"approver_id"},
     *             @OA\Property(
     *                 property="approver_id",
     *                 type="integer",
     *                 example=2,
     *                 description="ID of approver (must exist in approvers table)"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Approval stage updated"),
     *     @OA\Response(response=404, description="Approval stage not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */


    function approve(ApprovalPostRequest $request, $id)
    {
        try {
            $Expenses = $this->service->approve($id, $request->validated());
            if ($Expenses == null) {
                throw new \Exception("Failed to update Approval stage!");
            }
            return response()->json([
                'message' => "Approval stage updated"
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Approval stage not found',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
