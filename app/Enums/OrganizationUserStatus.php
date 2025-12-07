<?php

namespace App\Enums;
use \Spatie\Enum\Enum;

/**
 * @method static self applyingForMembership()
 * @method static self afilliation()
 * @method static self applyingForWithdrawal()
 * @method static self withdrawal()
 */
final class OrganizationUserStatus extends Enum
{
    public static function applyingForMembership(): self
    {
        return new self(0); 
    }

    public static function afilliation(): self
    {
        return new self(1); 
    }

    public static function applyingForWithdrawal(): self
    {
        return new self(2); 
    }

    public static function withdrawal(): self
    {
        return new self(3); 
    }
    

    protected static function values(): array
    {
        return [
            'applyingForMembership' => 0,
            'afilliation' => 1,
            'applyingForWithdrawal' => 2,
            'withdrawal' => 3,
        ];
    }

    protected static function labels(): array
    {
        return [
            'applyingForMembership' => __('organization_user_status.applying_membership'),
            'afilliation' => __('organization_user_status.afilliation'),
            "applyingForWithdrawal" => __('organization_user_status.applyingForWithdrawal'),
            "withdrawal" => __('organization_user_status.withdrawal')
        ];
    }
}
