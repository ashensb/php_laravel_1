<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Welcome, {{ Auth::user()->name }} (Student)</h2>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
        <p>This is the Student Dashboard overview page.</p>
    </div>
</body>
</html>