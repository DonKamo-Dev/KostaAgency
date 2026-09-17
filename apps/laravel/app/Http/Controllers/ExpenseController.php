<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $page = max(1, (int) $request->get('page', 1));

        $query = Expense::query();

        if ($search) {
            $s = $search;
            $query->where(function ($q) use ($s) {
                $q->where('category', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $expenses = $query->orderBy('date', 'desc')->paginate(10, ['*'], 'page', $page);

        $monthlyTotal = Expense::query()
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->sum('amount');

        $categories = Expense::query()
            ->distinct()->pluck('category')->filter()->values();

        $mappedExpenses = $expenses->map(fn ($e) => [
            'id' => $e->id,
            'date' => $e->date ? $e->date->format('d/m/Y') : '—',
            'date_raw' => $e->date ? $e->date->format('Y-m-d') : '',
            'category' => $e->category,
            'description' => $e->description,
            'amount' => $e->amount,
        ]);

        $meta = [
            'current_page' => $expenses->currentPage(),
            'last_page' => $expenses->lastPage(),
            'total' => $expenses->total(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $mappedExpenses,
                'meta' => $meta,
                'monthly_total' => $monthlyTotal,
                'categories' => $categories,
            ]);
        }

        return view('expenses.index', [
            'monthlyTotal' => $monthlyTotal,
            'categories' => $categories,
            'initialExpenses' => $mappedExpenses,
            'initialMeta' => $meta,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
        ]);

        Expense::create($data);

        return response()->json(['message' => 'Gasto registrado']);
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $expense->update($data);

        return response()->json(['message' => 'Gasto actualizado']);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json(['message' => 'Gasto eliminado']);
    }
}
