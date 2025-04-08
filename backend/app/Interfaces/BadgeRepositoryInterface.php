<?php
namespace App\Interfaces;

use App\Models\Badge;

interface BadgeRepositoryInterface
{
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function update(Badge $badge, array $data);
    public function delete(Badge $badge);
}