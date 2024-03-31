<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @if(isset($link))
    <title>{{ $link->title }}</title>
    <meta name="description" content="{{ $link->description }}">
    <meta property="og:title" content="{{ $link->keywords }}" />
    <meta property="og:description" content="{{ $link->description }}" />
    <meta property="og:image" content="{{ asset('storage/img/link/'.$link->thumbnail_image) }}" />
    <meta property="og:url" content="{{ $link->original_link }}" />
  @elseif(isset($setting))
    <title>Other</title>
    <meta property="og:url" content="{{ $setting->default_redirect_link }}" />
  @endif
</head>
<body>
  <script>
    @if(isset($link))
      @if(isset($link->original_link))
        setTimeout(function() {
          window.location.href = "{{ $link->original_link }}";
        }, 50);
      @elseif(isset($redirectLink->url))
        setTimeout(function() {
            window.location.href = "{{ $redirectLink->url }}";
          }, 50);
      @else
        setTimeout(function() {
            window.location.href = "{{ $setting->auto_redirect_to }}";
          }, 50);
      @endif
    @elseif(isset($setting))
      setTimeout(function() {
        window.location.href = "{{ $setting->auto_redirect_to }}";
      }, 50);
    @endif
  </script>
</body>
</html>
