<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $since = now()->subMonths(5)->startOfMonth();
        $now = now();

        // ── Core KPIs ──────────────────────────────────────────────
        $totalInvoiced = Document::query()
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->sum('total') ?? 0;

        $totalCollected = Document::query()
            ->where('type', 'invoice')
            ->where('status', '!=', 'cancelled')
            ->sum('paid') ?? 0;

        $totalExpenses = Expense::sum('amount') ?? 0;

        $netProfit = $totalCollected - $totalExpenses;
        $outstandingAmount = max(0, $totalInvoiced - $totalCollected);

        // ── Pending quotes ─────────────────────────────────────────
        $pendingQuotes = Document::query()
            ->where('type', 'quote')->where('status', 'pending')->count();

        $pendingQuotesValue = Document::query()
            ->where('type', 'quote')->where('status', 'pending')
            ->sum('total') ?? 0;

        // ── Overdue invoices ───────────────────────────────────────
        $overdueQuery = Document::query()
            ->where('type', 'invoice')
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', $now->toDateString());

        $overdueCount = (clone $overdueQuery)->count();
        $overdueAmount = (clone $overdueQuery)->selectRaw('SUM(total - paid) as amt')->value('amt') ?? 0;

        // ── Conversion rate ────────────────────────────────────────
        $totalQuotes = Document::query()
            ->where('type', 'quote')->whereNotIn('status', ['cancelled'])->count();
        $convertedQuotes = Document::query()
            ->where('type', 'quote')->where('status', 'converted')->count();
        $conversionRate = $totalQuotes > 0
            ? round(($convertedQuotes / $totalQuotes) * 100, 1)
            : 0;

        // ── Month-over-month income ────────────────────────────────
        $thisMonthIncome = Document::query()
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->whereYear('date', $now->year)->whereMonth('date', $now->month)
            ->sum('total') ?? 0;

        $lastMonth = $now->copy()->subMonthNoOverflow();
        $lastMonthIncome = Document::query()
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->whereYear('date', $lastMonth->year)
            ->whereMonth('date', $lastMonth->month)
            ->sum('total') ?? 0;

        $incomeGrowthPct = $lastMonthIncome > 0
            ? round((($thisMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100, 1)
            : null;

        $thisMonthExpenses = Expense::query()
            ->whereYear('date', $now->year)->whereMonth('date', $now->month)
            ->sum('amount') ?? 0;

        // ── Top clients ────────────────────────────────────────────
        $topClients = Document::query()
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->join('clients', 'clients.id', '=', 'documents.client_id')
            ->selectRaw('clients.name, SUM(documents.total) as revenue, COUNT(*) as invoice_count')
            ->groupBy('clients.id', 'clients.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // ── Chart data (6 months) ──────────────────────────────────
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $yearMonthSelect = $isSqlite
            ? "strftime('%Y', date) as year, CAST(strftime('%m', date) AS INTEGER) as month"
            : 'YEAR(date) as year, MONTH(date) as month';

        $incomeByMonth = Document::query()
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->where('date', '>=', $since)
            ->selectRaw("{$yearMonthSelect}, SUM(total) as total")
            ->groupBy('year', 'month')->get()
            ->keyBy(fn ($r) => "{$r->year}-{$r->month}");

        $expensesByMonth = Expense::query()
            ->where('date', '>=', $since)
            ->selectRaw("{$yearMonthSelect}, SUM(amount) as total")
            ->groupBy('year', 'month')->get()
            ->keyBy(fn ($r) => "{$r->year}-{$r->month}");

        $months = $incomeData = $expenseData = $profitData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->locale('es')->isoFormat('MMM');
            $key = "{$date->year}-{$date->month}";
            $inc = (float) ($incomeByMonth[$key]->total ?? 0);
            $exp = (float) ($expensesByMonth[$key]->total ?? 0);
            $incomeData[] = $inc;
            $expenseData[] = $exp;
            $profitData[] = round($inc - $exp, 2);
        }

        // ── Recent activity ────────────────────────────────────────
        $totalClients = Client::count();
        $totalServices = Service::count();

        $recentDocuments = Document::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(8)->get();

        $monthGoal = 5000;

        return view('dashboard.index', compact(
            'totalInvoiced', 'totalCollected', 'totalExpenses', 'netProfit',
            'outstandingAmount', 'pendingQuotes', 'pendingQuotesValue',
            'overdueCount', 'overdueAmount',
            'conversionRate', 'convertedQuotes', 'totalQuotes',
            'thisMonthIncome', 'lastMonthIncome', 'incomeGrowthPct',
            'thisMonthExpenses', 'topClients', 'monthGoal',
            'totalClients', 'totalServices', 'recentDocuments',
            'months', 'incomeData', 'expenseData', 'profitData'
        ));
    }
}
