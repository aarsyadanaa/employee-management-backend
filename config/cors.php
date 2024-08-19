<?php

return [

    'paths' => ['api/*', 'generate-pdf'], // Daftar path yang akan diizinkan untuk CORS

    'allowed_methods' => ['*'], // Metode HTTP yang diizinkan

    'allowed_origins' => ['*'], // Asal yang diizinkan. Gantilah '*' dengan daftar domain jika perlu

    'allowed_origins_patterns' => [], // Pola asal yang diizinkan

    'allowed_headers' => ['*'], // Header yang diizinkan

    'exposed_headers' => [], // Header yang bisa diekspos ke browser

    'max_age' => 0, // Usia cache preflight request dalam detik

    'supports_credentials' => true, // Menyokong credentials seperti cookies dan header Authorization

];
