<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Page;
use App\Models\Landing;
/**
 * Class SupplierCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PageCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    private $filter_landings = [];

    public function setup()
    {
        $this->crud->setModel(Page::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/page');
        $this->crud->setEntityNameStrings('страница', 'страницы');


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
        'name' => 'is_active',
        'label' => 'Активный',
        'type' => 'select2',
      ], function(){
        return [
          0 => '🔴 Не активный',
          1 => '🟢 Активный',
        ];
      }, function($is_active){
        $this->crud->query->where('is_active', $is_active);
      });

      $this->crud->addColumn([
        'name' => 'is_active',
        'label' => '🟢',
        'type' => 'check'
      ]);

      $this->crud->addColumn([
        'name' => 'name',
        'label' => 'Название',
      ]);

      $this->crud->addColumn([
        'name' => 'slug',
        'label' => 'URL',
      ]);

      $this->crud->addColumn([
        'name' => 'landing',
        'label' => 'Лендинг',
        'type' => 'relationship'
      ]);
    }

    protected function setupCreateOperation()
    {
      $this->crud->setValidation(PageRequest::class);

      // IS ACTIVE
      $this->crud->addField([
        'name' => 'is_active',
        'label' => 'Активен',
        'type' => 'boolean',
        'default' => '1',
        'tab' => 'Основное'
      ]);

      // IS ACTIVE
      $this->crud->addField([
        'name' => 'is_home',
        'label' => 'Главная страница',
        'type' => 'boolean',
        'default' => '0',
        'tab' => 'Основное'
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
        'placeholder' => "Выберите дендинг",
        'tab' => 'Основное'
      ]);

      $this->crud->addField([
        'name' => 'name',
        'label' => 'Название',
        'type' => 'text',
        'tab' => 'Основное'
      ]);

      // SLUG
      $this->crud->addField([
        'name' => 'slug',
        'label' => 'Slug',
        'hint' => 'По-умолчанию будет сгенерирован из названия.',
        'tab' => 'Основное'
      ]);


      // HTML
      $this->crud->addField([
        'name' => 'content',
        'label' => 'Контент',
        'type' => 'textarea',
        'attributes' => [
          'rows' => 10
        ],
        'tab' => 'Контент'
      ]);

      // SEO
      $this->crud->addField([
        'name' => 'meta_title',
        'label' => "Meta Title", 
        'type' => 'text',
        'fake' => true, 
        'store_in' => 'seo',
        'tab' => 'SEO'
      ]);

      $this->crud->addField([
          'name' => 'meta_description',
          'label' => "Meta Description", 
          'type' => 'textarea',
          'fake' => true, 
          'store_in' => 'seo',
          'tab' => 'SEO'
      ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
