<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LandingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Landing;
/**
 * Class SupplierCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LandingCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup()
    {
        $this->crud->setModel(Landing::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/landing');
        $this->crud->setEntityNameStrings('лендинг', 'лендинги');
    }

    protected function setupListOperation()
    {
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
        'name' => 'key',
        'label' => 'Ключ',
      ]);

      $this->crud->addColumn([
        'name' => 'pages',
        'label' => 'Кол-во страниц',
        'type' => 'relationship_count'
      ]);
    }

    protected function setupCreateOperation()
    {
      $this->crud->setValidation(LandingRequest::class);

      // IS ACTIVE
      $this->crud->addField([
        'name' => 'is_active',
        'label' => 'Активен',
        'type' => 'boolean',
        'default' => '1',
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
        'name' => 'key',
        'label' => 'Ключ',
        'hint' => 'По-умолчанию будет сгенерирован из названия.',
        'tab' => 'Основное'
      ]);

      // HTML
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
        'tab' => 'HTML'
      ]);

      $this->crud->addField([
        'name' => 'header_html',
        'label' => 'HTML шапки',
        'type' => 'textarea',
        'attributes' => [
          'rows' => 10
        ],
        'tab' => 'HTML'
      ]);

      $this->crud->addField([
        'name' => 'footer_html',
        'label' => 'HTML подвала',
        'type' => 'textarea',
        'attributes' => [
          'rows' => 10
        ],
        'tab' => 'HTML'
      ]);

      $this->crud->addField([
        'name' => 'cssLink',
        'label' => 'CSS-файл',
        'type' => 'upload',
        'upload'    => true,
        'disk' => 'cdn',
        'hint' => 'Файлу будет присвоено название по-умолчанию styles.css',
        'tab' => 'HTML'
      ]);

      $this->crud->addField([
        'name' => 'jsLink',
        'label' => 'JS-файл',
        'type' => 'upload',
        'upload'    => true,
        'disk' => 'cdn',
        'hint' => 'Файлу будет присвоено название по-умолчанию scripts.js',
        'tab' => 'HTML'
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
