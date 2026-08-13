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
            'code' => ['required', 'string', 'max:10', 'unique:checklist_categories,code'],
            'name' => ['required', 'string', 'max:255'],
        ], [
            'code.unique' => 'Kode kategori ini sudah dipakai, gunakan kode lain.',
        ]);

        $category = ChecklistCategory::create($validated);

        return redirect()
            ->route('checklist-config.index', $category)
            ->with('status', 'Kategori evaluasi berhasil ditambahkan.');
    }

    public function update(Request $request, ChecklistCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required', 'string', 'max:10',
                Rule::unique('checklist_categories', 'code')->ignore($category->id),
            ],
            'name' => ['required', 'string', 'max:255'],
        ], [
            'code.unique' => 'Kode kategori ini sudah dipakai, gunakan kode lain.',
        ]);

        $category->update($validated);

        return redirect()
            ->route('checklist-config.index', $category)
            ->with('status', 'Kategori evaluasi berhasil diperbarui.');
    }

    public function destroy(ChecklistCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('checklist-config.index')
            ->with('status', 'Kategori evaluasi beserta pertanyaannya berhasil dihapus.');
    }
}
