<?php

namespace App\Repositories;

use App\Models\Video;
use App\Interfaces\VideoRepositoryInterface;

class VideoRepository implements VideoRepositoryInterface
{
    /**
     * Récupère toutes les vidéos.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Video::all();
    }

    /**
     * Récupère une vidéo par son ID.
     *
     * @param int $id
     * @return \App\Models\Video|null
     */
    public function getById($id)
    {
        return Video::find($id);
    }

    /**
     * Crée une nouvelle vidéo.
     *
     * @param array $data
     * @return \App\Models\Video
     */
    public function store(array $data)
    {
        return Video::create($data);
    }

    /**
     * Met à jour une vidéo existante.
     *
     * @param array $data
     * @param int $id
     * @return \App\Models\Video
     */
    public function update(array $data, $id)
    {
        $video = Video::find($id);
        if ($video) {
            $video->update($data);
        }
        return $video;
    }

    /**
     * Supprime une vidéo.
     *
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        $video = Video::find($id);
        if ($video) {
            $video->delete();
        }
    }
}