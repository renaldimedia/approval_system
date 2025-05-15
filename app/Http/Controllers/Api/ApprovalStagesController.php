<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApprovalStagePostRequest;
use App\Http\Requests\ApprovalStagePutRequest;

use App\Services\ApprovalStagesService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class ApprovalStagesController extends BaseController
{
    public function __construct(private ApprovalStagesService $service) {}

    /**
     * @OA\Post(
     *     path="/api/approval-stages",
     *     tags={"ApprovalStages"},
     *     summary="Create approval stages",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"approver_id"},
     *             @OA\Property(property="approver_id", type="integer", example=1, description="ID must exists in table approver")
     *         )
     *     ),
     *     @OA\Response(response=201, description="approver created")
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    function store(ApprovalStagePostRequest $request)
    {
        try {
            //code...
            $ApprovalStages = $this->service->create($request->validated());

            return response()->json(['message' => "Approver created"], 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/approval-stages/{id}",
     *     tags={"ApprovalStages"},
     *     summary="Update an approval stage",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the approval stage to update",
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


    function update(ApprovalStagePutRequest $request, $id)
    {
        try {
            $approvalStage = $this->service->update($id, $request->validated());
            if ($approvalStage == null) {
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
