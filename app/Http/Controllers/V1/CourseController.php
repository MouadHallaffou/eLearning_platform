<?php

namespace App\Http\Controllers\V1;

use App\Models\Tag;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Interfaces\CourseRepositoryInterface;

/**
 * @OA\Tag(
 *     name="Courses",
 *     description="Endpoints pour la gestion des cours"
 * )
 */
class CourseController extends Controller
{
    private $courseRepositoryInterface;

    public function __construct(CourseRepositoryInterface $courseRepositoryInterface)
    {
        $this->courseRepositoryInterface = $courseRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/api/V1/courses",
     *     summary="Lister tous les cours",
     *     tags={"Courses"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des cours",
     *     )
     * )
     */
    public function index()
    {
        $data = $this->courseRepositoryInterface->index();
        return ApiResponseClass::sendResponse(CourseResource::collection($data), '', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/V1/courses",
     *     summary="Créer un nouveau cours",
     *     tags={"Courses"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cours créé avec succès",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
     *         )
     *     )
     * )
     */

    public function store(StoreCourseRequest $request)
    {
        if (!auth()->user()->hasRole('mentor')) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $validatedRequest = $request->validated();
        $details = [
            'title' => $validatedRequest['title'],
            'description' => $validatedRequest['description'],
            'content' => $validatedRequest['content'],
            'cover' => $validatedRequest['cover'],
            'duration' => $validatedRequest['duration'],
            'level' => $validatedRequest['level'],
            'category_id' => $validatedRequest['category_id'],
            'user_id' => auth()->id(),
        ];

        DB::beginTransaction();
        try {
            $course = $this->courseRepositoryInterface->store($details);

            if ($request->has('tag_ids')) {
                $tagIds = [];

                foreach ($request->tag_ids as $tag) {
                    if (is_numeric($tag)) {
                        $tagIds[] = $tag;
                    } else {
                        $newTag = Tag::firstOrCreate(['name' => $tag]);
                        $tagIds[] = $newTag->id;
                    }
                }

                $course->tags()->sync($tagIds);
            }

            DB::commit();
            return ApiResponseClass::sendResponse(new CourseResource($course), 'Course Created Successfully', 201);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/V1/courses/{id}",
     *     summary="Obtenir les détails d'un cours",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du cours",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du cours",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cours non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Course Not Found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        if (!auth()->user()->hasAnyRole(['mentor', 'admin'])) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $course = $this->courseRepositoryInterface->getById($id);
        if (!$course) {
            return ApiResponseClass::sendResponse('Course Not Found', '', 404);
        }

        return ApiResponseClass::sendResponse(new CourseResource($course), '', 200);
    }

    /**
     * @OA\Put(
     *     path="/api/V1/courses/{id}",
     *     summary="Modifier un cours",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du cours",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cours mis à jour avec succès",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cours non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Course Not Found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
     *         )
     *     )
     * )
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        if (!auth()->user()->hasRole('mentor')) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $validatedRequest = $request->validated(); 

        $course = $this->courseRepositoryInterface->getById($id);

        if (!$course) {
            return ApiResponseClass::sendResponse('Course Not Found', '', 404);
        }
        if ($course->user_id !== auth()->id()) {
            return ApiResponseClass::sendError('Forbidden: You are not the creator of this course.', 403);
        }

        $updateDetails = [
            'title' => $validatedRequest['title'],
            'description' => $validatedRequest['description'],
            'content' => $validatedRequest['content'],
            'cover' => $validatedRequest['cover'],
            'duration' => $validatedRequest['duration'],
            'level' => $validatedRequest['level'],
            'category_id' => $validatedRequest['category_id'],
        ];

        DB::beginTransaction();
        try {
            $course = $this->courseRepositoryInterface->update($updateDetails, $id);
            if (!$course) {
                throw new \Exception('Course update failed.');
            }

            if ($request->has('tag_ids')) {
                $tagIds = [];

                foreach ($request->tag_ids as $tag) {
                    if (is_numeric($tag)) {
                        $tagIds[] = $tag;
                    } else {
                        $newTag = Tag::firstOrCreate(['name' => $tag]);
                        $tagIds[] = $newTag->id;
                    }
                }

                $course->tags()->sync($tagIds);
            } else {
                $course->tags()->detach();
            }

            DB::commit();
            return ApiResponseClass::sendResponse(new CourseResource($course), 'Course Updated Successfully', 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseClass::rollback($ex);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/V1/courses/{id}",
     *     summary="Supprimer un cours",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du cours",
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Cours supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cours non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Course Not Found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasAnyRole(['mentor', 'admin'])) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $course = $this->courseRepositoryInterface->getById($id);

        if (!$course) {
            return ApiResponseClass::sendResponse('Course Not Found', '', 404);
        }

        if ($course->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return ApiResponseClass::sendError('Forbidden: You are not the creator of this course.', 403);
        }

        DB::beginTransaction();
        try {
            $this->courseRepositoryInterface->delete($id);
            DB::commit();

            return ApiResponseClass::sendResponse('Course Deleted Successfully', '', 204);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseClass::rollback($ex);
        }
    }
}
