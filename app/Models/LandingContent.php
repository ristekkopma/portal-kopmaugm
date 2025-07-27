<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    
protected $fillable = [
    'hero_title',
    'hero_paragraph',
    'hero_image',
    'intro_text',
    'step_image_1',
    'step_image_2',
    'step_image_3',
    'alamat',
    'map_iframe',
    'instagram',
    'youtube',
    'twitter',
    'linkedin',
];

}

