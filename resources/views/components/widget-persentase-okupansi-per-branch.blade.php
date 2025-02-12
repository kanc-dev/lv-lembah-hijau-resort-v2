<div class="card card-height-100">
    <div class="card-header align-items-center d-flex">
        <h4 class="mb-0 card-title flex-grow-1">Persentase Okupansi</h4>
    </div><!-- end card header -->

    <!-- card body -->
    <div class="card-body">
        <x-chart.branch-occupancy-pie />

        <div class="mt-3 table-responsive table-card">
            <table
                class="table mb-1 align-middle table-borderless table-sm table-centered table-nowrap">
                <thead
                    class="border border-dashed text-muted border-start-0 border-end-0 bg-soft-light">
                    <tr>
                        <th>Branch / Unit</th>
                        <th style="width: 30%;">Kapasitas</th>
                        <th style="width: 30%;">Terisi</th>
                    </tr>
                </thead>
                <tbody class="border-0">
                    @foreach ($data['list_okupansi_branch'] as $occupancy)
                        <tr>
                            <td>{{ $occupancy['branch'] }}</td>
                            <td>{{ $occupancy['kapasitas'] }}</td>
                            <td>{{ $occupancy['terisi'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- end card body -->
</div><!-- end card -->