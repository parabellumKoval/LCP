@php
$value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? "null";
$editor_id = 'editor-' . $field['name'];
@endphp
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    <div id="{{ $editor_id }}" class="ace-editor"></div>
    
    <input
      name="{{ $field['name'] }}"
      type="text"
      hidden
      id="{{ $editor_id }}-hidden_value"
      value="{{ $value }}"
    >

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    @push('crud_fields_scripts')
      <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.36.2/ace.min.js" integrity="sha512-xylzfb6LZn1im1ge493MNv0fISAU4QkshbKz/jVh6MJFAlZ6T1NRDJa0ZKb7ECuhSTO7fVy8wkXkT95/f4R4nA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.36.2/mode-html.min.js" integrity="sha512-JSkDZ0Crk8+F/F/lCqh6LZ/mzPqhSrRanUC3TExEziPsbLPFIJQmR5R7p954sdM88cOL9+WlVR6XBFJa3nXcdA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.36.2/theme-chrome.min.js" integrity="sha512-Iu1SMFSWTfo6i4ZZpT5L+nEIJW5Qzw3W/j6DT1BNNB3GSD2dNxgywMjqWtqKNrbylRYbv0axPPg6uPrm1YG2SA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.36.2/theme-one_dark.min.js" integrity="sha512-IzwM6BfvrK5Yt8SGQXeP/k6CHHMQRsjHHiqf3txqNuAA1fOIZa1aQEWBsY9X+X+xF60e8xuSIQbNhmFmiaxs4Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endpush


    @push('crud_fields_styles')
    <style>
        .ace-editor {
        position: relative;
        width: 100%;
        height: 400px;
    }
    </style>
    @endpush
@endif

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
<script>
{
  const value = {!! !empty($value)? $value: "null" !!};
  const editorId = "{!! $editor_id !!}";

  const editor = ace.edit(editorId);
  editor.setTheme("ace/theme/one_dark");
  
  const HtmlMode = ace.require("ace/mode/html").Mode;
  editor.session.setMode(new HtmlMode());

  editor.session.setValue(value);

  editor.session.on('change', function(delta) {
      document.getElementById(editorId + "-hidden_value").value = JSON.stringify(editor.getValue());
  });
}
</script>

@endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
