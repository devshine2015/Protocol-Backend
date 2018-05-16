<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $providerName }} Login</title>
    </head>
    <body>
      <script>
        setTimeout(function () {
          window.postMessage({
            type: 'OAUTH_RESULT',
            data: {!! $result !!}
          }, '*');
        }, 500);
      </script>
    </body>
</html>