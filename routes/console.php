<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
| Catatan: ->hourly() tidak tersedia di Laravel 10 untuk closure command.
| Gunakan Schedule di app/Console/Kernel.php untuk scheduling.
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
