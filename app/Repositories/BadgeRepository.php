<?php

namespace App\Repositories;

use App\Models\Badge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\BadgeRepositoryInterface;

class BadgeRepository implements BadgeRepositoryInterface
{
    public function all(): Collection
    {
        try {
            return Badge::all();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des badges: ' . $e->getMessage());
            throw $e;
        }
    }

    public function find(int $id): ?Badge
    {
        try {
            return Badge::find($id);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la recherche du badge {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data): Badge
    {
        DB::beginTransaction();
        
        try {
            $badge = Badge::create($data);
            DB::commit();
            
            Log::info("Badge créé avec succès - ID: {$badge->id}");
            return $badge;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création badge: ' . $e->getMessage(), ['data' => $data]);
            throw $e;
        }
    }

    public function update(Badge $badge, array $data): bool
    {
        DB::beginTransaction();
        
        try {
            $updated = $badge->update($data);
            DB::commit();
            
            if ($updated) {
                Log::info("Badge {$badge->id} mis à jour avec succès");
            } else {
                Log::warning("Aucune modification apportée au badge {$badge->id}");
            }
            
            return $updated;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour badge {$badge->id}: " . $e->getMessage(), ['data' => $data]);
            throw $e;
        }
    }

    public function delete(Badge $badge): bool
    {
        DB::beginTransaction();
        $id = $badge->id;
        
        try {
            $deleted = $badge->delete();
            DB::commit();
            
            if ($deleted) {
                Log::info("Badge {$id} supprimé avec succès");
            }
            
            return $deleted;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur suppression badge {$id}: " . $e->getMessage());
            throw $e;
        }
    }
}