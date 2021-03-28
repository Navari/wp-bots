<?php


namespace Navari\Bot\Interfaces;


use Navari\Bot\Models\News;

interface BotInterface
{
    public const Author = "Celalettin YILMAZ";

    public function run() : void;

    /**
     * @return News[]
     */
    public function get() : iterable;
}
