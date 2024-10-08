<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

// SLUGS
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

// MODEL
use App\Models\Page;

class Landing extends Model
{
    use CrudTrait;
    use Sluggable;
    use SluggableScopeHelpers;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'landings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'key', 'is_active', 'seo', 'cssLink', 'jsLink', 'head_stack', 'header_html', 'footer_html', 'closed_html', 'robots_txt', 'disk', 'extras', 'fields', 'strings'];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
      'seo' => 'array',
      'extras' => 'array',
      'head_stack' => 'array',
      'fields' => 'array',
      'strings' => 'array'
    ];

    protected $fakeColumns = ['seo', 'extras', 'fields', 'strings'];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    
    /**
     * __constract
     *
     * @param  mixed $attributes
     * @return void
     */
    public function __constract(array $attributes = array()) {
      parent::__construct($attributes);
    }
    
    /**
     * boot
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
    }
    
    /**
     * sluggable
     *
     * @return array
     */
    public function sluggable():array
    {
        return [
            'key' => [
                'source' => 'key_or_name',
            ],
        ];
    }

    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function pages()
    {
      return $this->hasMany(Page::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    
    /**
     * getSlugOrNameAttribute
     *
     * @return void
     */
    public function getKeyOrNameAttribute()
    {
        if ($this->key != '') {
            return $this->key;
        }
        return $this->name;
    }


    public function getCssLinkAttribute()
    {
      return $this->attributes['css_link'];
    }


    public function getJsLinkAttribute()
    {
      return $this->attributes['js_link'];
    }

    public function getPublicCssLinkAttribute() {
      $path = $this->key . '/styles.css';

      if(Storage::disk($this->key)->exists($path)) {
        return url('cdn/' . $path);
      }else {
        return null;
      }
    }

    public function getPublicJsLinkAttribute() {
      $path = $this->key . '/scripts.js';

      if(Storage::disk($this->key)->exists($path)) {
        return url('cdn/' . $path);
      }else {
        return null;
      }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setCssLinkAttribute($value = null)
    {
      $file_name = 'styles.css';
      $destination_path = $this->key;

      if(empty($value)) {
        Storage::disk($this->key)->delete($destination_path . '/' . $file_name);
        return;
      }

      Storage::disk($this->key)->putFileAs($destination_path, $value, $file_name);
      $this->attributes['css_link'] = $file_name;
    }


    public function setJsLinkAttribute($value = null)
    {
      $file_name = 'scripts.js';
      $destination_path = $this->key;

      if(empty($value)) {
        Storage::disk($this->key)->delete($destination_path . '/' . $file_name);
        return;
      }

      // $file_name = $value->getClientOriginalName();
      $destination_path = $this->key;

      Storage::disk($this->key)->putFileAs($destination_path, $value, $file_name);
      $this->attributes['js_link'] = $file_name;
    }
}
