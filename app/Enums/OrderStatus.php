<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';

    public function next(): ?self
    {
        return match ($this) {
            self::Pending => self::Processing,
            self::Processing => self::Shipped,
            self::Shipped => self::Delivered,
            self::Delivered => null,
        };
    }
}