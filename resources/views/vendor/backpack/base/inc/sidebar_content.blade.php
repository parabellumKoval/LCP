<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class="nav-item">
  <a class="nav-link" href="{{ backpack_url('landing') }}">
    <i class="las la-pager nav-icon"></i> Лендинги
  </a>
</li>
<li class="nav-item">
  <a class="nav-link" href="{{ backpack_url('page') }}">
    <i class="las la-file-alt nav-icon"></i> Страницы
  </a>
</li>
<li class="nav-item">
  <a class="nav-link" href="{{ backpack_url('review') }}">
    <i class="las la-comments nav-icon"></i> Отзывы
  </a>
</li>
<li class="nav-item">
  <a class="nav-link" href="{{ backpack_url('translation') }}">
    <i class="las la-language nav-icon"></i> Переводы
  </a>
</li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>