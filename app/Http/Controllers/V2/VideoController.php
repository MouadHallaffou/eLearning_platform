<?php

namespace App\Http\Controllers\V2;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VideoResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Interfaces\VideoRepositoryInterface;

class VideoController extends Controller
{
    private $videoRepository;

    public function __construct(VideoRepositoryInterface $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    /**
     * Récupère toutes les vidéos.
     */
    public function index()
    {
        if (!Auth::user()->hasAnyRole(['mentor', 'admin', 'student'])) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $videos = $this->videoRepository->index();
        return ApiResponseClass::sendResponse(VideoResource::collection($videos), '', 200);
    }

    /**
     * Récupère une vidéo par son ID.
     */
    public function show($id)
    {
        if (!Auth::user()->hasAnyRole(['mentor', 'admin', 'student'])) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $video = $this->videoRepository->getById($id);

        if (!$video) {
            return ApiResponseClass::sendError('Video not found', 404);
        }

        return ApiResponseClass::sendResponse(new VideoResource($video), '', 200);
    }

    /**
     * Crée une nouvelle vidéo.
     */
    public function store(StoreVideoRequest $request)
    {
        if (!Auth::user()->hasAnyRole(['mentor', 'admin'])) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $data = $request->validated();

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('videos', 'public');
            $data['url'] = Storage::url($path);
        } else {
            $data['url'] = 'http://mouadvideoexemple.com/default-video-url.mp4';
        }

        $video = $this->videoRepository->store($data);

        return ApiResponseClass::sendResponse(new VideoResource($video), 'Video created successfully.', 201);
    }

    /**
     * Met à jour une vidéo existante.
     */
    public function update(UpdateVideoRequest $request, $id)
    {
        if (!Auth::user()->hasAnyRole(['mentor', 'admin'])) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $data = $request->validated();

        if ($request->hasFile('video_file')) {
            $video = $this->videoRepository->getById($id);
            if ($video && $video->url) {
                $oldFilePath = str_replace('/storage', '', parse_url($video->url, PHP_URL_PATH));
                Storage::disk('public')->delete($oldFilePath);
            }
            $path = $request->file('video_file')->store('videos', 'public');
            $data['url'] = Storage::url($path);
        } else {
            $video = $this->videoRepository->getById($id);
            if ($video && $video->url) {
                $data['url'] = $video->url;
            } else {
                $data['url'] = 'http://mouadvideoexemple.com/default-video-url.mp4'; 
            }
        }
        $video = $this->videoRepository->update($data, $id);

        if (!$video) {
            return ApiResponseClass::sendError('Video not found', 404);
        }
        return ApiResponseClass::sendResponse(new VideoResource($video), 'Video updated successfully.', 200);
    }

    /**
     * Supprime une vidéo.
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasAnyRole(['mentor', 'admin'])) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }

        $video = $this->videoRepository->getById($id);
        if (!$video) {
            return ApiResponseClass::sendError('Video not found', 404);
        }

        if ($video->url) {
            $filePath = str_replace('/storage', '', parse_url($video->url, PHP_URL_PATH));
            Storage::disk('public')->delete($filePath);
        }

        $this->videoRepository->delete($id);

        return ApiResponseClass::sendResponse(null, 'Video deleted successfully.', 204);
    }
}
