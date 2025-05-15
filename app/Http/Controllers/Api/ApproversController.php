<?php

namespace App\Http\Controllers\Api;

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
}
