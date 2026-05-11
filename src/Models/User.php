<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected static string $table = 'users';

    // ─── Custom methods ───────────────────────

    /** Email দিয়ে user খোঁজো */
    public static function findByEmail(string $email): array|false
    {
        return static::first(['email' => $email]);
    }

    /** শুধু active user গুলো আনো */
    public static function active(): array
    {
        return static::where(['status' => 'active']);
    }
}
