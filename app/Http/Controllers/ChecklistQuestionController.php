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
            'details' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'parent_id' => ['nullable', 'integer', 'exists:checklist_questions,id'],
        ]);

        $parentId = $validated['parent_id'] ?? null;

        if ($parentId) {
            $parent = $category->questions()->find($parentId);
            if (! $parent) {
                return back()->withErrors(['parent_id' => 'Pertanyaan induk tidak valid.']);
            }

            // Batas maksimal hirarki: 2 tingkat (Parent -> Child).
            // Poin-poin strip (-) dimasukkan langsung pada field Rincian Child.
            if ($parent->parent_id !== null) {
                return back()->withErrors(['parent_id' => 'Maksimal hirarki adalah 2 tingkat (Parent dan Child). Poin rincian strip (-) dimasukkan langsung pada field Rincian Child.']);
            }
        }

        $nextOrderNo = (int) $category->questions()
            ->where('parent_id', $parentId)
            ->max('order_no') + 1;

        $category->questions()->create([
            'parent_id' => $parentId,
            'order_no' => $nextOrderNo,
            'question' => $validated['question'],
            'details' => $parentId ? ($validated['details'] ?? null) : null,
            'status' => $validated['status'],
        ]);

        $message = $parentId ? 'Sub-pertanyaan berhasil ditambahkan.' : 'Pertanyaan berhasil ditambahkan.';

        $category->load('productionLine');

        return redirect()
            ->route('checklist-config.index', ['line' => $category->productionLine, 'category' => $category])
            ->with('status', $message);
    }

    public function update(Request $request, ChecklistQuestion $question): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'details' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $question->update([
            'question' => $validated['question'],
            'details' => $question->parent_id !== null ? ($validated['details'] ?? null) : null,
            'status' => $validated['status'],
        ]);

        $category = $question->category()->with('productionLine')->first();

        return redirect()
            ->route('checklist-config.index', ['line' => $category->productionLine, 'category' => $category])
            ->with('status', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(ChecklistQuestion $question): RedirectResponse
    {
        $category = $question->category()->with('productionLine')->first();
        $question->delete();

        return redirect()
            ->route('checklist-config.index', ['line' => $category->productionLine, 'category' => $category])
            ->with('status', 'Pertanyaan berhasil dihapus.');
    }

    public function toggleStatus(ChecklistQuestion $question): RedirectResponse
    {
        $question->update([
            'status' => $question->status === 'active' ? 'inactive' : 'active',
        ]);

        $category = $question->category()->with('productionLine')->first();

        return redirect()
            ->route('checklist-config.index', ['line' => $category->productionLine, 'category' => $category])
            ->with('status', 'Status pertanyaan berhasil diperbarui.');
    }
}
