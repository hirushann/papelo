<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('flux:publish-assets', function () {
    $filesystem = new \Illuminate\Filesystem\Filesystem;
    $filesystem->ensureDirectoryExists(public_path('flux'));
    
    if ($filesystem->exists(base_path('vendor/livewire/flux/dist'))) {
        $filesystem->copyDirectory(base_path('vendor/livewire/flux/dist'), public_path('flux'));
    }
    if ($filesystem->exists(base_path('vendor/livewire/flux-pro/dist'))) {
        $filesystem->copyDirectory(base_path('vendor/livewire/flux-pro/dist'), public_path('flux'));
    }
    
    $this->info('Flux assets published successfully.');
})->purpose('Publish Flux UI assets to public directory');
