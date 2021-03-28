<?php


namespace Navari\Bot\Modules\Gazeteduvar;


use GuzzleHttp\Client;
use Navari\Bot\Http\Request;
use Navari\Bot\Interfaces\BotInterface;
use Navari\Bot\Models\News;
use Navari\Bot\Parser\SimpleDom;

class Gazeteduvar implements BotInterface
{
    public string $baseUrl = "https://www.Gazeteduvar.com.tr/";
    public string $name = "Gazeteduvar.com.tr botu";
    public array $parameters = [];
    public string $rss = "https://www.gazeteduvar.com.tr/export/rss";
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
            preg_match_all('@<script type="application/ld\\+json">(.*?)</script>@si', $response->raw_body, $json);
            $dom = new SimpleDom($response->raw_body);
            $raw_body = $dom->getElementByClass('div', 'content-text');
            if(isset($json[1][2])){
                try{
                    $json = preg_replace('/[[:cntrl:]]/', '', $json[1][2]);
                    $decoded = json_decode(str_replace(["\t", "\n", "  "], "",trim($json)), false, 512, JSON_THROW_ON_ERROR);
                    $news['image'] = $decoded->image->url;
                    $news['description'] = $decoded->articleBody ?? $decoded->description;
                    $news['tags'] = is_object($decoded->keywords) ? implode(',', $decoded->keywords) : $decoded->keywords;
                    $news['category'] = $decoded->articleSection;
                    $news['raw_body'] = $raw_body;
                    $this->news[] = News::create($news);
                }catch (\Exception $e){
                    $this->errors[] = $news['link'];
                }
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

    public function getErrors(): array
    {
        return $this->errors;
    }
}
