<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Member = 'member';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Manager => 'Manager',
            self::Member => 'Member',
        };
    }
}
