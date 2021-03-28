<?php


namespace Navari\Bot\Modules\Cumhuriyet;


use GuzzleHttp\Client;
use Navari\Bot\Http\Request;
use Navari\Bot\Interfaces\BotInterface;
use Navari\Bot\Models\News;
use Navari\Bot\Parser\SimpleDom;

class Cumhuriyet implements BotInterface
{
    public string $baseUrl = "https://www.cumhuriyet.com.tr/";
    public string $name = "Cumhuriyet.com.tr botu";
    public array $parameters = [];
    public string $rss = "https://www.cumhuriyet.com.tr/rss";
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
                'image' => (string)$item->image,
                'link' => (string)$item->link,
                'description' => '',
                'tags' => '',
                'gallery' => '',
                'category' => ''
            ];
            $response = Request::get($news['link']);
            preg_match('@<meta itemprop="articleSection" content="(.*?)" />@si', $response->raw_body, $articleSection);
            preg_match('@<meta name="keywords" content="(.*?)" />@si', $response->raw_body, $keywords);
            $dom = new SimpleDom($response->raw_body);
            $raw_body = $dom->getElementByClass('div', 'haberMetni');
            $news['description'] = $raw_body;
            $news['tags'] = $keywords[1];
            $news['category'] = $articleSection[1];
            $news['raw_body'] = $raw_body;
            $this->news[] = News::create($news);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(): iterable
    {
        return $this->news;
    }
}
