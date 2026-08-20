<?php

namespace App\Http\Controllers;

use App\Models\ChecklistCategory;
use App\Models\ProductionLine;

class ChecklistConfigController extends Controller
{
    public function index(?ProductionLine $line = null, ?ChecklistCategory $category = null)
    {
        $lines = ProductionLine::topLevel()->with('children')->orderBy('order_no')->get();

        $selectedLine = $line ?? $lines
            ->flatMap(fn ($l) => $l->children->isNotEmpty() ? $l->children : [$l])
            ->first();

        $categories = $selectedLine
            ? ChecklistCategory::where('production_line_id', $selectedLine->id)
                ->withCount('questions')
                ->orderBy('code')
                ->get()
            : collect();

        $selectedCategory = $category ?? $categories->first();

        $questions = $selectedCategory
            ? $selectedCategory->questions()
                ->topLevel()
                ->with('children')
                ->orderBy('order_no')
                ->get()
            : collect();

        return view('checklist-config.index', [
            'lines' => $lines,
            'selectedLine' => $selectedLine,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'questions' => $questions,
        ]);
    }
}
