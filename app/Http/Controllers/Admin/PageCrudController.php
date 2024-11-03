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

      $this->crud->addColumn([
        'name' => 'parent',
        'label' => 'Альтернативная для',
        'type' => 'relationship'
      ]);
    }

    protected function setupCreateOperation()
    {
      $this->crud->setValidation(PageRequest::class);

      // IN INDEX
      $this->crud->addField([
        'name' => 'in_index',
        'label' => 'Страница индексируется?',
        'type' => 'boolean',
        'default' => '1',
        'hint' => 'Уберите галочку если хотите скрыть страницу из индекса',
        'tab' => 'Основное'
      ]);

      // IS ACTIVE
      $this->crud->addField([
        'name' => 'is_active',
        'label' => 'Активен',
        'type' => 'boolean',
        'default' => '1',
        'hint' => 'Уберите галочку если хотите скрыть страницу',
        'tab' => 'Основное'
      ]);

      // IS ACTIVE
      $this->crud->addField([
        'name' => 'is_home',
        'label' => 'Главная страница',
        'type' => 'boolean',
        'default' => '0',
        'hint' => 'Может быть установлена только одна главная страница для лендинга.',
        'tab' => 'Основное'
      ]);

      // IS REVIEWS
      $this->crud->addField([
        'name' => 'is_reviews',
        'label' => 'Включить отзывы?',
        'type' => 'boolean',
        'default' => '0',
        'tab' => 'Основное'
      ]);

      $this->crud->addField([
        'name' => 'breadcrumbs',
        'label' => "Хлебные крошки", 
        'type' => 'checkbox',
        'default' => 1,
        'fake' => true, 
        'store_in' => 'extras',
        'hint' => 'Показать хлебные крошки на это странице (не влияет на микроразметки breadcrumbs, которая будет выводиться в любом случае)?',
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
        'placeholder' => "Выберите лендинг",
        'hint' => 'Выберите из списка лендинг к которому относится данная страница.',
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


      // Parent
      $this->crud->addField([
        'name' => 'parent',
        'label' => 'Альтернативная языковая версия страницы',
        'type' => 'relationship',
        'model'     => 'App\Models\Page',
        'attribute' => 'name',
        'entity' => 'parent',
        'multiple' => false,
        'placeholder' => "Выберите страницу",
        'hint' => 'Выберите из списка страницу альтернативной языковой версией которой является данная страница',
        'tab' => 'Основное'
      ]);

      // CONTENT
      $this->crud->addField([
        'name' => 'fields',
        'label' => "Шорткоды", 
        'type' => 'repeatable',
        'fields' => [
          [
            'name'  => 'shortcode',
            'type'  => 'text',
            'label' => 'Shortcode',
            'hint' => 'Ключ шорткода, без паттерна {{-- --}}',
          ],
          [
            'name'  => 'is_clear_tags',
            'type'  => 'checkbox',
            'label' => 'Очищать html-теги?',
          ],
          [
            'name'  => 'value',
            'type'  => 'ckeditor',
            'label' => 'Значение',
            'attributes' => [
              'rows' => 10
            ],
          ],
        ],
        'new_item_label'  => 'Добавить шорткод',
        'init_rows' => 0,
        'min_rows' => 0,
        'tab' => 'Контент'
      ]);

      $this->crud->addField([
        'name' => 'content',
        'label' => 'Html шаблон',
        'type' => 'ace',
        'hint' => 'Для замены части контента на значение шорткода необходимо исспользовать такой паттерн <code>{{-- shortcode --}}</code>',
        'tab' => 'Контент'
      ]);

      // SEO
      $this->crud->addField([
        'name' => 'head_stack',
        'label' => "Теги в head", 
        'type' => 'repeatable',
        'fields' => [
          [
            'name'  => 'tag_name',
            'type'  => 'text',
            'label' => 'Название (для администраторов)',
          ],
          [
            'name'  => 'tag',
            'type'  => 'textarea',
            'label' => 'Тег',
          ],
        ],
        'new_item_label'  => 'Добавить тег',
        'init_rows' => 0,
        'min_rows' => 0,
        'tab' => 'SEO'
      ]);

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

      $this->crud->addField([
          'name' => 'meta_keywords',
          'label' => "Meta Keywords", 
          'type' => 'textarea',
          'fake' => true, 
          'store_in' => 'seo',
          'tab' => 'SEO'
      ]);

      $this->crud->addField([
        'name' => 'locale',
        'label' => "Язык страницы",
        'fake' => true, 
        'store_in' => 'seo',
        'hint' => '<code>og:locale</code>, <code>html lang=""</code>',
        'tab' => 'SEO'
      ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
