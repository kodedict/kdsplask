<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Kdsplask | PHP</title>
    </head>
    <body>
        Main Template
    @include('partials/_menu')
    <div>
        Header
    </div>
    <div>
        @yield('pageContent')
    </div>
    <footer>
        footer
    </footer>
    </body>
</html>
