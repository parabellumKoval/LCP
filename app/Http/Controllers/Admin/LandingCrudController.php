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

      // DISK
      $this->crud->addField([
        'name' => 'disk',
        'label' => 'Путь к диску',
        'hint' => 'Укажите абсолютный путь к диску на сервере (для хостинга файлов). Например: <code>absolute/path/to/landing_root/public/disk_name</code>',
        'type' => 'text',
        'fake' => true,
        'store_in' => 'extras',
        'tab' => 'Основное'
      ]);

      //
      $this->crud->addField([
        'name' => 'footer',
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
        'fake' => true,
        'store_in' => 'fields',
        'tab' => 'Подвал'
      ]);

      $this->crud->addField([
        'name' => 'footer_html',
        'label' => 'HTML подвала',
        'type' => 'ace',
        'attributes' => [
          'rows' => 10
        ],
        'tab' => 'Подвал'
      ]);

      //
      $this->crud->addField([
        'name' => 'header',
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
        'fake' => true,
        'store_in' => 'fields',
        'tab' => 'Шапка'
      ]);

      $this->crud->addField([
        'name' => 'header_html',
        'label' => 'HTML шапки',
        'type' => 'ace',
        'attributes' => [
          'rows' => 10
        ],
        'tab' => 'Шапка'
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
        'tab' => 'Теги'
      ]);

      // SEO
      $this->crud->addField([
        'name' => 'head_tags',
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
        'fake' => true,
        'store_in' => 'seo',
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
        'name' => 'robots_txt',
        'label' => "robots.txt", 
        'type' => 'textarea',
        'attributes' => [
          'rows' => 10
        ],
        'tab' => 'SEO'
      ]);


      $this->crud->addField([
        'name' => 'locale',
        'label' => "Язык сайта",
        'fake' => true, 
        'store_in' => 'seo',
        'hint' => '<code>og:locale</code>, <code>html lang=""</code>',
        'tab' => 'SEO'
      ]);

      $this->crud->addField([
        'name' => 'site_name',
        'label' => "og:site_name",
        'fake' => true, 
        'store_in' => 'seo',
        'tab' => 'SEO'
      ]);



      $this->crud->addField([
        'name' => 'timeout',
        'label' => "Таймер", 
        'type' => 'number',
        'attributes' => ["step" => 1],
        'suffix' => 'сек.',
        'fake' => true, 
        'store_in' => 'extras',
        'hint' => 'Время по истечению которого будет проихведен переход на страницу укзанную ниже.',
        'tab' => 'Редирект'
      ]);

      $this->crud->addField([
        'name' => 'redirect_link',
        'label' => "Редирект на url", 
        'type' => 'text',
        'fake' => true, 
        'store_in' => 'extras',
        'hint' => 'Url страницы на которую будет произведен переход.',
        'tab' => 'Редирект'
      ]);

      // 
      $this->crud->addField([
        'name' => 'closed_html',
        'label' => 'HTML страницы',
        'type' => 'ace',
        'attributes' => [
          'rows' => 10
        ],
        'tab' => 'Заглушка'
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
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_block_title',
        'label' => 'Заголовок',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_block_desc_1',
        'label' => 'до "всего отзывов"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_block_desc_2',
        'label' => 'после "всего отзывов"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_block_more_show',
        'label' => 'Кнопка "показать все отзывы"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_block_more_hide',
        'label' => 'Кнопка "спрятать отзывы"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'delimiter_12',
        'type' => 'custom_html',
        'value' => '<h4>Сортировка отзывов</h4>',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_title',
        'label' => 'Заголовок',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_date_desc',
        'label' => 'Сначала самые новые',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_date_asc',
        'label' => 'Сначала самые старые',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_usefull_desc',
        'label' => 'Сначала полезные',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_sort_usefull_asc',
        'label' => 'Сначала бесполезные',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      // 
      $this->crud->addField([
        'name' => 'delimiter_2',
        'type' => 'custom_html',
        'value' => '<h4>Карточка отзыва</h4>',
        'tab' => 'Переводы'
      ]);
      
      $this->crud->addField([
        'name' => 'review_like_btn',
        'label' => 'Кнопка "понравилось"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_reply_btn',
        'label' => 'Кнопка "ответ на комментарий"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      //
      $this->crud->addField([
        'name' => 'delimiter_3',
        'type' => 'custom_html',
        'value' => '<h4>Форма отзывов</h4>',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_form_title',
        'label' => 'Заголовок',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_form_submit_btn',
        'label' => 'Кнопка "отправить отзыв"',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);
      
      $this->crud->addField([
        'name' => 'review_form_confirm',
        'label' => 'Вы не робот',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_form_name_palceholder',
        'label' => 'Плейсхолдер поля "ваше имя',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);


      //
      $this->crud->addField([
        'name' => 'delimiter_4',
        'type' => 'custom_html',
        'value' => '<h4>Сообщения</h4>',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_form_error_title',
        'label' => 'Ошибка отправки комментария',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_form_error_details',
        'label' => 'Включить подробную информациию об ошибке?',
        'type' => 'checkbox',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);

      $this->crud->addField([
        'name' => 'review_form_success',
        'label' => 'Спасибо за отзыв',
        'fake' => true, 
        'store_in' => 'strings',
        'tab' => 'Переводы'
      ]);
    }
}
