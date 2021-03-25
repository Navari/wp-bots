<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\CrawlSite;
use App\Models\News;
use Illuminate\Console\Command;

class BotRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:run';

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
     * @return void
     */
    public function handle(): void
    {
        $sites = CrawlSite::where('is_active', 1)->get();
        foreach($sites as $site){
            $module = new $site->module();
            $module->run();
            foreach($module->get() as $item){
                $insertCategory = Category::updateOrCreate(['title' => $item->getCategory()]);
                $insertNews = News::updateOrCreate(
                    ['origin_url' => $item->getOriginLink()],
                    [
                        'featured_image' => $item->getImage(),
                        'title' => $item->getTitle(),
                        'summary' => $item->getSummary(),
                        'description' => $item->getDescription(),
                        'tags' => $item->getTags(),
                        'category_id' => $insertCategory->id,
                        'crawl_site_id' => $site->id
                    ]
                );
            }
        }
    }
}
