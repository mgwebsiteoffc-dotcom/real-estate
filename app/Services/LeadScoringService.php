<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadScore;

class LeadScoringService
{
    public function calculateScore(Lead $lead)
    {
        $score = 0;
        $breakdown = [];

        // Score based on interactions
        $activityCount = $lead->activities()->count();
        $activityScore = min($activityCount * 5, 30);
        $score += $activityScore;
        $breakdown['activities'] = $activityScore;

        // Score based on status
        $statusScores = [
            'new' => 10,
            'contacted' => 20,
            'qualified' => 30,
            'proposal' => 40,
            'won' => 50,
            'lost' => 0
        ];
        $statusScore = $statusScores[$lead->status] ?? 0;
        $score += $statusScore;
        $breakdown['status'] = $statusScore;

        // Score based on lead age (fresher = higher)
        $daysOld = $lead->created_at->diffInDays(now());
        $ageScore = max(20 - ($daysOld * 2), 0);
        $score += $ageScore;
        $breakdown['freshness'] = $ageScore;

        // Determine grade
        if ($score >= 80) {
            $grade = 'A';
        } elseif ($score >= 60) {
            $grade = 'B';
        } elseif ($score >= 40) {
            $grade = 'C';
        } else {
            $grade = 'D';
        }

        LeadScore::updateOrCreate(
            ['lead_id' => $lead->id],
            [
                'score' => $score,
                'grade' => $grade,
                'score_breakdown' => $breakdown
            ]
        );

        return $score;
    }
}
