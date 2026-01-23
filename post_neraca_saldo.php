<?php
/**
 * Script untuk posting semua jurnal ke neraca_saldo
 * 
 * Cara menjalankan:
 * php artisan tinker
 * require 'post_neraca_saldo.php';
 */

use App\Models\Jurnal;
use App\Services\AccountingService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$accountingService = app(AccountingService::class);

echo "🔍 Mencari jurnal yang sudah posted...\n\n";

// Ambil semua jurnal posted, group by desa_id dan periode
$jurnals = Jurnal::where('status', 'posted')
    ->select('desa_id', DB::raw("DATE_FORMAT(tanggal_transaksi, '%Y-%m') as periode"))
    ->distinct()
    ->get();

if ($jurnals->isEmpty()) {
    echo "❌ Tidak ada jurnal yang sudah posted.\n";
    exit;
}

echo "📊 Ditemukan " . $jurnals->count() . " kombinasi desa + periode:\n\n";

$processed = 0;
$errors = [];

foreach ($jurnals as $item) {
    $desaId = $item->desa_id;
    $periode = $item->periode;
    
    // Cek jumlah jurnal untuk periode ini
    $countJurnal = Jurnal::where('desa_id', $desaId)
        ->where('status', 'posted')
        ->whereRaw("DATE_FORMAT(tanggal_transaksi, '%Y-%m') = ?", [$periode])
        ->count();
    
    echo sprintf(
        "  📅 Desa ID: %d | Periode: %s | Jurnal: %d\n",
        $desaId,
        $periode,
        $countJurnal
    );
}

echo "\n🚀 Memulai posting ke neraca_saldo...\n\n";

foreach ($jurnals as $item) {
    $desaId = $item->desa_id;
    $periode = $item->periode;
    
    try {
        echo sprintf("  ⏳ Processing Desa %d - Periode %s... ", $desaId, $periode);
        
        // Recalculate balance untuk periode ini (tanpa unit usaha)
        $accountingService->recalculateBalance($desaId, $periode, null);
        
        // Cek juga untuk setiap unit usaha
        $unitUsahas = DB::table('jurnal')
            ->where('desa_id', $desaId)
            ->where('status', 'posted')
            ->whereRaw("DATE_FORMAT(tanggal_transaksi, '%Y-%m') = ?", [$periode])
            ->whereNotNull('unit_usaha_id')
            ->distinct()
            ->pluck('unit_usaha_id');
        
        foreach ($unitUsahas as $unitUsahaId) {
            $accountingService->recalculateBalance($desaId, $periode, $unitUsahaId);
        }
        
        // Cek hasil
        $countNeracaSaldo = DB::table('neraca_saldo')
            ->where('desa_id', $desaId)
            ->where('periode', $periode)
            ->count();
        
        echo sprintf("✅ Done (%d akun)\n", $countNeracaSaldo);
        $processed++;
        
    } catch (\Exception $e) {
        echo sprintf("❌ Error: %s\n", $e->getMessage());
        $errors[] = [
            'desa_id' => $desaId,
            'periode' => $periode,
            'error' => $e->getMessage(),
        ];
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 SUMMARY\n";
echo str_repeat("=", 60) . "\n";
echo sprintf("✅ Berhasil: %d periode\n", $processed);
echo sprintf("❌ Error: %d periode\n", count($errors));

if (!empty($errors)) {
    echo "\n❌ Detail Error:\n";
    foreach ($errors as $error) {
        echo sprintf(
            "  - Desa %d, Periode %s: %s\n",
            $error['desa_id'],
            $error['periode'],
            $error['error']
        );
    }
}

echo "\n✅ Selesai!\n";
