<?php

namespace App\Http\Controllers;

use App\Models\ChecklistCategory;

class ChecklistConfigController extends Controller
{
    public function index(?ChecklistCategory $category = null)
    {
        $categories = ChecklistCategory::withCount('questions')
            ->orderBy('code')
            ->get();

        $selectedCategory = $category ?? $categories->first();

        $questions = $selectedCategory
            ? $selectedCategory->questions()->orderBy('order_no')->get()
            : collect();

        return view('checklist-config.index', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'questions' => $questions,
        ]);
    }
}