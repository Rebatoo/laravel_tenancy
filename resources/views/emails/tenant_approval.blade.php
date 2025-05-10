<p>Hello {{ $tenant->name }},</p>
<p>Your LaundryHub tenant account has been approved. Here are your login details:</p>
<ul>
    <li>Email: {{ $tenant->email }}</li>
    <li>Password: {{ $password }}</li>
    <li>Login URL: <a href="{{ route('tenant.login') }}">{{ route('tenant.login') }}</a></li>
</ul>
<p>Please change your password after logging in.</p> 