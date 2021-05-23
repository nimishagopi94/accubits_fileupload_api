<!DOCTYPE html>
<html>
<body>
<h1>Errors Found In The Uploaded File </h1>
@foreach($body as $errors)
    <p>{{ $errors }}</p>
@endforeach
<p>Thank you</p>
</body>
</html>
