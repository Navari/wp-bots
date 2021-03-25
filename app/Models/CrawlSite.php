<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawlSite extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'module',
        'parameters',
        'is_active'
    ];
}
