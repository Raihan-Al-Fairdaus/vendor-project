<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DevApiController extends Controller
{
    /**
     * Get Developer configuration credentials.
     */
    protected function getDevCredentials(): array
    {
        return [
            'email'      => env('DEV_EMAIL', 'developer@dna-advertising.com'),
            'password'   => env('DEV_PASSWORD', 'DevDNA@2026!'),
            'secret_key' => env('DEV_SECRET_KEY', 'dna_dev_sec_2026_secret_key_dna_portal'),
        ];
    }

    /**
     * Validate developer request authorization via Bearer Token or Secret Key.
     */
    protected function authorizeDev(Request $request): ?JsonResponse
    {
        $creds = $this->getDevCredentials();

        // 1. Check direct Secret Key header
        $headerKey = $request->header('X-Dev-Secret-Key');
        if ($headerKey && hash_equals($creds['secret_key'], $headerKey)) {
            return null; // Authorized
        }

        // 2. Check Bearer Token
        $authHeader = $request->header('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            if ($this->verifyToken($token)) {
                return null; // Authorized
            }
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Unauthorized: Akses khusus Developer ditolak. Secret key atau token tidak valid.',
        ], 401);
    }

    /**
     * Generate signed token for developer session.
     */
    protected function generateToken(string $email): string
    {
        $expires = time() + (86400 * 7); // 7 days
        $payload = base64_encode(json_encode(['email' => $email, 'exp' => $expires]));
        $signature = hash_hmac('sha256', $payload, config('app.key') ?: 'dna_portal_fallback_key');
        return $payload . '.' . $signature;
    }

    /**
     * Verify signed token.
     */
    protected function verifyToken(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return false;
        }

        [$payloadB64, $signature] = $parts;
        $expectedSig = hash_hmac('sha256', $payloadB64, config('app.key') ?: 'dna_portal_fallback_key');
        if (!hash_equals($expectedSig, $signature)) {
            return false;
        }

        $data = json_decode(base64_decode($payloadB64), true);
        if (!$data || !isset($data['exp']) || $data['exp'] < time()) {
            return false;
        }

        $creds = $this->getDevCredentials();
        return isset($data['email']) && strtolower($data['email']) === strtolower($creds['email']);
    }

    /**
     * POST /api/developer/login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'      => ['required', 'string'],
            'password'   => ['required', 'string'],
            'secret_key' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $creds = $this->getDevCredentials();

        // Check secret key if provided
        $providedKey = $request->input('secret_key') ?: $request->header('X-Dev-Secret-Key');
        if ($providedKey && !hash_equals($creds['secret_key'], $providedKey)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Developer Secret Key salah.',
            ], 403);
        }

        // Verify Developer Email & Password
        if (
            strtolower(trim($request->email)) !== strtolower($creds['email']) ||
            $request->password !== $creds['password']
        ) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email atau Password Developer salah.',
            ], 401);
        }

        $token = $this->generateToken($creds['email']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Selamat datang di Developer Portal DNA Advertising.',
            'data'    => [
                'token'      => $token,
                'email'      => $creds['email'],
                'role'       => 'Super Developer',
                'server_time'=> now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /api/developer/stats
     */
    public function stats(Request $request): JsonResponse
    {
        if ($authErr = $this->authorizeDev($request)) return $authErr;

        $totalAdmins = User::count();
        $totalLogs   = AdminLoginLog::count();
        $successLogs = AdminLoginLog::where('status', 'success')->count();
        $failedLogs  = AdminLoginLog::where('status', 'failed')->count();
        $todayLogs   = AdminLoginLog::whereDate('created_at', now()->toDateString())->count();
        $lastLog     = AdminLoginLog::latest()->first();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_admins'     => $totalAdmins,
                'total_logs'       => $totalLogs,
                'successful_logs'  => $successLogs,
                'failed_logs'      => $failedLogs,
                'today_logs'       => $todayLogs,
                'last_login_at'    => $lastLog ? $lastLog->created_at->format('Y-m-d H:i:s') : null,
                'last_login_email' => $lastLog ? $lastLog->email : null,
            ],
        ]);
    }

    /**
     * GET /api/developer/logs
     */
    public function logs(Request $request): JsonResponse
    {
        if ($authErr = $this->authorizeDev($request)) return $authErr;

        $query = AdminLoginLog::with('user:id,name,email')->orderByDesc('created_at');

        if ($request->filled('status') && in_array($request->status, ['success', 'failed'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . trim($request->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', $search)
                  ->orWhere('ip_address', 'like', $search)
                  ->orWhere('message', 'like', $search);
            });
        }

        $limit = min((int) ($request->limit ?? 100), 500);
        $logs = $query->limit($limit)->get();

        $formatted = $logs->map(function ($log) {
            return [
                'id'         => $log->id,
                'email'      => $log->email,
                'admin_name' => $log->user ? $log->user->name : '-',
                'ip_address' => $log->ip_address ?: 'Unknown',
                'user_agent' => $log->user_agent,
                'status'     => $log->status,
                'message'    => $log->message,
                'time_human' => $log->created_at ? $log->created_at->diffForHumans() : '-',
                'created_at' => $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-',
            ];
        });

        return response()->json([
            'status' => 'success',
            'count'  => $formatted->count(),
            'data'   => $formatted,
        ]);
    }

    /**
     * DELETE /api/developer/logs
     */
    public function clearLogs(Request $request): JsonResponse
    {
        if ($authErr = $this->authorizeDev($request)) return $authErr;

        $count = AdminLoginLog::count();
        AdminLoginLog::truncate();

        return response()->json([
            'status'  => 'success',
            'message' => "Berhasil menghapus {$count} data riwayat login admin.",
        ]);
    }

    /**
     * GET /api/developer/admins
     */
    public function admins(Request $request): JsonResponse
    {
        if ($authErr = $this->authorizeDev($request)) return $authErr;

        $admins = User::orderBy('id')->get();

        $data = $admins->map(function ($u) {
            $lastLogin = AdminLoginLog::where('user_id', $u->id)
                ->where('status', 'success')
                ->latest()
                ->first();

            return [
                'id'            => $u->id,
                'name'          => $u->name,
                'email'         => $u->email,
                'created_at'    => $u->created_at ? $u->created_at->format('Y-m-d H:i:s') : '-',
                'last_login_at' => $lastLogin ? $lastLogin->created_at->format('Y-m-d H:i:s') : 'Belum pernah login',
                'last_login_ip' => $lastLogin ? $lastLogin->ip_address : '-',
            ];
        });

        return response()->json([
            'status' => 'success',
            'count'  => $data->count(),
            'data'   => $data,
        ]);
    }

    /**
     * POST /api/developer/admins
     * Tambah admin baru
     */
    public function createAdmin(Request $request): JsonResponse
    {
        if ($authErr = $this->authorizeDev($request)) return $authErr;

        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.unique'  => 'Email admin ini sudah terdaftar.',
            'password.min'  => 'Password minimal harus 6 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $admin = User::create([
            'name'     => trim($request->name),
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
        ]);

        try {
            AdminLoginLog::create([
                'user_id'    => $admin->id,
                'email'      => $admin->email,
                'ip_address' => $request->ip(),
                'user_agent' => 'Developer Desktop App',
                'status'     => 'success',
                'message'    => "Admin baru [{$admin->name}] dibuat oleh Developer.",
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'status'  => 'success',
            'message' => "Admin '{$admin->name}' ({$admin->email}) berhasil ditambahkan!",
            'data'    => [
                'id'         => $admin->id,
                'name'       => $admin->name,
                'email'      => $admin->email,
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }

    /**
     * POST /api/developer/admins/{id}/reset-password
     * Ganti / Reset password admin yang lupa password
     */
    public function resetAdminPassword(Request $request, $id): JsonResponse
    {
        if ($authErr = $this->authorizeDev($request)) return $authErr;

        $validator = Validator::make($request->all(), [
            'new_password' => ['required', 'string', 'min:6'],
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min'      => 'Password baru minimal harus 6 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $admin = User::find($id);
        if (!$admin) {
            return response()->json([
                'status'  => 'error',
                'message' => "Admin dengan ID #{$id} tidak ditemukan.",
            ], 404);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        // Catat riwayat reset password
        try {
            AdminLoginLog::create([
                'user_id'    => $admin->id,
                'email'      => $admin->email,
                'ip_address' => $request->ip(),
                'user_agent' => 'Developer Desktop App',
                'status'     => 'success',
                'message'    => "Password admin [{$admin->name}] direset oleh Developer.",
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'status'  => 'success',
            'message' => "Password untuk admin '{$admin->name}' ({$admin->email}) berhasil diganti!",
            'data'    => [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }

    /**
     * DELETE /api/developer/admins/{id}
     * Hapus admin
     */
    public function deleteAdmin(Request $request, $id): JsonResponse
    {
        if ($authErr = $this->authorizeDev($request)) return $authErr;

        $admin = User::find($id);
        if (!$admin) {
            return response()->json([
                'status'  => 'error',
                'message' => "Admin dengan ID #{$id} tidak ditemukan.",
            ], 404);
        }

        // Jangan izinkan hapus jika hanya ada 1 admin
        if (User::count() <= 1) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak bisa menghapus admin terakhir. Website harus memiliki minimal 1 admin aktif.',
            ], 400);
        }

        $adminName = $admin->name;
        $adminEmail = $admin->email;

        // Catat ke log sebelum dihapus
        try {
            AdminLoginLog::create([
                'user_id'    => null,
                'email'      => $adminEmail,
                'ip_address' => $request->ip(),
                'user_agent' => 'Developer Desktop App',
                'status'     => 'failed',
                'message'    => "Admin [{$adminName}] ({$adminEmail}) dihapus oleh Developer.",
            ]);
        } catch (\Throwable $e) {}

        $admin->delete();

        return response()->json([
            'status'  => 'success',
            'message' => "Admin '{$adminName}' ({$adminEmail}) berhasil dihapus.",
        ]);
    }
}
