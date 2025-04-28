<?php

namespace App\Http\Controllers;

use App\Models\LaundryLog;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaundryLogController extends Controller
{
    public function index()
    {
        $laundryLogs = LaundryLog::with(['customer', 'worker'])->latest()->get();
        return view('tenant.laundry_logs.index', compact('laundryLogs'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('tenant.laundry_logs.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'laundry_type' => 'required|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,washing,completed,picked_up',
            'location' => 'nullable|string|max:255'
        ]);

        $validatedData['worker_id'] = Auth::guard('worker')->id();

        LaundryLog::create($validatedData);

        return redirect()->route('tenant.laundry_logs.index')
            ->with('success', 'Laundry log created successfully.');
    }

    public function show(LaundryLog $laundryLog)
    {
        return view('tenant.laundry_logs.show', compact('laundryLog'));
    }

    public function edit(LaundryLog $laundryLog)
    {
        $customers = Customer::all();
        return view('tenant.laundry_logs.edit', compact('laundryLog', 'customers'));
    }

    public function update(Request $request, LaundryLog $laundryLog)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'laundry_type' => 'required|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,washing,completed,picked_up',
            'location' => 'nullable|string|max:255'
        ]);

        $laundryLog->update($validatedData);

        return redirect()->route('tenant.laundry_logs.index')
            ->with('success', 'Laundry log updated successfully.');
    }

    public function destroy(LaundryLog $laundryLog)
    {
        $laundryLog->delete();

        return redirect()->route('tenant.laundry_logs.index')
            ->with('success', 'Laundry log deleted successfully.');
    }
} 