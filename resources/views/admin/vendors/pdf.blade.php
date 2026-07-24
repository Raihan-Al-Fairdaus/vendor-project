<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vendors List</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Vendor Registration List</h2>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Category</th>
                <th>Email</th>
                <th>PIC</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vendors as $vendor)
            <tr>
                <td>{{ $vendor->id }}</td>
                <td>{{ $vendor->company_name }}</td>
                <td>{{ $vendor->business_category }}</td>
                <td>{{ $vendor->company_email }}</td>
                <td>{{ $vendor->pic_name }}</td>
                <td>{{ ucfirst($vendor->status) }}</td>
                <td>{{ $vendor->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
