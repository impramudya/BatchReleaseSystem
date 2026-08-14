<?php

namespace App\Http\Controllers;

use App\Models\ChecklistCategory;
use App\Models\ChecklistQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChecklistQuestionController extends Controller
{
    public function store(Request $request, ChecklistCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'parent_id' => ['nullable', 'integer', 'exists:checklist_questions,id'],
        ]);

        $parentId = $validated['parent_id'] ?? null;

        if ($parentId) {
            $parentExists = $category->questions()->where('id', $parentId)->exists();
            if (! $parentExists) {
                return back()->withErrors(['parent_id' => 'Pertanyaan induk tidak valid.']);
            }
        }

        $nextOrderNo = (int) $category->questions()
            ->where('parent_id', $parentId)
            ->max('order_no') + 1;

        $category->questions()->create([
            'parent_id' => $parentId,
            'order_no' => $nextOrderNo,
            'question' => $validated['question'],
            'status' => $validated['status'],
        ]);

        $message = $parentId ? 'Sub-pertanyaan berhasil ditambahkan.' : 'Pertanyaan berhasil ditambahkan.';

        return redirect()
            ->route('checklist-config.index', $category)
            ->with('status', $message);
    }

    public function update(Request $request, ChecklistQuestion $question): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $question->update($validated);

        return redirect()
            ->route('checklist-config.index', $question->checklist_category_id)
            ->with('status', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(ChecklistQuestion $question): RedirectResponse
    {
        $categoryId = $question->checklist_category_id;
        $question->delete();

        return redirect()
            ->route('checklist-config.index', $categoryId)
            ->with('status', 'Pertanyaan berhasil dihapus.');
    }

    public function toggleStatus(ChecklistQuestion $question): RedirectResponse
    {
        $question->update([
            'status' => $question->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()
            ->route('checklist-config.index', $question->checklist_category_id)
            ->with('status', 'Status pertanyaan berhasil diperbarui.');
    }
}
