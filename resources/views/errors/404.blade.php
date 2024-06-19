<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center">
        <h1 class="display-1">404</h1>
        <p class="lead">Sorry, the page you are looking for could not be found.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
    </div>
</body>
</html>
