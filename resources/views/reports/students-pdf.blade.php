<!DOCTYPE html>
<html>
<head>
    <title>Student List Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #1e2530; }
        .header p { margin: 5px 0 0; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background-color: #2563eb; color: #ffffff; text-transform: uppercase; font-size: 10px; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Student Management System</h2>
        <p>Filtered Student List Report</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Reg No</th>
                <th>Name</th>
                <th>Email</th>
                <th>DOB</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->reg_no }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->dob }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No students found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated Date: {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>