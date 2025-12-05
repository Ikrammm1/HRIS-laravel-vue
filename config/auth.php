<?php

return [

    // ... bagian providers ...

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // *** BAGIAN YANG HARUS DIUBAH ***
        'api' => [
            'driver' => 'jwt', // <--- UBAH KE 'jwt'
            'provider' => 'users',
            // tidak perlu token driver untuk JWT
        ],
    ],

    // ... bagian passwords ...
];