<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Landing;

class DiskServiceProdiver extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      if(Schema::hasTable('landings') === false){
        return;
      }

      $landings = Landing::all();
      $disks = [];

      $landings->each(function(Landing $landing) use(&$disks) {
        if($landing->key && isset($landing->extras['disk']) && !empty($landing->extras['disk'])) {
          // FILE STORAGE DISK 
          // $this->app['config']["filesystems.disks.{$landing->key}"] = [
          //   'driver' => 'local',
          //   'root' => $landing->extras['disk'],
          //   'url' => env('APP_URL').'/cdn',
          //   'visibility' => 'public',
          // ];

          // PUBLIC DISK
          $path_array = explode('/', $landing->extras['disk']);
          // array_pop($path_array);
          $root_path = implode('/', $path_array);

          // dd($root_path);
          $this->app['config']["filesystems.disks.{$landing->key}-public"] = [
            'driver' => 'local',
            'root' => $root_path,
            'visibility' => 'public',
          ];

          $disks[] = $landing->key . '-public';
        }
      });

      $this->app['config']["elfinder.disks"] = $disks;
    }
}
