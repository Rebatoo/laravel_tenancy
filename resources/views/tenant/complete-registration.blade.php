@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Complete Your Registration</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tenant.complete-registration.post') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="domain" class="col-md-4 col-form-label text-md-right">Your Domain Name</label>
                            <div class="col-md-6">
                                <input id="domain" type="text" class="form-control" name="domain" required>
                                <small class="form-text text-muted">
                                    This will be your unique subdomain (e.g., yourdomain.{{ config('tenancy.central_domains')[0] }})
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Complete Registration
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
