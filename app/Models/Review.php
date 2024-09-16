<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

// MODEL
use App\Models\Landing;
use App\Models\Page;

class Review extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'reviews';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
      'extras' => 'array'
    ];

    protected $fakeColumns = ['extras'];
    
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
     * page
     *
     * @return void
     */
    public function page()
    {
      return $this->belongsTo(Page::class);
    }
    
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

    public function scopeModeratedOrOwn(Builder $query){
      $review_author_ids = session('reviews_author');
      $query->where('is_moderated', 1)
            ->when(!empty($review_author_ids), function($query) use ($review_author_ids){
              $query->orWhereIn('id', $review_author_ids);
            });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getUniqNameAdminAttribute() {
      return $this->landing->name . ' | ' . $this->id . ' | ' . $this->author  . ' | ' . mb_substr($this->text, 0, 50) . '...';
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
