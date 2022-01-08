<?php

namespace App\Constants;

interface AppConstants
{
    public const CREATED = 'CREATED';
    public const PENDING = 'PENDING';
    public const APPROVED = 'APPROVED';
    public const PAYED = 'PAYED';
    public const REJECTED = 'REJECTED';
    public const EXPIRED = 'EXPIRED';

    public const STATUS = [
        self::CREATED,
        self::PENDING,
        self::APPROVED,
        self::PAYED,
        self::REJECTED,
        self::EXPIRED,
    ];

    public const TYPE_DOCUMENT = ['TI', 'CC', 'CE', 'PPN', 'NIT', 'RUT'];
}
