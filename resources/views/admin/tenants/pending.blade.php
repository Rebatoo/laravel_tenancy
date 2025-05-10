@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Pending Tenant Registrations</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Domain</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendingTenants as $tenant)
                <tr>
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->email }}</td>
                    <td>{{ $tenant->domain_name }}.{{ config('tenancy.central_domains')[0] }}</td>
                    <td>
                        <form action="{{ route('admin.tenants.approve', $tenant) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Approve & Send Password</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 