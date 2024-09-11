<?php

namespace App\Observers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Landing;

class LandingObserver
{
    /**
     * Handle the Landing "created" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function created(Landing $landing)
    {
    }

    /**
     * Create a directory.
     *
     * @param  string  $path
     * @param  int     $mode
     * @param  bool    $recursive
     * @param  bool    $force
     * @return bool
     */
    public function makeDirectory($path, $mode = 0777, $recursive = false, $force = false)
    {
        if($force){
          if(!file_exists($path) && !is_dir($path)) {
            return @mkdir($path, $mode, $recursive);
          }
        }else {
          if(!file_exists($path) && !is_dir($path)) {
            return mkdir($path, $mode, $recursive);
          }
        }
    }

    /**
     * Handle the Landing "updated" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function updated(Landing $landing)
    {
      if($landing->extras['disk']) {
        $this->makeDirectory($landing->extras['disk'] . '/css', 0777, false);
        $this->makeDirectory($landing->extras['disk'] . '/js', 0777, false);
        $this->makeDirectory($landing->extras['disk'] . '/images', 0777, false);
        $this->makeDirectory($landing->extras['disk'] . '/files', 0777, false);

        if($landing->robots_txt) {
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
    }

    /**
     * Handle the Landing "deleted" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function deleted(Landing $landing)
    {
        //
    }

    /**
     * Handle the Landing "restored" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function restored(Landing $landing)
    {
        //
    }

    /**
     * Handle the Landing "force deleted" event.
     *
     * @param  \App\Models\Landing  $landing
     * @return void
     */
    public function forceDeleted(Landing $landing)
    {
        //
    }
}
