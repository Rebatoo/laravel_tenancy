<?php

namespace App\Http\Controllers;

use App\Models\LaundryLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Get all laundry logs with related customer data
        $laundryLogs = LaundryLog::with('customer')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate some basic analytics
        $totalOrders = $laundryLogs->count();
        $totalRevenue = $laundryLogs->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('tenant.analytics', [
            'laundryLogs' => $laundryLogs,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'averageOrderValue' => $averageOrderValue
        ]);
    }

    public function downloadPdf()
    {
        $laundryLogs = LaundryLog::with(['customer', 'worker'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = PDF::loadView('tenant.analytics-pdf', [
            'laundryLogs' => $laundryLogs
        ]);

        return $pdf->download('analytics-report.pdf');
    }
} 