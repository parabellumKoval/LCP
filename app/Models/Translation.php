<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

// MODEL
use App\Models\Landing;
use App\Models\Page;

class Translation extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'translations';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
      'strings' => 'array'
    ];

    protected $fakeColumns = ['strings'];
    
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
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */    
    /**
     * landing
     *
     * @return void
     */
    public function landing()
    {
      return $this->belongsTo(Landing::class);
    }
      
    /**
     * page
     *
     * @return void
     */
    public function page()
    {
      return $this->belongsTo(Page::class);
    }

    /**
     * parent
     *
     * @return void
     */
    public function parent()
    {
      return $this->belongsTo(self::class, 'parent_id');
    }
    
    /**
     * children
     *
     * @return void
     */
    public function children()
    {
      return $this->hasMany(self::class, 'parent_id');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
