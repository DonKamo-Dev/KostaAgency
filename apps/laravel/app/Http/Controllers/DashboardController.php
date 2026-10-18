<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Lead;
use App\Models\Service;
use App\Models\SiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $since = now()->subMonths(5)->startOfMonth();
        $now = now();

        // ── 1. Real Web Traffic, Geolocation & Social Analytics ────
        $analyticsRange = $request->query('range', '30d');
        if (! in_array($analyticsRange, ['today', '7d', '30d', 'all'], true)) {
            $analyticsRange = '30d';
        }

        $uniqueVisitors = 0;
        $totalPageviews = 0;
        $todayVisitors = 0;
        $socialVisits = 0;
        $socialSharePct = 0;
        $mobileSharePct = 0;
        $topLocations = collect();
        $trafficSources = collect();
        $topPages = collect();

        if (SiteVisit::ensureTableExists()) {
            $visitsQuery = SiteVisit::query();

            if ($analyticsRange === 'today') {
                $visitsQuery->whereDate('created_at', $now->toDateString());
            } elseif ($analyticsRange === '7d') {
                $visitsQuery->where('created_at', '>=', $now->copy()->subDays(7));
            } elseif ($analyticsRange === '30d') {
                $visitsQuery->where('created_at', '>=', $now->copy()->subDays(30));
            }

            $totalPageviews = (clone $visitsQuery)->count();
            $uniqueVisitors = (clone $visitsQuery)->distinct('visitor_hash')->count('visitor_hash');

            $todayVisitors = SiteVisit::query()
                ->whereDate('created_at', $now->toDateString())
                ->distinct('visitor_hash')
                ->count('visitor_hash');

            $socialChannels = ['instagram', 'tiktok', 'facebook', 'whatsapp', 'linkedin', 'youtube', 'x'];
            $socialVisits = (clone $visitsQuery)
                ->whereIn('source_channel', $socialChannels)
                ->count();

            $socialSharePct = $totalPageviews > 0
                ? round(($socialVisits / $totalPageviews) * 100, 1)
                : 0;

            $mobileCount = (clone $visitsQuery)
                ->whereIn('device_type', ['mobile', 'tablet'])
                ->count();

            $mobileSharePct = $totalPageviews > 0
                ? round(($mobileCount / $totalPageviews) * 100, 1)
                : 0;

            // Top Locations (Country + City)
            $topLocations = (clone $visitsQuery)
                ->selectRaw('country_code, country_name, city_name, COUNT(*) as visits, COUNT(DISTINCT visitor_hash) as unique_visitors')
                ->groupBy('country_code', 'country_name', 'city_name')
                ->orderByDesc('visits')
                ->limit(6)
                ->get()
                ->map(function ($row) use ($totalPageviews) {
                    $flag = SiteVisit::flagEmoji($row->country_code);
                    $city = trim((string) $row->city_name);
                    $country = trim((string) ($row->country_name ?: 'Desconocido'));
                    $label = $city !== '' && $city !== 'Red Local'
                        ? "{$city}, {$country}"
                        : $country;

                    return (object) [
                        'flag'            => $flag,
                        'country_code'    => $row->country_code,
                        'country_name'    => $country,
                        'city_name'       => $city ?: 'General',
                        'label'           => $label,
                        'visits'          => (int) $row->visits,
                        'unique_visitors' => (int) $row->unique_visitors,
                        'pct'             => $totalPageviews > 0 ? round(($row->visits / $totalPageviews) * 100, 1) : 0,
                    ];
                });

            // Traffic Sources (Social Networks, Google, Direct, Referral)
            $trafficSources = (clone $visitsQuery)
                ->selectRaw('source_channel, COUNT(*) as visits, COUNT(DISTINCT visitor_hash) as unique_visitors')
                ->groupBy('source_channel')
                ->orderByDesc('visits')
                ->get()
                ->map(function ($row) use ($totalPageviews) {
                    $meta = SiteVisit::channelMeta((string) $row->source_channel);

                    return (object) [
                        'channel'         => $row->source_channel,
                        'label'           => $meta['label'],
                        'color'           => $meta['color'],
                        'is_social'       => $meta['is_social'],
                        'visits'          => (int) $row->visits,
                        'unique_visitors' => (int) $row->unique_visitors,
                        'pct'             => $totalPageviews > 0 ? round(($row->visits / $totalPageviews) * 100, 1) : 0,
                    ];
                });

            // Top Visited Public Pages
            $pageLabels = [
                '/'           => 'Inicio (Landing)',
                '/portafolio' => 'Portafolio & Casos',
                '/kamo'       => 'Perfil Kamo (Bento)',
                '/cv'         => 'CV / Perfil',
                '/perfil'     => 'Perfil',
                '/contacto'   => 'Contacto / Cotizar',
                '/privacidad' => 'Privacidad',
            ];

            $topPages = (clone $visitsQuery)
                ->selectRaw('path, COUNT(*) as visits, COUNT(DISTINCT visitor_hash) as unique_visitors')
                ->groupBy('path')
                ->orderByDesc('visits')
                ->limit(5)
                ->get()
                ->map(function ($row) use ($pageLabels, $totalPageviews) {
                    return (object) [
                        'path'            => $row->path,
                        'label'           => $pageLabels[$row->path] ?? $row->path,
                        'visits'          => (int) $row->visits,
                        'unique_visitors' => (int) $row->unique_visitors,
                        'pct'             => $totalPageviews > 0 ? round(($row->visits / $totalPageviews) * 100, 1) : 0,
                    ];
                });
        }

        // ── 2. Core Financial KPIs ─────────────────────────────────
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

        // ── 3. Operational Counts (Quotes, Invoices, Bills, Expenses) ──
        $quotesTotalCount = Document::query()
            ->where('type', 'quote')->where('status', '!=', 'cancelled')->count();

        $pendingQuotes = Document::query()
            ->where('type', 'quote')->where('status', 'pending')->count();

        $pendingQuotesValue = Document::query()
            ->where('type', 'quote')->where('status', 'pending')
            ->sum('total') ?? 0;

        $invoicesTotalCount = Document::query()
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')->count();

        $paidInvoicesCount = Document::query()
            ->where('type', 'invoice')->where('status', 'paid')->count();

        $overdueQuery = Document::query()
            ->where('type', 'invoice')
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', $now->toDateString());

        $overdueCount = (clone $overdueQuery)->count();
        $overdueAmount = (clone $overdueQuery)->selectRaw('SUM(total - paid) as amt')->value('amt') ?? 0;

        $billsTotalCount = Document::query()
            ->where('type', 'bill')->where('status', '!=', 'cancelled')->count();

        $pendingBillsCount = Document::query()
            ->where('type', 'bill')->whereNotIn('status', ['paid', 'cancelled'])->count();

        $pendingBillsAmount = Document::query()
            ->where('type', 'bill')->whereNotIn('status', ['paid', 'cancelled'])
            ->selectRaw('SUM(total - paid) as amt')->value('amt') ?? 0;

        $expensesTotalCount = Expense::count();

        // ── 4. Conversion rate ─────────────────────────────────────
        $totalQuotes = $quotesTotalCount;
        $convertedQuotes = Document::query()
            ->where('type', 'quote')->where('status', 'converted')->count();
        $conversionRate = $totalQuotes > 0
            ? round(($convertedQuotes / $totalQuotes) * 100, 1)
            : 0;

        // ── 5. Month-over-month income ─────────────────────────────
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

        // ── 6. Top clients ─────────────────────────────────────────
        $topClients = Document::query()
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->join('clients', 'clients.id', '=', 'documents.client_id')
            ->selectRaw('clients.name, SUM(documents.total) as revenue, COUNT(*) as invoice_count')
            ->groupBy('clients.id', 'clients.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // ── 7. Chart data (6 months) ───────────────────────────────
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

        // ── 8. Recent activity & general counts ────────────────────
        $totalClients = Client::count();
        $totalServices = Service::count();
        $totalLeadsCount = Lead::count();

        $recentDocuments = Document::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(8)->get();

        $monthGoal = 5000000;

        return view('dashboard.index', compact(
            'analyticsRange', 'uniqueVisitors', 'totalPageviews', 'todayVisitors',
            'socialVisits', 'socialSharePct', 'mobileSharePct',
            'topLocations', 'trafficSources', 'topPages',
            'totalInvoiced', 'totalCollected', 'totalExpenses', 'netProfit',
            'outstandingAmount',
            'quotesTotalCount', 'pendingQuotes', 'pendingQuotesValue',
            'invoicesTotalCount', 'paidInvoicesCount', 'overdueCount', 'overdueAmount',
            'billsTotalCount', 'pendingBillsCount', 'pendingBillsAmount',
            'expensesTotalCount',
            'conversionRate', 'convertedQuotes', 'totalQuotes',
            'thisMonthIncome', 'lastMonthIncome', 'incomeGrowthPct',
            'thisMonthExpenses', 'topClients', 'monthGoal',
            'totalClients', 'totalServices', 'totalLeadsCount', 'recentDocuments',
            'months', 'incomeData', 'expenseData', 'profitData'
        ));
    }
}
