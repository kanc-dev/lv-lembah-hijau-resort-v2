<table>
    <thead>
        <tr>
            <th>Nama Event</th>
            <th>Unit (Branch)</th> <!-- Menambahkan kolom untuk unit -->
            @foreach ($dates as $date)
                <th>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($groupedData as $eventName => $dateData)
            <tr>
                <td>{{ $eventName }}</td>
                <td>{{ $branch ? $branch->name : 'All Branches' }}</td> <!-- Menampilkan nama branch -->
                @foreach ($dateData as $value)
                    <td>{{ $value === '' ? '' : $value }}</td> <!-- Menampilkan kosong jika tidak ada data -->
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
