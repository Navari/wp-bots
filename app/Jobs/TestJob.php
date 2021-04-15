<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $a, $b;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        var_dump($this->a);
    }
}
