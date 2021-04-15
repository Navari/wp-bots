<?php

namespace App\Models;

use App\Traits\Search;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory, Search;

    protected $fillable = [
        'featured_image',
        'title',
        'summary',
        'description',
        'raw_body',
        'tags',
        'gallery',
        'origin_url',
        'category_id',
        'crawl_site_id'
    ];
    //ALTER TABLE news ADD FULLTEXT('summary', 'description', 'tags');
    protected array $searchable = [
        'summary',
        'description',
        'tags'
    ];
}
