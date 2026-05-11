<?php

namespace Core;

use PDO;

abstract class Model
{
    // Child class এ override করবেন
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    // ─────────────────────────────────────────
    //  READ
    // ─────────────────────────────────────────

    /** সব row আনো */
    public static function all(): array
    {
        $db  = Database::getConnection();
        $sql = "SELECT * FROM " . static::$table;
        return $db->query($sql)->fetchAll();
    }

    /** Primary key দিয়ে একটা row আনো */
    public static function find(int|string $id): array|false
    {
        $db  = Database::getConnection();
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Condition দিয়ে খোঁজো — where(['email' => 'a@b.com']) */
    public static function where(array $conditions): array
    {
        $db     = Database::getConnection();
        $parts  = [];
        $params = [];

        foreach ($conditions as $col => $val) {
            $parts[]        = "$col = :$col";
            $params[":$col"] = $val;
        }

        $sql  = "SELECT * FROM " . static::$table . " WHERE " . implode(' AND ', $parts);
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** প্রথম match করা row আনো */
    public static function first(array $conditions): array|false
    {
        $results = static::where($conditions);
        return $results[0] ?? false;
    }

    // ─────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────

    /** নতুন row insert করো, last insert id ফেরত দেয় */
    public static function create(array $data): int|string
    {
        $db      = Database::getConnection();
        $cols    = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));

        $sql  = "INSERT INTO " . static::$table . " ($cols) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);

        $params = [];
        foreach ($data as $key => $val) {
            $params[":$key"] = $val;
        }

        $stmt->execute($params);
        return $db->lastInsertId();
    }

    // ─────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────

    /** Primary key দিয়ে update করো */
    public static function update(int|string $id, array $data): bool
    {
        $db   = Database::getConnection();
        $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));

        $sql  = "UPDATE " . static::$table . " SET $sets WHERE " . static::$primaryKey . " = :__id";
        $stmt = $db->prepare($sql);

        $params = [':__id' => $id];
        foreach ($data as $key => $val) {
            $params[":$key"] = $val;
        }

        return $stmt->execute($params);
    }

    // ─────────────────────────────────────────
    //  DELETE
    // ─────────────────────────────────────────

    /** Primary key দিয়ে delete করো */
    public static function delete(int|string $id): bool
    {
        $db   = Database::getConnection();
        $sql  = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ─────────────────────────────────────────
    //  UTILITY
    // ─────────────────────────────────────────

    /** মোট কতটা row আছে */
    public static function count(): int
    {
        $db  = Database::getConnection();
        $sql = "SELECT COUNT(*) FROM " . static::$table;
        return (int) $db->query($sql)->fetchColumn();
    }

    /** Raw SQL চালাও (জটিল query এর জন্য) */
    public static function raw(string $sql, array $params = []): array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
