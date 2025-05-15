<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="Ardi API",
 *     version="1.0.0"
 * )
 */

use App\Http\Requests\ApproverPostRequest;
use App\Services\ApproversService;
use Illuminate\Routing\Controller as BaseController;



class ApproversController extends BaseController
{
    public function __construct(private ApproversService $service) {}

    /**
     * @OA\Post(
     *     path="/api/approvers",
     *     tags={"Approvers"},
     *     summary="Create a new book",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="approver created")
     * )
     */

    function store(ApproverPostRequest $request)
    {
        try {
            //code...
            \Log::info(json_encode($this->service));
            $approvers = $this->service->create($request->validated());
             
            return response()->json(['data' => $approvers], 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

     /**
     * @OA\Get(
     *     path="/api/approvers",
     *     tags={"Approvers"},
     *     summary="Get list approvers",
     *     @OA\Response(response=200, description="successfully getting approvers data")
     * )
     */

    function list()
    {
        try {
            $approvers = $this->service->list();

            return response()->json(['message' => "Data retrieved", 'data' => $approvers], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
