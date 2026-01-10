<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $budgets = Budget::where('month', $month)
            ->where('year', $year)
            ->with('category')
            ->get();

        $categories = Category::where('type', 'expense')->get();

        return view('budgets.index', compact('budgets', 'categories', 'month', 'year'));
    }

    public function create()
    {
        $categories = Category::where('type', 'expense')
            ->whereNotIn('id', function($query) {
                $query->select('category_id')
                    ->from('budgets')
                    ->where('month', Carbon::now()->month)
                    ->where('year', Carbon::now()->year);
            })
            ->get();

        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000'
        ]);

        // Check if budget already exists
        $exists = Budget::where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Budget untuk kategori ini sudah ada di bulan yang dipilih!')
                ->withInput();
        }

        Budget::create($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Budget berhasil ditambahkan!');
    }

    public function edit(Budget $budget)
    {
        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000'
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Budget berhasil diupdate!');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Budget berhasil dihapus!');
    }
}