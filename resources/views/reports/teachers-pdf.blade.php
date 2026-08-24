<!DOCTYPE html>
<html>
<head>
    <title>Teacher List Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #7c3aed; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #1e2530; }
        .header p { margin: 5px 0 0; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background-color: #7c3aed; color: #ffffff; text-transform: uppercase; font-size: 10px; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Student Management System</h2>
        <p>Filtered Teacher List Report</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Qualification</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->phone }}</td>
                    <td>{{ $teacher->qualification }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No teachers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated Date: {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>