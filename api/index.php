<?php
// ============================================================
// VERCEL ENTRYPOINT — Optimized for fast cold start
// ============================================================

// 1. Buat folder /tmp yang diizinkan Vercel
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];
foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 2. Redirect Laravel cache & storage ke /tmp
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('LOG_CHANNEL=stderr');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');

// 3. Optimasi: jalankan artisan cache sekali per cold start
//    (hanya jika cache belum ada di /tmp instance ini)
$cacheReady = file_exists('/tmp/bootstrap/cache/config.php')
    && file_exists('/tmp/bootstrap/cache/routes.php')
    && file_exists('/tmp/bootstrap/cache/services.php');

if (!$cacheReady) {
    $artisan = __DIR__ . '/../artisan';
    if (file_exists($artisan)) {
        $php = PHP_BINARY ?: 'php';
        // Cache config, routes, events, views — pangkas bootstrap time
        @exec("$php $artisan config:cache --quiet 2>/dev/null");
        @exec("$php $artisan route:cache  --quiet 2>/dev/null");
        @exec("$php $artisan event:cache  --quiet 2>/dev/null");
        @exec("$php $artisan view:cache   --quiet 2>/dev/null");
    }
}

// 4. Jalankan Laravel
require __DIR__ . '/../public/index.php';