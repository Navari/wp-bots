<?php


namespace Navari\Bot\Modules\Haber7;


use Navari\Bot\Http\Request;
use Navari\Bot\Interfaces\BotInterface;
use Navari\Bot\Models\News;
use Navari\Bot\Parser\SimpleDom;
use GuzzleHttp\Client;

class Haber7 implements BotInterface
{

    public string $baseUrl = "https://www.haber7.com/";
    public string $name = "haber7.com botu";
    public array $parameters = [];
    public string $rss = "http://sondakika.haber7.com/sondakika.rss";
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
                'image' => '',
                'link' => (string)$item->link,
                'description' => '',
                'tags' => '',
                'gallery' => '',
                'category' => ''
            ];
            $response = Request::get($news['link']);
            preg_match('@<script type="application/ld\\+json">(.*?)</script>@si', $response->raw_body, $json);
            $dom = new SimpleDom($response->raw_body);
            $raw_body = $dom->getElementByClass('div', 'news-content');
            if(isset($json[1])){
                $decoded = json_decode($json[1]);
                $news['image'] = $decoded->image->url;
                $news['description'] = $decoded->articleBody;
                $news['tags'] = is_object($decoded->keywords) ? implode(',', $decoded->keywords) : $decoded->keywords;
                $news['category'] = $decoded->articleSection;
                $news['raw_body'] = $raw_body;
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
