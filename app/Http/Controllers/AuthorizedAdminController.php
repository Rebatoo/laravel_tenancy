<?php

namespace App\Http\Controllers;

use App\Models\AuthorizedAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizedAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = AuthorizedAdmin::with('addedBy');
        
        if ($request->has('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }
        
        $authorizedAdmins = $query->paginate(10);
        return view('authorized-admins.index', compact('authorizedAdmins'));
    }

    public function create()
    {
        return view('authorized-admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:authorized_admins,email'
        ]);

        AuthorizedAdmin::create([
            'email' => $request->email,
            'added_by' => Auth::id(),
            'is_active' => true
        ]);

        return redirect()->route('authorized-admins.index')
            ->with('success', 'Authorized admin email added successfully.');
    }

    public function destroy(AuthorizedAdmin $authorizedAdmin)
    {
        $authorizedAdmin->delete();
        return redirect()->route('authorized-admins.index')
            ->with('success', 'Authorized admin email removed successfully.');
    }

    public function toggleStatus(AuthorizedAdmin $authorizedAdmin)
    {
        $authorizedAdmin->update([
            'is_active' => !$authorizedAdmin->is_active
        ]);

        return redirect()->route('authorized-admins.index')
            ->with('success', 'Authorized admin status updated successfully.');
    }
} 