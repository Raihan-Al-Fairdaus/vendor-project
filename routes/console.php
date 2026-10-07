<?php
use Illuminate\Support\Facades\Artisan;
Artisan::command('test:mail', function () {
    try {
        Illuminate\Support\Facades\Mail::raw('Test', function($m) {
            $m->to('gamingraihan863@gmail.com')->subject('Test');
        });
        $this->info('SUCCESS');
    } catch (\Exception $e) {
        $this->error('ERROR: ' . $e->getMessage());
    }
});
