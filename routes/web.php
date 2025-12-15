<?php

// resources/routes/web.php

use Illuminate\Support\Facades\Route;

// Biarkan route API di routes/api.php

// 💡 CATCH-ALL ROUTE (HARUS MENJADI ROUTE TERAKHIR)
// Route ini akan menangkap semua URL yang tidak cocok dengan API
Route::get('/{any}', function () {
    // Asumsikan Anda merender file blade utama Anda (yang berisi <div id="app">)
    // Jika Anda menggunakan Laravel sebagai API murni, file ini harus diganti
    // dengan mekanisme yang melayani file index.html atau root SPA Anda.

    // Jika Anda tetap ingin merender Blade (asumsi: resources/views/index.blade.php)
    return view('app'); 

})->where('any', '.*');