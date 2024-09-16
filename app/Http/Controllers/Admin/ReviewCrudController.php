<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReviewRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Review;
use App\Models\Landing;
use App\Models\Page;

/**
 * Class SupplierCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReviewCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    private $filter_landings = [];
    private $landings_list = [];
    private $entry = null;

    public function setup()
    {
        $this->crud->setModel(Review::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/review');
        $this->crud->setEntityNameStrings('отзыв', 'отзывы');

        // CURRENT MODEL
        $this->setEntry();

        $this->filter_landings = Landing::pluck('name', 'id')->toArray();
        $this->pages_list = Page::where('landing_id', $this->getLandingId())->pluck('name', 'id')->toArray();
    }

    protected function setupListOperation()
    {

      // Filter by landing
      $this->crud->addFilter([
        'name' => 'landing',
        'label' => 'Лендинг',
        'type' => 'select2',
      ], function(){
        $list = ['empty' => '🔴 Без лендинга'] + $this->filter_landings;
        return $list;
      }, function($id){
        if($id === 'empty') {
          $this->crud->query->whereNull('landing_id');
        }else {
          $this->crud->query->where('landing_id', $id);
        }
      });

      $this->crud->addFilter([
        'name' => 'is_moderated',
        'label' => 'Опубликован?',
        'type' => 'select2',
      ], function(){
        return [
          0 => '🔴 Не опубликован',
          1 => '🟢 Опубликован',
        ];
      }, function($is_active){
        $this->crud->query->where('is_moderated', $is_active);
      });

      $this->crud->addColumn([
        'name' => 'is_moderated',
        'label' => '🟢',
        'type' => 'check'
      ]);

      $this->crud->addColumn([
        'name' => 'published_at',
        'label' => '🕐',
        'type' => 'datetime'
      ]);

      $this->crud->addColumn([
        'name' => 'landing',
        'label' => 'Лендинг',
        'type' => 'relationship'
      ]);

      $this->crud->addColumn([
        'name' => 'author',
        'label' => 'Автор',
      ]);

      $this->crud->addColumn([
        'name' => 'text',
        'label' => 'Текст',
      ]);

    }

    protected function setupCreateOperation()
    {
      $this->crud->setValidation(ReviewRequest::class);

      $js_attributes = [
        'data-value' => '',
        'onfocus' => "this.setAttribute('data-value', this.value);",
        'onchange' => "
            const value = event.target.value
            let isConfirmed = confirm('Несохраненные данные будут сброшены. Все равно продолжить?');
            
            if(isConfirmed) {
              reload_page(event);
            } else{
              this.value = this.getAttribute('data-value');
            }

            function reload_page(event) {
              const value = event.target.value
              url = insertParam('landing_id', value)
            };

            function insertParam(key, value) {
              key = encodeURIComponent(key);
              value = encodeURIComponent(value);
          
              // kvp looks like ['key1=value1', 'key2=value2', ...]
              var kvp = document.location.search.substr(1).split('&');
              let i=0;
          
              for(; i<kvp.length; i++){
                  if (kvp[i].startsWith(key + '=')) {
                      let pair = kvp[i].split('=');
                      pair[1] = value;
                      kvp[i] = pair.join('=');
                      break;
                  }
              }
          
              if(i >= kvp.length){
                  kvp[kvp.length] = [key,value].join('=');
              }
          
              // can return this or...
              let params = kvp.join('&');
          
              // reload page with new params
              document.location.search = params;
          }
          "
      ];

       // IS ACTIVE
       $this->crud->addField([
        'name' => 'is_moderated',
        'label' => 'Опубликован',
        'type' => 'boolean',
        'default' => '1',
      ]);

      // HTML
      $this->crud->addField([
        'name' => 'landing_id',
        'label' => 'Лендинг',
        'type' => 'select_from_array',
        'options' => $this->filter_landings,
        'default' => null,
        'value' => $this->getLandingId(),
        'attributes' => $js_attributes,
        'placeholder' => "Выберите лендинг"
      ]);

      // PAGE
      $this->crud->addField([
        'name' => 'page_id',
        'label' => 'Страница',
        'type' => 'select_from_array',
        'options' => $this->pages_list,
        'allows_null' => false,
        // 'value' => $this->getLandingId(),
        // 'attributes' => $js_attributes,
        'placeholder' => "Выберите страницу"
      ]);

    //   $this->crud->addField([
    //    'name' => 'page_id',
    //    'label' => 'Страница',
    //    'type' => 'relationship',
    //    'model'     => 'App\Models\Page',
    //    'attribute' => 'uniqNameAdmin',
    //    'entity' => 'page',
    //    'multiple' => false,
    //    'placeholder' => "Выберите страницу"
    //  ]);

       // PARENT
      $this->crud->addField([
        'name' => 'parent_id',
        'label' => 'Родительский комментарий',
        'type' => 'relationship',
        'model'     => 'App\Models\Review',
        'attribute' => 'uniqNameAdmin',
        'entity' => 'parent',
        'multiple' => false,
        'placeholder' => "Выберите комментарий"
      ]);



      $this->crud->addField([
        'name' => 'author',
        'label' => 'Имя автора',
        'type' => 'text',
      ]);

      $this->crud->addField([
        'name' => 'photo',
        'label' => 'Фото автора',
        'type' => 'browse',
      ]);

      $this->crud->addField([
        'name' => 'published_at',
        'label' => 'Дата и время публикации',
        'type' => 'datetime',
      ]);

      $this->crud->addField([
        'name' => 'text',
        'label' => 'Текст отзыва',
        'type' => 'ckeditor'
      ]);

      $this->crud->addField([
        'name' => 'rating',
        'label' => 'Рейтинг',
        'type' => 'range',
        'attributes' => [
            'min' => 1,
            'max' => 5,
        ],
      ]);


      $this->crud->addField([
        'name' => 'likes',
        'label' => 'Лайки',
        'type' => 'number',
        'default' => 0
      ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


    private function setEntry() {
      if($this->crud->getCurrentOperation() === 'update')
        $this->entry = $this->crud->getEntry(\Route::current()->parameter('id'));
      else
        $this->entry = null;
    }

    private function getLandingId() {
      $landing_id = \Request::get('landing_id');

      if(\Request::has('landing_id')){
        return $landing_id? $landing_id: 'null';
      } elseif($this->entry && $this->entry->landing_id){
        return $this->entry->landing_id;
      } else {
        return 'null';
      }

    }
}
