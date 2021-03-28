<?php


namespace Navari\Bot\Modules\NoktaNokta;


use Navari\Bot\Http\Request;
use Navari\Bot\Interfaces\BotInterface;
use Navari\Bot\Models\News;
use Navari\Bot\Parser\SimpleDom;
use GuzzleHttp\Client;

class NoktaNokta implements BotInterface
{

    public string $baseUrl = "https://noktanokta.net/";
    public string $name = "noktanokta.net botu";
    public array $parameters = [];
    public string $rss = "https://noktanokta.net/feed/";
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
                'summary' => (string)strip_tags($item->description,['img']),
                'image' => (string)$item->children('media', true)->attributes()->url,
                'link' => (string)$item->link,
                'description' => '',
                'tags' => '',
                'gallery' => '',
                'category' => (string)$item->category,
                'raw_body' => (string)strip_tags($item->description,['img'])
            ];
            print_r($news);
            die();
            $response = Request::get($news['link']);
            preg_match('@<script type="application/ld\\+json">(.*?)</script>@si', $response->raw_body, $json);
            $dom = new SimpleDom($response->raw_body);
            $raw_body = $dom->getElementByClass('div', 'hbptContent haber_metni');
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
     * @return News[]
     */
    public function get(): iterable
    {
        return $this->news;
    }
}
