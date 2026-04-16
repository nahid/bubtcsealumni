<?php

namespace App\Enums;

enum BoardPosition: string
{
    case President = 'president';
    case VicePresident = 'vice_president';
    case GeneralSecretary = 'general_secretary';
    case Treasurer = 'treasurer';
    case JointSecretary = 'joint_secretary';
    case ExecutiveMember = 'executive_member';

    public function label(): string
    {
        return match ($this) {
            self::President => 'President',
            self::VicePresident => 'Vice President',
            self::GeneralSecretary => 'General Secretary',
            self::Treasurer => 'Treasurer',
            self::JointSecretary => 'Joint Secretary',
            self::ExecutiveMember => 'Executive Member',
        };
    }
}
