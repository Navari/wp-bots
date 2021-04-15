<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Navari\Bot\Modules\Cumhuriyet\Cumhuriyet;
use Navari\Bot\Modules\Ensonhaber\Ensonhaber;
use Navari\Bot\Modules\Gazeteduvar\Gazeteduvar;
use Navari\Bot\Modules\Haber7\Haber7;
use Navari\Bot\Modules\Hurriyet\Hurriyet;
use Navari\Bot\Modules\NoktaNokta\NoktaNokta;

class TestBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $cumhuriyet = new Hurriyet();
//        $cumhuriyet->run();
//        print_r($cumhuriyet->get());
////        print_r($cumhuriyet->getErrors());
        print_r(Redis::connection()->client()->info());
    }
}
