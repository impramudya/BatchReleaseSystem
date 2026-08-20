<?php

namespace App\Http\Controllers;

use App\Models\ChecklistCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChecklistCategoryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'production_line_id' => ['required', 'integer', 'exists:production_lines,id'],
            'code' => [
                'required', 'string', 'max:10',
                Rule::unique('checklist_categories', 'code')
                    ->where(fn ($query) => $query->where('production_line_id', $request->production_line_id)),
            ],
            'name' => ['required', 'string', 'max:255'],
        ], [
            'code.unique' => 'Kode kategori ini sudah dipakai di line yang sama, gunakan kode lain.',
            'production_line_id.required' => 'Pilih line produksi terlebih dahulu.',
        ]);

        $category = ChecklistCategory::create($validated);
        $category->load('productionLine');

        return redirect()
            ->route('checklist-config.index', ['line' => $category->productionLine, 'category' => $category])
            ->with('status', 'Kategori evaluasi berhasil ditambahkan.');
    }

    public function update(Request $request, ChecklistCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required', 'string', 'max:10',
                Rule::unique('checklist_categories', 'code')
                    ->where(fn ($query) => $query->where('production_line_id', $category->production_line_id))
                    ->ignore($category->id),
            ],
            'name' => ['required', 'string', 'max:255'],
        ], [
            'code.unique' => 'Kode kategori ini sudah dipakai di line yang sama, gunakan kode lain.',
        ]);

        $category->update($validated);
        $category->load('productionLine');

        return redirect()
            ->route('checklist-config.index', ['line' => $category->productionLine, 'category' => $category])
            ->with('status', 'Kategori evaluasi berhasil diperbarui.');
    }

    public function destroy(ChecklistCategory $category): RedirectResponse
    {
        $line = $category->productionLine;
        $category->delete();

        return redirect()
            ->route('checklist-config.index', ['line' => $line])
            ->with('status', 'Kategori evaluasi beserta pertanyaannya berhasil dihapus.');
    }
}
