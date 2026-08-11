<?php

namespace App\Enums;

/**
 * Fixed lead lifecycle as per SRS section 5.
 * Not dynamic in v1 — do not make this DB-driven.
 */
enum LeadStatus: string
{
    case New = 'New';
    case Contacted = 'Contacted';
    case Interested = 'Interested';
    case Converted = 'Converted';
    case Lost = 'Lost';

    public function label(): string
    {
        return $this->value;
    }

    /**
     * Badge color helper for UI (Tailwind classes), optional convenience.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::New        => 'bg-slate-100 text-slate-700',
            self::Contacted  => 'bg-blue-100 text-blue-700',
            self::Interested => 'bg-amber-100 text-amber-700',
            self::Converted  => 'bg-green-100 text-green-700',
            self::Lost       => 'bg-red-100 text-red-700',
        };
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}