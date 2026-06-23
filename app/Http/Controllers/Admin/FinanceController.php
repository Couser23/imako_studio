<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $query = Payment::with(['booking.user', 'booking.package', 'verifier'])
            ->where('status', 'verified');

        $payments = clone $query;
        $payments = $payments->whereMonth('verified_at', $month)
                             ->whereYear('verified_at', $year)
                             ->orderBy('verified_at', 'desc')
                             ->paginate(15);
                             
        // Base month query for stats
        $monthQuery = clone $query;
        $monthQuery = $monthQuery->whereMonth('verified_at', $month)->whereYear('verified_at', $year);

        $monthlyRevenue = $monthQuery->sum('amount');
        
        $todayRevenue = Payment::where('status', 'verified')
            ->whereDate('verified_at', Carbon::today())
            ->sum('amount');

        // Hari aktif (Days with transactions)
        $activeDays = (clone $monthQuery)->selectRaw('DATE(verified_at) as date')
            ->groupBy('date')
            ->get()
            ->count();

        $avgPerDay = $activeDays > 0 ? $monthlyRevenue / $activeDays : 0;

        // Hari terbaik
        $bestDayObj = (clone $monthQuery)->selectRaw('DATE(verified_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderByDesc('total')
            ->first();
        $bestDay = $bestDayObj ? Carbon::parse($bestDayObj->date)->format('d') : '-';
        $bestDayAmount = $bestDayObj ? $bestDayObj->total : 0;

        // Uang Keluar Bulan ini
        $expenses = \App\Models\Expense::whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->orderBy('expense_date', 'desc')
            ->get();
        $totalExpenses = $expenses->sum('amount');

        // Pemasukan perhari (Array for 31 days)
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $dailyRevenue = array_fill(1, $daysInMonth, 0);
        
        $dailyStats = (clone $monthQuery)->selectRaw('DAY(verified_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->get();
            
        foreach ($dailyStats as $stat) {
            $dailyRevenue[$stat->day] = $stat->total;
        }

        // Pemasukan per minggu (Week 1, 2, 3, 4, 5)
        $weeklyRevenue = [
            'Minggu 1' => 0, 'Minggu 2' => 0, 'Minggu 3' => 0, 'Minggu 4' => 0, 'Minggu 5' => 0
        ];
        foreach ($dailyStats as $stat) {
            $week = ceil($stat->day / 7);
            if ($week > 5) $week = 5;
            $weeklyRevenue['Minggu ' . $week] += $stat->total;
        }

        // Per paket bulan ini
        $packageRevenue = \App\Models\Package::with(['bookings' => function($q) use ($month, $year) {
            $q->whereHas('payments', function($p) use ($month, $year) {
                $p->where('status', 'verified')
                  ->whereMonth('verified_at', $month)
                  ->whereYear('verified_at', $year);
            })->with('payments');
        }])->get()->map(function($package) {
            return [
                'name' => $package->name,
                'total' => $package->bookings->sum(function($booking) {
                    return $booking->payments->where('status', 'verified')->sum('amount');
                })
            ];
        })->sortByDesc('total')->values();

        // Rekap (Last month comparison)
        $lastMonth = Carbon::create($year, $month)->subMonth();
        $lastMonthRevenue = Payment::where('status', 'verified')
            ->whereMonth('verified_at', $lastMonth->month)
            ->whereYear('verified_at', $lastMonth->year)
            ->sum('amount');

        $difference = $monthlyRevenue - $lastMonthRevenue;
        $growth = $lastMonthRevenue > 0 ? ($difference / $lastMonthRevenue) * 100 : ($monthlyRevenue > 0 ? 100 : 0);
        $targetSetting = \App\Models\Setting::where('key', 'monthly_target')->first();
        $target = $targetSetting ? (float) $targetSetting->value : 10000000;

        $dateContext = Carbon::create($year, $month);

        // Breakdown Hari ini
        $todayMethodsData = Payment::with('paymentMethod')
            ->where('status', 'verified')
            ->whereDate('verified_at', Carbon::today())
            ->selectRaw('payment_method_id, SUM(amount) as total')
            ->groupBy('payment_method_id')
            ->get();

        $todayMethods = $todayMethodsData->map(function ($payment) {
            return (object)[
                'name' => $payment->paymentMethod ? $payment->paymentMethod->name : 'Lainnya',
                'total' => $payment->total,
            ];
        });

        // --- TAHUNAN STATS ---
        $yearQuery = Payment::where('status', 'verified')->whereYear('verified_at', $year);
        $yearlyRevenue = (clone $yearQuery)->sum('amount');
        
        $activeMonths = (clone $yearQuery)->selectRaw('MONTH(verified_at) as month')->groupBy('month')->get()->count();
        $avgPerMonth = $activeMonths > 0 ? $yearlyRevenue / $activeMonths : 0;
        
        $bestMonthObj = (clone $yearQuery)->selectRaw('MONTH(verified_at) as month, SUM(amount) as total')->groupBy('month')->orderByDesc('total')->first();
        $bestMonth = $bestMonthObj ? Carbon::create()->month($bestMonthObj->month)->translatedFormat('F') : '-';
        $bestMonthAmount = $bestMonthObj ? $bestMonthObj->total : 0;
        
        $yearlyExpenseList = \App\Models\Expense::whereYear('expense_date', $year)->orderBy('expense_date', 'desc')->get();
        $yearlyExpenses = $yearlyExpenseList->sum('amount');
        
        $lastYearRevenue = Payment::where('status', 'verified')->whereYear('verified_at', $year - 1)->sum('amount');
        $yearlyGrowth = $lastYearRevenue > 0 ? (($yearlyRevenue - $lastYearRevenue) / $lastYearRevenue) * 100 : ($yearlyRevenue > 0 ? 100 : 0);
        
        $monthlyRevenues = array_fill(1, 12, 0);
        $monthlyStats = (clone $yearQuery)->selectRaw('MONTH(verified_at) as month, SUM(amount) as total')->groupBy('month')->get();
        foreach($monthlyStats as $stat) {
            $monthlyRevenues[$stat->month] = $stat->total;
        }

        return view('admin.finance.index', compact(
            'payments', 'monthlyRevenue', 'todayRevenue', 'activeDays', 'avgPerDay',
            'bestDay', 'bestDayAmount', 'expenses', 'totalExpenses', 'dailyRevenue', 'daysInMonth',
            'weeklyRevenue', 'packageRevenue', 'lastMonthRevenue', 'difference', 'growth', 'target',
            'dateContext', 'todayMethods', 'year',
            'yearlyRevenue', 'activeMonths', 'avgPerMonth', 'bestMonth', 'bestMonthAmount', 
            'yearlyExpenses', 'yearlyExpenseList', 'lastYearRevenue', 'yearlyGrowth', 'monthlyRevenues'
        ));
    }

    public function updateTarget(Request $request)
    {
        $request->validate([
            'target' => 'required|numeric|min:0',
            'type' => 'nullable|string|in:monthly,yearly'
        ]);

        $type = $request->input('type', 'monthly');
        $key = $type === 'yearly' ? 'yearly_target' : 'monthly_target';
        $desc = $type === 'yearly' ? 'Target pemasukan tahunan' : 'Target pemasukan bulanan';

        \App\Models\Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $request->target, 'description' => $desc]
        );

        return back()->with('success', 'Target berhasil diperbarui.');
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'category' => 'required|string|max:255',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        \App\Models\Expense::create([
            'amount' => $request->amount,
            'category' => $request->category,
            'expense_date' => $request->expense_date,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Pengeluaran berhasil ditambahkan!');
    }
    public function print(Request $request)
    {
        $type = $request->input('type', 'daily');
        $date = $request->input('date', \Carbon\Carbon::today()->toDateString());
        $month = $request->input('month', \Carbon\Carbon::now()->month);
        $year = $request->input('year', \Carbon\Carbon::now()->year);

        $dateContext = \Carbon\Carbon::create($year, $month, 1);
        $targetDate = \Carbon\Carbon::parse($date);

        $payments = collect();
        $expenses = collect();
        $title = '';

        if ($type === 'daily') {
            $title = 'Laporan Harian (' . $targetDate->translatedFormat('d F Y') . ')';
            $payments = \App\Models\Payment::with(['booking.user', 'booking.package'])
                ->where('status', 'verified')
                ->whereDate('verified_at', $targetDate)
                ->orderBy('verified_at')
                ->get();
            $expenses = \App\Models\Expense::whereDate('expense_date', $targetDate)->orderBy('expense_date')->get();
        } elseif ($type === 'monthly') {
            $title = 'Laporan Bulanan (' . $dateContext->translatedFormat('F Y') . ')';
            $payments = \App\Models\Payment::with(['booking.user', 'booking.package'])
                ->where('status', 'verified')
                ->whereYear('verified_at', $year)
                ->whereMonth('verified_at', $month)
                ->orderBy('verified_at')
                ->get();
            $expenses = \App\Models\Expense::whereYear('expense_date', $year)
                ->whereMonth('expense_date', $month)
                ->orderBy('expense_date')
                ->get();
        } elseif ($type === 'yearly') {
            $title = 'Laporan Tahunan (' . $year . ')';
            $payments = \App\Models\Payment::with(['booking.user', 'booking.package'])
                ->where('status', 'verified')
                ->whereYear('verified_at', $year)
                ->orderBy('verified_at')
                ->get();
            $expenses = \App\Models\Expense::whereYear('expense_date', $year)
                ->orderBy('expense_date')
                ->get();
        }

        $totalRevenue = $payments->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netIncome = $totalRevenue - $totalExpense;

        return view('admin.finance.print', compact(
            'type', 'title', 'payments', 'expenses', 
            'totalRevenue', 'totalExpense', 'netIncome'
        ));
    }
}
