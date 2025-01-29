<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $branchName }} Room Report</title>
</head>

<body>
    <h1>{{ $branchName }} Room Report ({{ $startDate }} to {{ $endDate }})</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Unit</th>
                @foreach ($allDates as $date)
                    <th>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th> <!-- Display formatted date -->
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedData as $eventName => $dates)
                <tr>
                    <td>{{ $eventName }}</td>
                    <td>{{ $branchName }}</td>
                    @foreach ($allDates as $date)
                        <td>{{ $dates[$date->format('Y-m-d')] ?? '' }}</td> <!-- Format date as string -->
                    @endforeach
                </tr>
            @endforeach
        </tbody>

    </table>
</body>

</html>
