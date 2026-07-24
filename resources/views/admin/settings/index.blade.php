@extends('layouts.admin')

@section('title', 'Settings - VendorConnect')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage your admin account and system preferences.')

@section('content')

<div class="grid gap-4" style="grid-template-columns: 1fr 1fr; align-items: start;">

    {{-- Profile Settings --}}
    <div class="card animate-on-scroll" style="transition-delay:0.1s;">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.75rem;padding-bottom:1.25rem;border-bottom:1px solid var(--border);">
            <div style="width:56px;height:56px;background:var(--primary);color:white;border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 style="margin-bottom:0.2rem;">{{ $user->name }}</h3>
                <span style="font-size:0.8rem;background:var(--primary-muted);color:var(--primary);padding:0.2rem 0.6rem;border-radius:9999px;font-weight:500;">Administrator</span>
            </div>
        </div>

        <h4 style="margin-bottom:1.25rem;color:var(--text-body);font-size:0.9rem;text-transform:uppercase;letter-spacing:0.05em;">Profile Information</h4>

        @if(session('success') && str_contains(session('success'), 'Profile'))
        <div style="background:var(--success-bg);border:1px solid var(--success);border-radius:var(--radius-md);padding:0.875rem;color:var(--success);margin-bottom:1.25rem;font-size:0.875rem;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('admin.settings.profile') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="color: #0f172a;">
                @error('name')<p style="color:var(--error);font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="color: #0f172a;">
                @error('email')<p style="color:var(--error);font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Account Created</label>
                <input type="text" class="form-control" value="{{ $user->created_at->format('d M Y, H:i') }}" disabled style="background:var(--background);cursor:not-allowed;color:var(--text-muted);">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Save Profile Changes</button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="card animate-on-scroll" style="transition-delay:0.2s;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.75rem;padding-bottom:1.25rem;border-bottom:1px solid var(--border);">
            <div style="width:42px;height:42px;background:var(--warning-bg);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:1.2rem;">🔑</div>
            <div>
                <h3 style="font-size:1rem;">Change Password</h3>
                <p style="font-size:0.8rem;color:var(--text-muted);">Ensure your account uses a strong password.</p>
            </div>
        </div>

        @if(session('success') && str_contains(session('success'), 'Password'))
        <div style="background:var(--success-bg);border:1px solid var(--success);border-radius:var(--radius-md);padding:0.875rem;color:var(--success);margin-bottom:1.25rem;font-size:0.875rem;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('admin.settings.password') }}" method="POST">
            @csrf
            
            {{-- Current Password --}}
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <div style="position: relative;">
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••" required style="padding-right: 45px; color: #0f172a;">
                    <button type="button" class="toggle-pwd" data-target="current_password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 0;">👁️</button>
                </div>
                @error('current_password')<p style="color:var(--error);font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
            </div>

            {{-- New Password --}}
            <div class="form-group">
                <label class="form-label">New Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="new_password" class="form-control" placeholder="Min. 8 characters" required style="padding-right: 45px; color: #0f172a;">
                    <button type="button" class="toggle-pwd" data-target="new_password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 0;">👁️</button>
                </div>
                @error('password')<p style="color:var(--error);font-size:0.8rem;margin-top:0.3rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Confirm New Password --}}
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="confirm_password" class="form-control" placeholder="••••••••" required style="padding-right: 45px; color: #0f172a;">
                    <button type="button" class="toggle-pwd" data-target="confirm_password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 0;">👁️</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Update Password</button>
        </form>
    </div>

</div>

{{-- System Info --}}
<div class="card animate-on-scroll" style="transition-delay:0.3s;margin-top:1rem;">
    <h4 style="margin-bottom:1.25rem;color:var(--text-body);font-size:0.9rem;text-transform:uppercase;letter-spacing:0.05em;">⚙️ System Information</h4>
    <div class="grid grid-cols-3 gap-4">
        <div style="padding:1rem;background:var(--background);border-radius:var(--radius-md);border:1px solid var(--border);">
            <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:0.05em;">Application</div>
            <div style="font-weight:600;color:var(--text-main);">VendorConnect v1.0</div>
        </div>
        <div style="padding:1rem;background:var(--background);border-radius:var(--radius-md);border:1px solid var(--border);">
            <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:0.05em;">Framework</div>
            <div style="font-weight:600;color:var(--text-main);">Laravel {{ app()->version() }}</div>
        </div>
        <div style="padding:1rem;background:var(--background);border-radius:var(--radius-md);border:1px solid var(--border);">
            <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:0.05em;">PHP Version</div>
            <div style="font-weight:600;color:var(--text-main);">{{ PHP_VERSION }}</div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-pwd').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const inputField = document.getElementById(targetId);
            
            const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
            inputField.setAttribute('type', type);
            
            this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
    });
</script>

@endsection