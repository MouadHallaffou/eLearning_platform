<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\StatsRepositoryInterface;

class StatsController extends Controller
{
    protected $statsRepository;

    public function __construct(StatsRepositoryInterface $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    // GET /api/V1/stats/courses
    public function getCourseStats()
    {
        try {
            $stats = $this->statsRepository->getCourseStats();
            return response()->json(['data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // GET /api/V1/stats/categories
    public function getCategoryStats()
    {
        try {
            $stats = $this->statsRepository->getCategoryStats();
            return response()->json(['data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // GET /api/V1/stats/tags
    public function getTagStats()
    {
        try {
            $stats = $this->statsRepository->getTagStats();
            return response()->json(['data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}
