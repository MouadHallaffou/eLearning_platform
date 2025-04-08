<?php

namespace App\Interfaces;

interface StatsRepositoryInterface
{
    public function getCourseStats();
    public function getCategoryStats();
    public function getTagStats();
}
