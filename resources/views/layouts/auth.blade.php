<!DOCTYPE html>
<html lang="en">
  <head>
    @include('layouts.header')
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          @yield('content')
        </div>
        </div>
      </div>
    @include('layouts.footer')
  </body>
</html>
