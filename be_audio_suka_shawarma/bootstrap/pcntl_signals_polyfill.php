<?php

/**
 * Windows tidak punya ekstensi pcntl (POSIX-only), jadi konstanta
 * SIGINT/SIGTERM/SIGHUP tidak pernah ter-define di sana. Laravel
 * Octane (vendor/laravel/octane/src/Commands/Concerns/
 * InteractsWithServers.php) mereferensikan konstanta itu langsung
 * tanpa pengecekan, jadi crash fatal di Windows tanpa polyfill ini.
 *
 * Nilai di bawah adalah nomor sinyal POSIX standar - persis sama
 * dengan yang didefinisikan pcntl di Linux/macOS. Di Windows,
 * penanganan sinyal-nya sendiri memang tidak akan benar-benar
 * berfungsi (OS-nya tidak punya konsep sinyal POSIX), tapi
 * setidaknya konstanta-nya ada supaya Octane tidak fatal error saat
 * boot.
 */

if (!defined('SIGHUP')) {
    define('SIGHUP', 1);
}

if (!defined('SIGINT')) {
    define('SIGINT', 2);
}

if (!defined('SIGTERM')) {
    define('SIGTERM', 15);
}
