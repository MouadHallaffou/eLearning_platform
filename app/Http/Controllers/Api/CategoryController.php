<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $CategoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $CategoryRepositoryInterface)
    {
        $this->CategoryRepositoryInterface = $CategoryRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->CategoryRepositoryInterface->index();

        return ApiResponseClass::sendResponse(CategoryResource::collection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $details = [
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ];

        DB::beginTransaction();
        try {
            $Category = $this->CategoryRepositoryInterface->store($details);
            DB::commit();

            return ApiResponseClass::sendResponse(new CategoryResource($Category), 'Category Created Successfully', 201);
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
        $Category = $this->CategoryRepositoryInterface->getById($id);

        if (!$Category) {
            return ApiResponseClass::sendResponse('Category Not Found', '', 404);
        }

        return ApiResponseClass::sendResponse(new CategoryResource($Category), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $Category = $this->CategoryRepositoryInterface->getById($id);

        if (!$Category) {
            return ApiResponseClass::sendResponse('Category Not Found', '', 404);
        }

        $updateDetails = [
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ];

        DB::beginTransaction();
        try {
            $this->CategoryRepositoryInterface->update($updateDetails, $id);
            DB::commit();

            return ApiResponseClass::sendResponse(new CategoryResource($Category), 'Category Updated Successfully', 200);
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
        $Category = $this->CategoryRepositoryInterface->getById($id);

        if (!$Category) {
            return ApiResponseClass::sendResponse('Category Not Found', '', 404);
        }

        DB::beginTransaction();
        try {
            $this->CategoryRepositoryInterface->delete($id);
            DB::commit();

            return ApiResponseClass::sendResponse('Category Deleted Successfully', '', 204);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseClass::rollback($ex);
        }
    }
}
