<?php

namespace App\Observers;

use App\Models\Guest;

class GuestObserver
{
    /**
     * Handle the Guest "created" event.
     */
    public function created(Guest $guest): void
    {
        //
    }

    /**
     * Handle the Guest "updated" event.
     */
    public function updated(Guest $guest)
    {
        // Pastikan hanya menghitung tamu yang check-in
        if ($guest->isDirty('tanggal_checkin') && $guest->tanggal_checkin !== null) {
            $this->updateBranchGuestCount($guest, 'checkin');
        }

        // Jika tamu check-out, kurangi jumlah tamu
        if ($guest->isDirty('tanggal_checkout') && $guest->tanggal_checkout !== null) {
            $this->updateBranchGuestCount($guest, 'checkout');
        }
    }

    private function updateBranchGuestCount($guest, $action)
    {
        $branchId = $guest->branch_id;
        $checkinDate = $guest->tanggal_checkin;
        $checkoutDate = $guest->tanggal_checkout ?: now(); // Jika masih menginap, gunakan tanggal sekarang

        // Tentukan tanggal-tanggal yang perlu dihitung
        $dates = [];
        foreach (new \DatePeriod(new \DateTime($checkinDate), new \DateInterval('P1D'), new \DateTime($checkoutDate)) as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        // Jika aksi adalah 'checkin', tambahkan tamu
        // Jika aksi adalah 'checkout', kurangi tamu
        $count = ($action === 'checkin') ? 1 : -1;

        // Update atau buat record jumlah tamu di tabel branch_guest_counts
        foreach ($dates as $date) {
            BranchGuestCount::updateOrCreate(
                ['branch_id' => $branchId, 'date' => $date],
                ['guest_count' => DB::raw('guest_count + ' . $count)]
            );
        }
    }

    /**
     * Handle the Guest "deleted" event.
     */
    public function deleted(Guest $guest): void
    {
        //
    }

    /**
     * Handle the Guest "restored" event.
     */
    public function restored(Guest $guest): void
    {
        //
    }

    /**
     * Handle the Guest "force deleted" event.
     */
    public function forceDeleted(Guest $guest): void
    {
        //
    }
}
