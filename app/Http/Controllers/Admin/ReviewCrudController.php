<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReviewRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Review;
use App\Models\Landing;

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

    public function setup()
    {
        $this->crud->setModel(Review::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/review');
        $this->crud->setEntityNameStrings('отзыв', 'отзывы');

        $this->filter_landings = Landing::pluck('name', 'id')->toArray();
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

       // IS ACTIVE
       $this->crud->addField([
        'name' => 'is_moderated',
        'label' => 'Опубликован',
        'type' => 'boolean',
        'default' => '1',
      ]);

      // HTML
      $this->crud->addField([
        'name' => 'landing',
        'label' => 'Лендинг',
        'type' => 'relationship',
        'model'     => 'App\Models\Landing',
        'attribute' => 'name',
        'entity' => 'landing',
        'multiple' => false,
        'placeholder' => "Выберите дендинг"
      ]);


       // IS ACTIVE
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
}
