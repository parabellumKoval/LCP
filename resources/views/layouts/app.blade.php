<html>
  <head>
      <title>App Name - @yield('title')</title>
      @if($landing->publicCssLink)
        <link href="{{ $landing->publicCssLink }}" rel="stylesheet" />
      @endif
  </head>
  <body>
      @if($landing->header_html)
        <header>
          {!! $landing->header_html !!}
        </header>
      @endif

      <div class="container">
          @yield('content')
      </div>

      @if($landing->footer_html)
        <footer>
          {!! $landing->footer_html ?? '' !!}
        </footer>
      @endif


      @if($landing->publicJsLink)
        <script type="text/javascript" src="{{ $landing->publicJsLink }}"></script>
      @endif
  </body>
</html>