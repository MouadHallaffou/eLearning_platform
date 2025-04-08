<?php

namespace App\Http\Controllers\V3;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BadgeResource;
use App\Http\Requests\StoreBadgeRequest;
use App\Interfaces\BadgeRepositoryInterface;

class BadgeController extends Controller
{
    public function __construct(
        protected BadgeRepositoryInterface $repository
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(BadgeResource::collection($this->repository->all()));
    }

    public function store(StoreBadgeRequest $request): JsonResponse
    {
        $badge = $this->repository->create($request->validated());
        return response()->json(new BadgeResource($badge), 201);
    }

    public function show(int $id): JsonResponse
    {
        $badge = $this->repository->find($id);
        return $badge 
            ? response()->json(new BadgeResource($badge))
            : response()->json(['message' => 'Not found'], 404);
    }

    public function update(StoreBadgeRequest $request, int $id): JsonResponse
    {
        $badge = $this->repository->find($id);
        if (!$badge) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $this->repository->update($badge, $request->validated());
        return response()->json(new BadgeResource($badge));
    }

    public function destroy(int $id): JsonResponse
    {
        $badge = $this->repository->find($id);
        if (!$badge) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $this->repository->delete($badge);
        return response()->json(null, 204);
    }
}