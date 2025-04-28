<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantWorkerController extends Controller
{
    public function create()
    {
        return view('tenant.workers.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'nullable|email|unique:workers,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        Worker::create($validatedData);

        return redirect()->route('tenant.workers.index')->with('success', 'Worker created successfully!');
    }

    public function index()
    {
        $workers = Worker::all();
        return view('tenant.workers.index', compact('workers'));
    }
} 