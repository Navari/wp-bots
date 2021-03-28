<?php


namespace Navari\Bot\Models;


use Navari\Bot\Traits\ArrayLikeTrait;
use Navari\Bot\Traits\InitializerTrait;
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
