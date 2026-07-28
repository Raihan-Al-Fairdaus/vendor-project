<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vendors List</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
            word-break: break-word;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .maps-link {
            font-size: 10px;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <h2>Vendor Registration List</h2>

    <p>
        <strong>Generated on:</strong>
        {{ now()->format('Y-m-d H:i:s') }}
    </p>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Category</th>
                <th>Email</th>
                <th>PIC</th>
                <th>NPWP</th>
                <th>Google Maps</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>

            @forelse($vendors as $vendor)

                <tr>

                    <td>{{ $vendor->id }}</td>

                    <td>{{ $vendor->company_name }}</td>

                    <td>{{ $vendor->business_category }}</td>

                    <td>{{ $vendor->company_email }}</td>

                    <td>{{ $vendor->pic_name }}</td>

                    <td>{{ $vendor->npwp ?? '-' }}</td>

                    <td class="maps-link">
                        @if($vendor->google_maps_link)
                            {{ $vendor->google_maps_link }}
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ ucfirst($vendor->status) }}</td>

                    <td>{{ optional($vendor->created_at)->format('Y-m-d') }}</td>

                </tr>

            @empty

                <tr>
                    <td colspan="9" style="text-align:center;">
                        No vendor data available.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</body>
</html>