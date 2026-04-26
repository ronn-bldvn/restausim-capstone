<?php

namespace App\Http\Controllers;

use App\Models\StudentQuiz;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{


    public function index()
    {
        $quizTotal = StudentQuiz::count();
        $totalSections = StudentQuiz::distinct('section')->count('section');
        $meanScore = StudentQuiz::avg('score');

        // Median
        $scores = StudentQuiz::orderBy('score')->pluck('score')->toArray();
        $count = count($scores);

        if ($count === 0) {
            $medianScore = null;
        } elseif ($count % 2 === 0) {
            $medianScore = ($scores[$count / 2 - 1] + $scores[$count / 2]) / 2;
        } else {
            $medianScore = $scores[floor($count / 2)];
        }

        // Mode
        $modeScore = StudentQuiz::select('score')
            ->groupBy('score')
            ->orderByRaw('COUNT(*) DESC')
            ->value('score');

        // Standard Deviation (Population)
        if ($count === 0) {
            $stdDeviation = null;
        } else {
            $variance = collect($scores)
                ->map(fn ($score) => pow($score - $meanScore, 2))
                ->avg();

            $stdDeviation = sqrt($variance);
        }

        $sectionData = StudentQuiz::select('section', DB::raw('COUNT(*) as total'))
            ->whereNotNull('section')
            ->groupBy('section')
            ->pluck('total', 'section');

        $ageData = StudentQuiz::select('age', DB::raw('COUNT(*) as total'))
            ->whereNotNull('age')
            ->groupBy('age')
            ->orderBy('age')
            ->pluck('total', 'age');

        $sexData = StudentQuiz::select('sex', DB::raw('COUNT(*) as total'))
            ->whereNotNull('sex')
            ->groupBy('sex')
            ->orderBy('sex')
            ->pluck('total', 'sex');

        return view(
            'superadmin.dashboard',
            compact(
                'quizTotal',
                'totalSections',
                'meanScore',
                'medianScore',
                'modeScore',
                'stdDeviation',
                'sectionData',
                'ageData',
                'sexData',
            )
        );
    }

    public function preTest(Request $request)
    {
        $preTest = StudentQuiz::select(
            'id',
            'age',
            'sex',
            'section',
            'score',
            'completed_at'
        )->paginate(10);

        $ages     = StudentQuiz::distinct()->orderBy('age')->pluck('age');
        $sections = StudentQuiz::distinct()->orderBy('section')->pluck('section');

        return view('superadmin.pretest', compact('preTest', 'ages', 'sections'));
    }

    public function filter(Request $request)
    {
        $query = StudentQuiz::select(
            'id',
            'age',
            'sex',
            'section',
            'score',
            'completed_at'
        );

        if ($request->filled('age')) {
            $query->where('age', $request->age);
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $preTest = $query->paginate(10);

        // Pass the dropdown data too
        $ages = StudentQuiz::distinct()->orderBy('age')->pluck('age');
        $sections = StudentQuiz::distinct()->orderBy('section')->pluck('section');

        return response()->json([
            'table' => view('superadmin.pretest', compact('preTest', 'ages', 'sections'))->render()
        ]);
    }
}
