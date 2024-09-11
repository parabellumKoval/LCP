<?php

namespace App\Observers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Page;

class PageObserver
{

    /**
     * Handle the Page "updated" event.
     *
     * @param  \App\Models\Page  $page
     * @return void
     */
    public function updated(Page $page)
    {

      $landing = $page->landing;    
      $robots_content = $landing->robots_txt;

      foreach($landing->pages as $page) {
        if($page->in_index === 0) {
          $url = $page->is_home? '/': '/'.$page->slug;
          $robots_content .= "
Disallow: {$url}\n";
        }
      }

      Storage::disk("{$landing->key}-public")->put('robots.txt', $robots_content);

    }

}
