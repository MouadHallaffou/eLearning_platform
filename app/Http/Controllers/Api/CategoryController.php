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
use OpenAPI\Annotations as OA;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $CategoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $CategoryRepositoryInterface)
    {
        $this->CategoryRepositoryInterface = $CategoryRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/api/category",
     *     summary="Get a list of categories",
     *     tags={"Category"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $categories = $this->CategoryRepositoryInterface->index();
        return ApiResponseClass::sendResponse(CategoryResource::collection($categories), '', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/category",
     *     summary="Create a new category",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Technology"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category Created Successfully"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
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
     * @OA\Get(
     *     path="/api/category/{id}",
     *     summary="Get a category by ID",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Category Not Found")
     * )
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
     * @OA\Put(
     *     path="/api/category/{id}",
     *     summary="Update an existing category",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Category Name"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category Updated Successfully"),
     *     @OA\Response(response=404, description="Category Not Found")
     * )
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
     * @OA\Delete(
     *     path="/api/category/{id}",
     *     summary="Delete a category by ID",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Category Deleted Successfully"),
     *     @OA\Response(response=404, description="Category Not Found")
     * )
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
