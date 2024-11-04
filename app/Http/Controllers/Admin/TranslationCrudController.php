<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TranslationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Translation;
/**
 * Class SupplierCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TranslationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    private $locales = [];

    public function setup()
    {
        $this->crud->setModel(Translation::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/translation');
        $this->crud->setEntityNameStrings('перевод', 'переводы');

        $this->locales = config('backpack.crud.locales');
        // dd($this->locales);
    }

    protected function setupListOperation()
    {

      $this->crud->addColumn([
        'name' => 'locale',
        'label' => 'Язык',
      ]);

      $this->crud->addColumn([
        'name' => 'landing',
        'label' => 'Лендинг',
        'type' => 'relationship',
        'default' => 'Общее'
      ]);
    }

    protected function setupCreateOperation()
    {
      $this->crud->setValidation(TranslationRequest::class);

      //
      $this->crud->addField([
        'name' => 'locale',
        'label' => 'Язык',
        'type' => 'select_from_array',
        'options' => $this->locales
      ]);

      //
      $this->crud->addField([
        'name' => 'landing',
        'label' => 'Лендинг',
        'type' => 'relationship',
        'model'     => 'App\Models\Landing',
        'attribute' => 'name',
        'entity' => 'landing',
        'multiple' => false,
        'placeholder' => "Выберите лендинг",
        'hint' => 'Выберите из списка лендинг чтобы перезаписать стандартные значения',
      ]);


      $this->getLangFields();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


    private function getLangFields() {

      $this->crud->addField([
        'name' => 'delimiter_1',
        'type' => 'custom_html',
        'value' => '<h4>Блок с отзывами</h4>',
        'tab' => 'Блок с отзывами'
      ]);

      $this->crud->addField([
        'name' => 'review_block_title',
        'label' => 'Заголовок',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Блок с отзывами'
      ]);

      $this->crud->addField([
        'name' => 'review_block_desc_1',
        'label' => 'до "всего отзывов"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Блок с отзывами'
      ]);

      $this->crud->addField([
        'name' => 'review_block_desc_2',
        'label' => 'после "всего отзывов"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Блок с отзывами'
      ]);

      $this->crud->addField([
        'name' => 'review_block_more_show',
        'label' => 'Кнопка "показать все отзывы"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Блок с отзывами'
      ]);

      $this->crud->addField([
        'name' => 'review_block_more_hide',
        'label' => 'Кнопка "спрятать отзывы"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Блок с отзывами'
      ]);

      $this->crud->addField([
        'name' => 'delimiter_12',
        'type' => 'custom_html',
        'value' => '<h4>Сортировка отзывов</h4>',
        'tab' => 'Сортировка отзывов'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_title',
        'label' => 'Заголовок',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Сортировка отзывов'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_date_desc',
        'label' => 'Сначала самые новые',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Сортировка отзывов'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_date_asc',
        'label' => 'Сначала самые старые',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Сортировка отзывов'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_usefull_desc',
        'label' => 'Сначала полезные',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Сортировка отзывов'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_usefull_asc',
        'label' => 'Сначала бесполезные',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Сортировка отзывов'
      ]);

      // 
      $this->crud->addField([
        'name' => 'delimiter_2',
        'type' => 'custom_html',
        'value' => '<h4>Карточка отзыва</h4>',
        'tab' => 'Карточка отзыва'
      ]);
      
      $this->crud->addField([
        'name' => 'review_like_btn',
        'label' => 'Кнопка "понравилось"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Карточка отзыва'
      ]);

      $this->crud->addField([
        'name' => 'review_reply_btn',
        'label' => 'Кнопка "ответ на комментарий"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Карточка отзыва'
      ]);

      //
      $this->crud->addField([
        'name' => 'delimiter_3',
        'type' => 'custom_html',
        'value' => '<h4>Форма отзывов</h4>',
        'tab' => 'Форма отзывов'
      ]);

      $this->crud->addField([
        'name' => 'review_form_title',
        'label' => 'Заголовок',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Форма отзывов'
      ]);

      $this->crud->addField([
        'name' => 'review_form_submit_btn',
        'label' => 'Кнопка "отправить отзыв"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Форма отзывов'
      ]);
      
      $this->crud->addField([
        'name' => 'review_form_confirm',
        'label' => 'Вы не робот',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Форма отзывов'
      ]);

      $this->crud->addField([
        'name' => 'review_form_name_palceholder',
        'label' => 'Плейсхолдер поля "ваше имя"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Форма отзывов'
      ]);


      //
      $this->crud->addField([
        'name' => 'delimiter_4',
        'type' => 'custom_html',
        'value' => '<h4>Сообщения</h4>',
        'tab' => 'Сообщения'
      ]);

      $this->crud->addField([
        'name' => 'review_form_error_title',
        'label' => 'Ошибка отправки комментария',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Сообщения'
      ]);

      $this->crud->addField([
        'name' => 'review_form_success',
        'label' => 'Спасибо за отзыв',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Сообщения'
      ]);
    }
}
