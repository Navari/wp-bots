<?php


namespace Navari\Bot\Modules\Ensonhaber;


use GuzzleHttp\Client;
use Navari\Bot\Http\Request;
use Navari\Bot\Interfaces\BotInterface;
use Navari\Bot\Models\News;
use Navari\Bot\Parser\SimpleDom;

class Ensonhaber implements BotInterface
{
    public string $baseUrl = "https://www.ensonhaber.com/";
    public string $name = "Ensonhaber.com botu";
    public array $parameters = [];
    public string $rss = "https://www.ensonhaber.com/rss/ensonhaber.xml";
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
                'summary' => (string)strip_tags($item->description),
                'image' => (string)$item->image,
                'link' => (string)$item->link,
                'description' => '',
                'tags' => '',
                'gallery' => '',
                'category' => ''
            ];
            $response = Request::get($news['link']);
            preg_match('@<script type="application/ld\\+json">(.*?)</script>@si', $response->raw_body, $json);
            $dom = new SimpleDom($response->raw_body);
            $raw_body = $dom->getElementByClass('article', 'body');
            if(isset($json[1])){
                $decoded = json_decode($json[1]);
                $news['description'] = $decoded->articleBody;
                $news['tags'] = implode(',', $decoded->keywords);
                $news['category'] = $decoded->articleSection;
                $news['raw_body'] = $raw_body;
                $this->news[] = News::create($news);
            }else{
                $this->errors[] = $news['link'];
            }
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
