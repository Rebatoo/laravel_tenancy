@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registration Pending</div>
                <div class="card-body">
                    <p>Your registration is under review. An admin will approve your account and send you login credentials shortly.</p>
                    <p>Check your email (<strong>{{ session('google_tenant_data.email') ?? 'your email' }}</strong>) for updates.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 