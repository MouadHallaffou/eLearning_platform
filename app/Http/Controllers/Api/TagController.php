<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Interfaces\TagRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    private TagRepositoryInterface $TagRepositoryInterface;

    public function __construct(TagRepositoryInterface $TagRepositoryInterface)
    {
        $this->TagRepositoryInterface = $TagRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->TagRepositoryInterface->index();

        return ApiResponseClass::sendResponse(TagResource::collection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $details = [
            'name' => $request->name
        ];

        DB::beginTransaction();
        try {
            $Tag = $this->TagRepositoryInterface->store($details);
            DB::commit();

            return ApiResponseClass::sendResponse(new TagResource($Tag), 'Tag Created Successfully', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Tag = $this->TagRepositoryInterface->getById($id);

        if (!$Tag) {
            return ApiResponseClass::sendResponse('Tag Not Found', '', 404);
        }

        return ApiResponseClass::sendResponse(new TagResource($Tag), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, $id)
    {
        $Tag = $this->TagRepositoryInterface->getById($id);

        if (!$Tag) {
            return ApiResponseClass::sendResponse('Tag Not Found', '', 404);
        }

        $updateDetails = [
            'name' => $request->name
        ];

        DB::beginTransaction();
        try {
            $this->TagRepositoryInterface->update($updateDetails, $id);
            DB::commit();

            return ApiResponseClass::sendResponse(new TagResource($Tag), 'Tag Updated Successfully', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Tag = $this->TagRepositoryInterface->getById($id);

        if (!$Tag) {
            return ApiResponseClass::sendResponse('Tag Not Found', '', 404);
        }

        DB::beginTransaction();
        try {
            $this->TagRepositoryInterface->delete($id);
            DB::commit();

            return ApiResponseClass::sendResponse('Tag Deleted Successfully', '', 204);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseClass::rollback($ex);
        }
    }
}
