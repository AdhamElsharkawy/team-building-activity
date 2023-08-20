<?php

namespace App\Http\Traits;

//use App\Models\Seo;

trait SeoTrait
{
    public function seo($title, $url, $description = 'Description', $keywords = 'keywords',$image = 'Logo.svg')
    {
        /*$seo = Seo::first();
        $data = [
            'title' => $title . ' | ' . $seo->title,
            'description' => $seo->description,
            'keywords' => $seo->keywords,
            'image' => $seo->image,
            'url' => $url,
        ];*/
        $data = [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'image' => asset($image),
            'url' => env('APP_URL') . '/' . $url,
        ];
        return $data;
    }
}
