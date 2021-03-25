<?php


namespace App\Bot\Modules\Haberler;


use App\Bot\Http\Request;
use App\Bot\Interfaces\BotInterface;
use App\Bot\Models\News;
use GuzzleHttp\Client;

class Haberler implements BotInterface
{

    public string $baseUrl = "https://www.haberler.com/";
    public string $name = "Haberler.com botu";
    public array $parameters = [];
    public string $rss = "https://rss.haberler.com/rss.asp?limit=100";
    public array $categories = [];
    public array $news = [];
    public array $errors = [];

    public function __construct()
    {
        Request::setHttpClient(new Client());
    }

    public function run(): void
    {
        $rssResponse = Request::get($this->rss);
        $rss = simplexml_load_string($rssResponse->raw_body,null, LIBXML_NOCDATA);
        foreach($rss->channel->item as $item){
            $news = [
                'title' => (string)$item->title,
                'summary' => (string)$item->description,
                'image' => (string)$item->children('media', true)->attributes()->url,
                'link' => (string)$item->link,
                'description' => '',
                'tags' => '',
                'gallery' => '',
                'category' => ''
            ];
            $response = Request::get($news['link']);
            preg_match('@<script type="application/ld\\+json">(.*?)</script>@si', $response->raw_body, $json);
            if(isset($json[1])){
                $decoded = json_decode($json[1]);
                $news['description'] = $decoded->articleBody;
                $news['tags'] = implode(',', $decoded->keywords);
                $news['category'] = $decoded->articleSection;
                $this->news[] = News::create($news);
            }else{
                $this->errors[] = $news['link'];
            }
        }
    }

    /**
     * @return News[]
     */
    public function get(): iterable
    {
        return $this->news;
    }
}
