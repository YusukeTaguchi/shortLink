<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @if($link)
    <title>{{ $link->title }}</title>
    <meta name="description" content="{{ $link->description }}">
    <meta property="og:title" content="{{ $link->keywords }}" />
    <meta property="og:description" content="{{ $link->description }}" />
    <meta property="og:image" content="{{ asset('storage/img/link/'.$link->thumbnail_image) }}" />
    <meta property="og:url" content="{{ $link->original_link }}" />
  @else
    <title>Other</title>
    <meta property="og:url" content="{{ $setting->default_redirect_link }}" />
  @endif
</head>
<body>
  <script>
    @if($link)
      setTimeout(function() {
        window.location.href = "{{ $link->original_link }}";
      }, 50);
    @else
      setTimeout(function() {
        window.location.href = "{{ $setting->auto_redirect_to }}";
      }, 50);
    @endif
  </script>
</body>
</html>
