<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <div class="container">
      <div class="card mt-4">
        <div class="card-header bg-primary text-white text-center">@yield('title')</div>
        <div class="card-body">
        @session('error')
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endsession
        @yield('content')
        </div>
      </div>
    </div>
    @stack('script') 
  </body>
</html>