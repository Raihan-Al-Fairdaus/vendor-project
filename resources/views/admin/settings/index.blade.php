@extends('layouts.admin')

@section('title', 'Settings - VendorConnect')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage your admin account and system preferences.')

@section('content')

{{-- Top Header --}}
<div class="admin-page-header" style="background-color: #1b3a60; padding: 1.75rem 2rem 1.5rem;">
    <h1 style="color: #ffffff; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.35rem 0; line-height: 1.2;">Settings</h1>
    <p style="color: rgba(255,255,255,0.55); font-size: 0.85rem; margin: 0;">Manage your admin account and system preferences.</p>
</div>

{{-- Two-Column Grid --}}
<div style="padding: 0 2rem 1.25rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start;">

    {{-- LEFT CARD: Profile Settings --}}
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
            <div style="width: 48px; height: 48px; background-color: #3b82f6; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 700; flex-shrink: 0;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 style="margin: 0 0 0.25rem 0; font-size: 1.1rem; font-weight: 700; color: #0f172a;">{{ $user->name }}</h3>
                <span style="font-size: 0.75rem; background-color: #e0f2fe; color: #0369a1; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 600;">Administrator</span>
            </div>
        </div>

        <h4 style="margin: 0 0 1.25rem 0; color: #64748b; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Profile Information</h4>

        @if(session('success') && str_contains(session('success'), 'Profile'))
        <div style="background-color: #d1fae5; border: 1px solid #10b981; border-radius: 6px; padding: 0.75rem 1rem; color: #065f46; margin-bottom: 1.25rem; font-size: 0.85rem;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('admin.settings.profile') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.4rem;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.6rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                @error('name')<p style="color: #ef4444; font-size: 0.8rem; margin: 0.35rem 0 0 0;">{{ $message }}</p>@enderror
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.4rem;">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.6rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                @error('email')<p style="color: #ef4444; font-size: 0.8rem; margin: 0.35rem 0 0 0;">{{ $message }}</p>@enderror
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.4rem;">Account Created</label>
                <input type="text" value="{{ $user->created_at->format('d M Y, H:i') }}" disabled style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.6rem 0.75rem; color: #64748b; background-color: #f8fafc; font-size: 0.85rem; cursor: not-allowed;">
            </div>
            <button type="submit" style="width: 100%; background-color: #1b3a60; color: #ffffff; border: none; border-radius: 6px; padding: 0.65rem 1.25rem; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Save Profile Changes</button>
        </form>
    </div>

    {{-- RIGHT CARD: Change Password --}}
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e2e8f0;">
            <div style="width: 42px; height: 42px; background-color: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">🔑</div>
            <div>
                <h3 style="margin: 0 0 0.2rem 0; font-size: 1rem; font-weight: 700; color: #0f172a;">Change Password</h3>
                <p style="margin: 0; font-size: 0.8rem; color: #64748b;">Ensure your account uses a strong password.</p>
            </div>
        </div>

        @if(session('success') && str_contains(session('success'), 'Password'))
        <div style="background-color: #d1fae5; border: 1px solid #10b981; border-radius: 6px; padding: 0.75rem 1rem; color: #065f46; margin-bottom: 1.25rem; font-size: 0.85rem;">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('admin.settings.password') }}" method="POST">
            @csrf
            
            {{-- Current Password --}}
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.4rem;">Current Password</label>
                <div style="position: relative;">
                    <input type="password" name="current_password" id="current_password" placeholder="••••••••" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.6rem 2.75rem 0.6rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                    <button type="button" class="toggle-pwd" data-target="current_password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center; color: #64748b;">👁️</button>
                </div>
                @error('current_password')<p style="color: #ef4444; font-size: 0.8rem; margin: 0.35rem 0 0 0;">{{ $message }}</p>@enderror
            </div>

            {{-- New Password --}}
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.4rem;">New Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="new_password" placeholder="Min. 8 characters" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.6rem 2.75rem 0.6rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                    <button type="button" class="toggle-pwd" data-target="new_password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center; color: #64748b;">👁️</button>
                </div>
                @error('password')<p style="color: #ef4444; font-size: 0.8rem; margin: 0.35rem 0 0 0;">{{ $message }}</p>@enderror
            </div>

            {{-- Confirm New Password --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0f172a; margin-bottom: 0.4rem;">Confirm New Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="confirm_password" placeholder="••••••••" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.6rem 2.75rem 0.6rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                    <button type="button" class="toggle-pwd" data-target="confirm_password" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center; color: #64748b;">👁️</button>
                </div>
            </div>

            <button type="submit" style="width: 100%; background-color: #1b3a60; color: #ffffff; border: none; border-radius: 6px; padding: 0.65rem 1.25rem; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Update Password</button>
        </form>
    </div>

</div>

{{-- System Info --}}
<div style="padding: 0 2rem 2rem;">
    <div style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem;">
        <h4 style="margin: 0 0 1.25rem 0; color: #0f172a; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.5rem;">
            ⚙️ System Information
        </h4>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div style="padding: 1rem; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.35rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">Application</div>
                <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">VendorConnect v1.0</div>
            </div>
            <div style="padding: 1rem; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.35rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">Framework</div>
                <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">Laravel {{ app()->version() }}</div>
            </div>
            <div style="padding: 1rem; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.35rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">PHP Version</div>
                <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">{{ PHP_VERSION }}</div>
            </div>
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