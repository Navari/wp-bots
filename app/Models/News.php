<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'featured_image',
        'title',
        'summary',
        'description',
        'tags',
        'gallery',
        'origin_url',
        'category_id',
        'crawl_site_id'
    ];
}
