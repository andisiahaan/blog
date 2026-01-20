<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// AI Auto Blog Scheduler - reads config from .env via config/ai.php
Schedule::command('ai:schedule')->when(function () {
    return config('ai.enabled', false) && config('ai.scheduling.enabled', false);
})->daily()->when(function () {
    $frequency = config('ai.scheduling.frequency', 'daily');
    return match ($frequency) {
        'hourly' => now()->minute === 0,
        'every_6_hours' => now()->hour % 6 === 0 && now()->minute === 0,
        'every_12_hours' => now()->hour % 12 === 0 && now()->minute === 0,
        'daily' => now()->hour === 8 && now()->minute === 0,
        'weekly' => now()->dayOfWeek === 1 && now()->hour === 8 && now()->minute === 0,
        default => now()->hour === 8 && now()->minute === 0,
    };
})->withoutOverlapping();
