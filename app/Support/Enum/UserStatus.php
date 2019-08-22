<?php

namespace Tagydes\Support\Enum;

class UserStatus
{
    const UNCONFIRMED = 'Unconfirmed';
    const ACTIVE = 'Active';
    const INACTIVATE = 'Inactive';
    const BANNED = 'Banned';

    public static function lists()
    {
        return [
            self::ACTIVE => trans('app.'.self::ACTIVE),
            self::BANNED => trans('app.'. self::BANNED),
            self::UNCONFIRMED => trans('app.' . self::UNCONFIRMED)
        ];
    }
}
