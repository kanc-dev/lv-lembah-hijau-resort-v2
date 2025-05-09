<div class="modal fade" id="plotRoomModal{{ $guest->id }}" tabindex="-1"
    aria-labelledby="plotRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="plotRoomModalLabel">Pilih Kamar untuk Tamu: {{ $guest->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="roomsTable" class="table text-center align-middle table-sm table-striped table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kamar (Unit)</th>
                                <th>Kapasitas</th>
                                <th>Bed Terisi</th>
                                <th>Bed Tersedia</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-scroll">
                            <!-- Data kamar akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

