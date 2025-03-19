<?php

namespace App\Http\Controllers\V1;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Interfaces\TagRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\DB;
use OpenAPI\Annotations as OA;

class TagController extends Controller
{
    private TagRepositoryInterface $TagRepositoryInterface;

    public function __construct(TagRepositoryInterface $TagRepositoryInterface)
    {
        $this->TagRepositoryInterface = $TagRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/api/tag",
     *     summary="Get a list of tags",
     *     tags={"Tag"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $data = $this->TagRepositoryInterface->index();
        return ApiResponseClass::sendResponse(TagResource::collection($data), '', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/tag",
     *     summary="Create new tags",
     *     tags={"Tag"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="array", @OA\Items(type="string"), example={"Laravel", "PHP", "API"})
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tags Created Successfully"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function store(StoreTagRequest $request)
    {
        $tags = $request->tags;
        DB::beginTransaction();
        try {
            $createdTags = [];
            foreach ($tags as $tagName) {
                $tag = $this->TagRepositoryInterface->store(['name' => $tagName]);
                $createdTags[] = new TagResource($tag);
            }
            DB::commit();
            return ApiResponseClass::sendResponse($createdTags, 'Tags Created Successfully', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tag/{id}",
     *     summary="Get a tag by ID",
     *     tags={"Tag"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Tag Not Found")
     * )
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
     * @OA\Put(
     *     path="/api/tag/{id}",
     *     summary="Update an existing tag",
     *     tags={"Tag"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Tag Name")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Tag Updated Successfully"),
     *     @OA\Response(response=404, description="Tag Not Found")
     * )
     */
    public function update(UpdateTagRequest $request, $id)
    {
        $Tag = $this->TagRepositoryInterface->getById($id);
        if (!$Tag) {
            return ApiResponseClass::sendResponse('Tag Not Found', '', 404);
        }
        $updateDetails = ['name' => $request->name];
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
     * @OA\Delete(
     *     path="/api/tag/{id}",
     *     summary="Delete a tag by ID",
     *     tags={"Tag"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Tag Deleted Successfully"),
     *     @OA\Response(response=404, description="Tag Not Found")
     * )
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
