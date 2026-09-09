with open(r'C:\laragon\www\vendor-project\resources\views\admin\settings\index.blade.php', 'w', encoding='utf-8') as f:
    f.write('''@extends('layouts.admin')

@section('title', 'Settings - VendorConnect')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage your admin account and system preferences.')

@section('content')

<style>
    /* Kunci layar agar tidak bisa di-scroll pada halaman ini */
    .main-content {
        overflow: hidden !important;
    }
    
    /* Responsive tweaks untuk layar yang sangat kecil */
    @media (max-height: 700px) {
        .settings-card { padding: 1rem !important; }
        .settings-field { margin-bottom: 0.75rem !important; }
        .settings-header { padding-bottom: 0.75rem !important; margin-bottom: 0.75rem !important; }
    }
</style>

{{-- Container Utama Flexbox (mengisi penuh 100% tinggi main-content) --}}
<div style="display: flex; flex-direction: column; height: 100%; width: 100%;">

    {{-- Top Header (Tetap diam di atas) --}}
    <div class="admin-page-header" style="background-color: #1b3a60; padding: 1.25rem 2rem; flex-shrink: 0;">
        <h1 style="color: #ffffff; font-size: 1.4rem; font-weight: 700; margin: 0 0 0.25rem 0; line-height: 1.2;">Settings</h1>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.8rem; margin: 0;">Manage your admin account and system preferences.</p>
    </div>

    {{-- Area Konten Utama (Mengisi sisa layar) --}}
    <div style="flex: 1; min-height: 0; padding: 1.25rem 2rem; display: flex; flex-direction: column; gap: 1.25rem;">
        
        {{-- Baris Atas: Profil & Password (Bisa scroll di dalam card jika layar terlalu pendek) --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; flex: 1; min-height: 0;">
            
            {{-- KIRI: Profile Settings --}}
            <div class="settings-card" style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; overflow-y: auto;">
                <div class="settings-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0; flex-shrink: 0;">
                    <div style="width: 44px; height: 44px; background-color: #3b82f6; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 700; flex-shrink: 0;">
                        {{ strtoupper(substr(->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="margin: 0 0 0.2rem 0; font-size: 1rem; font-weight: 700; color: #0f172a;">{{ ->name }}</h3>
                        <span style="font-size: 0.7rem; background-color: #e0f2fe; color: #0369a1; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 600;">Administrator</span>
                    </div>
                </div>

                <h4 style="margin: 0 0 1rem 0; color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; flex-shrink: 0;">Profile Information</h4>

                @if(session('success') && str_contains(session('success'), 'Profile'))
                <div style="background-color: #d1fae5; border: 1px solid #10b981; border-radius: 6px; padding: 0.6rem 0.85rem; color: #065f46; margin-bottom: 1rem; font-size: 0.8rem; flex-shrink: 0;">
                    <i class="fa-solid fa-check" style="margin-right: 5px;"></i> {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.settings.profile') }}" method="POST" style="display: flex; flex-direction: column; flex: 1;">
                    @csrf
                    <div class="settings-field" style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.35rem;">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', ->name) }}" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                        @error('name')<p style="color: #ef4444; font-size: 0.75rem; margin: 0.25rem 0 0 0;">{{  }}</p>@enderror
                    </div>
                    <div class="settings-field" style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.35rem;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', ->email) }}" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                        @error('email')<p style="color: #ef4444; font-size: 0.75rem; margin: 0.25rem 0 0 0;">{{  }}</p>@enderror
                    </div>
                    <div class="settings-field" style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.35rem;">Account Created</label>
                        <input type="text" value="{{ ->created_at->format('d M Y, H:i') }}" disabled style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 0.75rem; color: #64748b; background-color: #f8fafc; font-size: 0.85rem; cursor: not-allowed;">
                    </div>
                    
                    <div style="margin-top: auto;">
                        <button type="submit" style="width: 100%; background-color: #1b3a60; color: #ffffff; border: none; border-radius: 6px; padding: 0.6rem 1.25rem; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Save Profile Changes</button>
                    </div>
                </form>
            </div>

            {{-- KANAN: Change Password --}}
            <div class="settings-card" style="background-color: #ffffff; border-radius: 10px; padding: 1.5rem; display: flex; flex-direction: column; overflow-y: auto;">
                <div class="settings-header" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0; flex-shrink: 0;">
                    <div style="width: 40px; height: 40px; background-color: #fef3c7; color: #d97706; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0 0 0.15rem 0; font-size: 0.95rem; font-weight: 700; color: #0f172a;">Change Password</h3>
                        <p style="margin: 0; font-size: 0.75rem; color: #64748b;">Ensure your account uses a strong password.</p>
                    </div>
                </div>

                @if(session('success') && str_contains(session('success'), 'Password'))
                <div style="background-color: #d1fae5; border: 1px solid #10b981; border-radius: 6px; padding: 0.6rem 0.85rem; color: #065f46; margin-bottom: 1rem; font-size: 0.8rem; flex-shrink: 0;">
                    <i class="fa-solid fa-check" style="margin-right: 5px;"></i> {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.settings.password') }}" method="POST" style="display: flex; flex-direction: column; flex: 1;">
                    @csrf
                    
                    <div class="settings-field" style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.35rem;">Current Password</label>
                        <div style="position: relative;">
                            <input type="password" name="current_password" id="current_password" placeholder="••••••••" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 2.5rem 0.5rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                            <button type="button" class="toggle-pwd" data-target="current_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1rem; padding: 0; display: flex; align-items: center; justify-content: center; color: #64748b;"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        @error('current_password')<p style="color: #ef4444; font-size: 0.75rem; margin: 0.25rem 0 0 0;">{{  }}</p>@enderror
                    </div>

                    <div class="settings-field" style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.35rem;">New Password</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="new_password" placeholder="Min. 8 characters" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 2.5rem 0.5rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                            <button type="button" class="toggle-pwd" data-target="new_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1rem; padding: 0; display: flex; align-items: center; justify-content: center; color: #64748b;"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        @error('password')<p style="color: #ef4444; font-size: 0.75rem; margin: 0.25rem 0 0 0;">{{  }}</p>@enderror
                    </div>

                    <div class="settings-field" style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #0f172a; margin-bottom: 0.35rem;">Confirm New Password</label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirmation" id="confirm_password" placeholder="••••••••" required style="width: 100%; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.5rem 2.5rem 0.5rem 0.75rem; color: #0f172a; background-color: #ffffff; font-size: 0.85rem; outline: none;">
                            <button type="button" class="toggle-pwd" data-target="confirm_password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1rem; padding: 0; display: flex; align-items: center; justify-content: center; color: #64748b;"><i class="fa-solid fa-eye"></i></button>
                        </div>
                    </div>
                    
                    <div style="margin-top: auto;">
                        <button type="submit" style="width: 100%; background-color: #1b3a60; color: #ffffff; border: none; border-radius: 6px; padding: 0.6rem 1.25rem; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Baris Bawah: System Info (Tetap diam di bawah) --}}
        <div style="background-color: #ffffff; border-radius: 10px; padding: 1.25rem 1.5rem; flex-shrink: 0;">
            <h4 style="margin: 0 0 1rem 0; color: #0f172a; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-server" style="color: #64748b;"></i> System Information
            </h4>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div style="padding: 0.85rem; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">Application</div>
                    <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">VendorConnect v1.0</div>
                </div>
                <div style="padding: 0.85rem; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">Framework</div>
                    <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">Laravel {{ app()->version() }}</div>
                </div>
                <div style="padding: 0.85rem; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 0.25rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">PHP Version</div>
                    <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">{{ PHP_VERSION }}</div>
                </div>
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
            
            // Toggle FontAwesome class
            const icon = this.querySelector('i');
            if(type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });
</script>

@endsection
''')
