<?php

namespace App\Constants;

interface AppConstants
{
    public const CREATED = 'CREATED';
    public const PENDING = 'PENDING';
    public const APPROVED = 'APPROVED';
    public const REJECTED = 'REJECTED';
    public const EXPIRED = 'EXPIRED';

    public const STATUS = [
        self::CREATED,
        self::PENDING,
        self::APPROVED,
        self::REJECTED,
        self::EXPIRED,
    ];

    public const DOCUMENT_TYPE = ['TI', 'CC', 'CE', 'PPN', 'NIT', 'RUT'];
}
