<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\BadgeRule;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $badges = [
            ['name' => 'Debutant', 'description' => 'Premier pas sur la plateforme', 'type' => 'student'],
            ['name' => 'Intermediaire', 'description' => 'A progresse significativement', 'type' => 'student'],
            ['name' => 'Expert', 'description' => 'A complete plusieurs cours', 'type' => 'student'],
            ['name' => '100% de completion', 'description' => 'A termine un cours à 100%', 'type' => 'student'],
            ['name' => 'Fidèle', 'description' => 'Actif depuis 6 mois', 'type' => 'student']
        ];

        foreach ($badges as $data) {
            $badge = Badge::create($data);

            if ($badge->name === 'Debutant') {
                BadgeRule::create(['badge_id' => $badge->id, 'operator' => '>=', 'condition' => 'courses_started', 'value' => 1]);
            } elseif ($badge->name === 'Intermediaire') {
                BadgeRule::create(['badge_id' => $badge->id, 'operator' => '>=', 'condition' => 'courses_followed', 'value' => 10]);
            } elseif ($badge->name === 'Expert') {
                BadgeRule::create(['badge_id' => $badge->id, 'operator' => '>=', 'condition' => 'courses_completed', 'value' => 5]);
            } elseif ($badge->name === '100% de completion') {
                BadgeRule::create(['badge_id' => $badge->id, 'operator' => '>=', 'condition' => 'course_completion', 'value' => 100]);
            } elseif ($badge->name === 'Fidèle') {
                BadgeRule::create(['badge_id' => $badge->id, 'operator' => '>=', 'condition' => 'active_months', 'value' => 6]);
            }
        }


        $mentorBadges = [
            ['name' => 'Createur', 'description' => 'A cree 5 cours', 'type' => 'mentor'],
            ['name' => 'Influenceur', 'description' => 'A 50 elèves inscrits', 'type' => 'mentor'],
            ['name' => 'Engage', 'description' => 'Actif depuis 6 mois', 'type' => 'mentor']
        ];

        foreach ($mentorBadges as $data) {
            $badge = Badge::create($data);

            if ($badge->name === 'Createur') {
                BadgeRule::create(['badge_id' => $badge->id, 'operator' => '>=', 'condition' => 'created_courses', 'value' => 5]);
            } elseif ($badge->name === 'Influenceur') {
                BadgeRule::create(['badge_id' => $badge->id, 'operator' => '>=', 'condition' => 'students_enrolled', 'value' => 50]);
            } elseif ($badge->name === 'Engage') {
                BadgeRule::create(['badge_id' => $badge->id, 'operator' => '>=', 'condition' => 'active_months', 'value' => 6]);
            }
        }
    }
}
