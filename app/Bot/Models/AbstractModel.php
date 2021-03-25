<?php


namespace App\Bot\Models;


use App\Bot\Traits\ArrayLikeTrait;
use App\Bot\Traits\InitializerTrait;
use JetBrains\PhpStorm\Pure;

class AbstractModel implements \ArrayAccess
{
    use ArrayLikeTrait, InitializerTrait;
    /**
     * @var array
     */
    protected static array $initPropertiesMap = [];

    /**
     * @return array
     */
    #[Pure] public static function getColumns(): array
    {
        return \array_keys(static::$initPropertiesMap);
    }

}
