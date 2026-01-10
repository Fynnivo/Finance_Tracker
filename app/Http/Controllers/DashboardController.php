<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'month');
        
        // Calculate date range
        $dateRange = $this->getDateRange($filter);
        
        // Get balance
        $totalIncome = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', $dateRange)
            ->sum('amount');
            
        $totalExpense = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', $dateRange)
            ->sum('amount');
            
        $balance = $totalIncome - $totalExpense;
        
        // Financial health
        $healthStatus = $this->getFinancialHealth($totalIncome, $totalExpense);
        
        // Recent transactions
        $recentTransactions = Transaction::with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Expense by category (for chart)
        $expenseByCategory = Transaction::select('category_id', DB::raw('SUM(amount) as total'))
            ->where('type', 'expense')
            ->whereBetween('transaction_date', $dateRange)
            ->groupBy('category_id')
            ->with('category')
            ->get();
        
        // Budgets for current month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $budgets = Budget::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->with('category')
            ->get();
        
        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'healthStatus',
            'recentTransactions',
            'expenseByCategory',
            'budgets',
            'filter'
        ));
    }
    
    private function getDateRange($filter)
    {
        switch ($filter) {
            case 'today':
                return [Carbon::today(), Carbon::today()];
            case 'week':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'month':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'year':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            default:
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        }
    }
    
    private function getFinancialHealth($income, $expense)
    {
        // Cek jika income 0 dulu untuk menghindari pembagian dengan nol
        if ($income <= 0) {
            return [
                'status' => $expense > 0 ? 'danger' : 'success',
                'message' => $expense > 0 ? 'Cashflow Negatif! Tidak ada pemasukan.' : 'Belum ada data transaksi.',
                'icon' => $expense > 0 ? '⚠️' : 'ℹ️'
            ];
        }

        if ($expense > $income) {
            return [
                'status' => 'danger',
                'message' => 'Cashflow Negatif! Pengeluaran melebihi pemasukan',
                'icon' => '⚠️'
            ];
        } elseif (($expense / $income) > 0.8) { // Sekarang aman karena $income pasti > 0
            return [
                'status' => 'warning',
                'message' => 'Hati-hati! Kamu sudah menggunakan 80% dari pendapatan',
                'icon' => '⚡'
            ];
        } else {
            return [
                'status' => 'success',
                'message' => 'Kondisi Aman! Keuangan terkendali',
                'icon' => '✅'
            ];
        }
    }
}