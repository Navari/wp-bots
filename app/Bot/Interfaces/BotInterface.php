<?php


namespace App\Bot\Interfaces;


use App\Bot\Models\News;

interface BotInterface
{
    public const Author = "Celalettin YILMAZ";

    public function run() : void;

    /**
     * @return News[]
     */
    public function get() : iterable;
}
