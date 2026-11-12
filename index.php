<?php

require_once __DIR__ . '/Karyawan.php';
require_once __DIR__ . '/Manajer.php';

// ============================================================================
// 1. PENGUJIAN OBJEK KARYAWAN
// ============================================================================

// Instansiasi Objek Karyawan
$karyawan = new Karyawan(
    nik: "KRY-202401",
    nama: "Ahmad Fauzi",
    jabatan: "Staff",
    gajiPokok: 5000000.0,
    jamLembur: 8
);

// Perekaman Output Method Getter Awal Karyawan
$karyawanGettersAwal = [
    'getNik()'        => $karyawan->getNik(),
    'getNama()'       => $karyawan->getNama(),
    'getJabatan()'    => $karyawan->getJabatan(),
    'getGajiPokok()'  => $karyawan->getGajiPokok(),
    'getJamLembur()'  => $karyawan->getJamLembur()
];

// Pemanggilan 4 Method Bisnis Karyawan
$karyawanBisnis = [
    'hitungTunjanganJabatan()' => $karyawan->hitungTunjanganJabatan(),
    'hitungUangLembur()'       => $karyawan->hitungUangLembur(),
    'hitungPotongan()'         => $karyawan->hitungPotongan(),
    'hitungGajiBersih()'       => $karyawan->hitungGajiBersih()
];

$slipGajiKaryawanAwal = $karyawan->cetakSlipGaji();

// Pengujian Seluruh Method Setter Karyawan
$karyawan->setNama("Ahmad Fauzi, S.Kom");
$karyawan->setJabatan("Supervisor");
$karyawan->setGajiPokok(6500000.0);
$karyawan->setJamLembur(12);
$karyawan->setNik("KRY-202401-REV");

$karyawanGettersSetelahSetter = [
    'getNik()'        => $karyawan->getNik(),
    'getNama()'       => $karyawan->getNama(),
    'getJabatan()'    => $karyawan->getJabatan(),
    'getGajiPokok()'  => $karyawan->getGajiPokok(),
    'getJamLembur()'  => $karyawan->getJamLembur()
];

$slipGajiKaryawanSetelahSetter = $karyawan->cetakSlipGaji();

// ============================================================================
// 2. PENGUJIAN OBJEK MANAJER (INHERITANCE & METHOD SPESIFIK)
// ============================================================================

// Instansiasi Objek Manajer
$manajer = new Manajer(
    nik: "MGR-1001",
    nama: "Ratna Kusuma, M.M.",
    gajiPokok: 12000000.0,
    jamLembur: 5,
    departemen: "Divisi Keuangan & Akuntansi",
    jumlahBawahan: 6,
    tunjanganManajerial: 4000000.0,
    skorKpi: 94.0,
    anggaranDepartemen: 80000000.0
);

// Perekaman Seluruh Getter Manajer (Parent & Specific)
$manajerGetters = [
    'getNik()'                 => $manajer->getNik(),
    'getNama()'                => $manajer->getNama(),
    'getJabatan()'             => $manajer->getJabatan(),
    'getGajiPokok()'           => $manajer->getGajiPokok(),
    'getJamLembur()'           => $manajer->getJamLembur(),
    'getDepartemen()'          => $manajer->getDepartemen(),
    'getJumlahBawahan()'       => $manajer->getJumlahBawahan(),
    'getTunjanganManajerial()' => $manajer->getTunjanganManajerial(),
    'getSkorKpi()'             => $manajer->getSkorKpi(),
    'getAnggaranDepartemen()'  => $manajer->getAnggaranDepartemen()
];

// Pemanggilan Method Bisnis Spesifik Manajer
$tunjanganKepemimpinan = $manajer->hitungTunjanganKepemimpinan();
$bonusKpi             = $manajer->hitungBonusKpi();

// Pengujian Method alokasikanAnggaran()
$anggaranSebelumAlokasi = $manajer->getAnggaranDepartemen();
$statusAlokasi1 = $manajer->alokasikanAnggaran(25000000.0, "Pengadaan Software ERP");
$anggaranSetelahAlokasi1 = $manajer->getAnggaranDepartemen();
// Percobaan alokasi yang melebihi pagu (ekspektasi: gagal/false)
$statusAlokasiOverdraft = $manajer->alokasikanAnggaran(100000000.0, "Pembelian Gedung Kantor");

// Pemanggilan Method Override hitungGajiBersih() (Polymorphism)
$gajiBersihManajer = $manajer->hitungGajiBersih();

// Pemanggilan Method cetakSlipGajiManajer()
$slipGajiManajerAwal = $manajer->cetakSlipGajiManajer();

// Pengujian Seluruh Method Setter Spesifik Manajer
$manajer->setDepartemen("Divisi Keuangan, Akuntansi & Investasi");
$manajer->setJumlahBawahan(10);
$manajer->setTunjanganManajerial(5000000.0);
$manajer->setSkorKpi(98.5);
$manajer->setAnggaranDepartemen(120000000.0);

$manajerGettersSetelahSetter = [
    'getDepartemen()'          => $manajer->getDepartemen(),
    'getJumlahBawahan()'       => $manajer->getJumlahBawahan(),
    'getTunjanganManajerial()' => $manajer->getTunjanganManajerial(),
    'getSkorKpi()'             => $manajer->getSkorKpi(),
    'getAnggaranDepartemen()'  => $manajer->getAnggaranDepartemen()
];

$slipGajiManajerSetelahSetter = $manajer->cetakSlipGajiManajer();

// Format Rupiah Helper
$formatRp = fn(float $val) => "Rp " . number_format($val, 0, ',', '.');

// ============================================================================
// CLI HANDLER
// ============================================================================
if (php_sapi_name() === 'cli') {
    echo "====================================================================\n";
    echo "           DEMO PENGUJIAN LENGKAP METHOD: KARYAWAN & MANAJER         \n";
    echo "====================================================================\n\n";

    echo ">>> [1] PENGUJIAN OBJEK KARYAWAN <<<\n";
    echo "1.1 Pemanggilan Method Getter Awal:\n";
    foreach ($karyawanGettersAwal as $method => $val) {
        $valFormatted = is_numeric($val) && !str_starts_with($val, 'KRY') ? (is_float($val) ? $formatRp($val) : $val) : $val;
        echo "    - {$method} => {$valFormatted}\n";
    }

    echo "\n1.2 Pemanggilan 4 Method Bisnis:\n";
    echo "    - hitungTunjanganJabatan() => " . $formatRp($karyawanBisnis['hitungTunjanganJabatan()']) . "\n";
    echo "    - hitungUangLembur()       => " . $formatRp($karyawanBisnis['hitungUangLembur()']) . "\n";
    echo "    - hitungPotongan()         => " . $formatRp($karyawanBisnis['hitungPotongan()']) . "\n";
    echo "    - hitungGajiBersih()       => " . $formatRp($karyawanBisnis['hitungGajiBersih()']) . "\n";

    echo "\n1.3 Pemanggilan Method cetakSlipGaji():\n";
    echo $slipGajiKaryawanAwal;

    echo "\n1.4 Pengujian Seluruh Method Setter & Hasil Getter Baru:\n";
    foreach ($karyawanGettersSetelahSetter as $method => $val) {
        $valFormatted = is_numeric($val) && !str_starts_with($val, 'KRY') ? (is_float($val) ? $formatRp($val) : $val) : $val;
        echo "    - {$method} => {$valFormatted}\n";
    }
    echo "\nSlip Gaji Karyawan Setelah Setter:\n";
    echo $slipGajiKaryawanSetelahSetter;

    echo "\n====================================================================\n";
    echo ">>> [2] PENGUJIAN OBJEK MANAJER (INHERITANCE & METHOD SPESIFIK) <<<\n";
    echo "====================================================================\n\n";

    echo "2.1 Pemanggilan Method Getter Manajer (Parent & Child):\n";
    foreach ($manajerGetters as $method => $val) {
        if ($method === 'getSkorKpi()') {
            $valFormatted = number_format($val, 1) . " / 100";
        } elseif (in_array($method, ['getGajiPokok()', 'getTunjanganManajerial()', 'getAnggaranDepartemen()'])) {
            $valFormatted = $formatRp($val);
        } else {
            $valFormatted = (string)$val;
        }
        echo "    - {$method} => {$valFormatted}\n";
    }

    echo "\n2.2 Pemanggilan Method-Method Baru Manajer:\n";
    echo "    - hitungTunjanganKepemimpinan() => " . $formatRp($tunjanganKepemimpinan) . "\n";
    echo "    - hitungBonusKpi()              => " . $formatRp($bonusKpi) . "\n";
    echo "    - alokasikanAnggaran(Rp 25.000.000, ERP): " . ($statusAlokasi1 ? "BERHASIL (Sisa: " . $formatRp($anggaranSetelahAlokasi1) . ")" : "GAGAL") . "\n";
    echo "    - alokasikanAnggaran(Rp 100.000.000, Overdraft): " . ($statusAlokasiOverdraft ? "BERHASIL" : "GAGAL DITOLAK (Pagu Tidak Cukup)") . "\n";
    echo "    - hitungGajiBersih() [OVERRIDE] => " . $formatRp($gajiBersihManajer) . "\n";

    echo "\n2.3 Pemanggilan Method cetakSlipGajiManajer():\n";
    echo $slipGajiManajerAwal;

    echo "\n2.4 Pengujian Method Setter Manajer & Hasil Getter Baru:\n";
    foreach ($manajerGettersSetelahSetter as $method => $val) {
        if ($method === 'getSkorKpi()') {
            $valFormatted = number_format($val, 1) . " / 100";
        } elseif (in_array($method, ['getTunjanganManajerial()', 'getAnggaranDepartemen()'])) {
            $valFormatted = $formatRp($val);
        } else {
            $valFormatted = (string)$val;
        }
        echo "    - {$method} => {$valFormatted}\n";
    }
    echo "\nSlip Gaji Manajer Setelah Setter:\n";
    echo $slipGajiManajerSetelahSetter;
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTS PBO — Object-Oriented Payroll System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --bg: #09090b;
        --surface: #121217;
        --surface-hover: #18181f;
        --surface-glass: rgba(18, 18, 24, 0.72);
        --border: rgba(255, 255, 255, 0.08);
        --border-hover: rgba(255, 255, 255, 0.18);
        --border-glow: rgba(168, 85, 247, 0.35);
        --text: #f4f4f5;
        --text-muted: #a1a1aa;
        --text-sub: #71717a;
        --accent-purple: #a855f7;
        --accent-cyan: #06b6d4;
        --accent-emerald: #10b981;
        --accent-pink: #ec4899;
        --accent-amber: #f59e0b;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background-color: var(--bg);
        color: var(--text);
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
        line-height: 1.6;
        position: relative;
    }

    /* Ambient Motion.dev background grid & radial glow */
    .bg-grid {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
        z-index: 0;
        mask-image: radial-gradient(circle at 50% 30%, black 20%, transparent 80%);
        -webkit-mask-image: radial-gradient(circle at 50% 30%, black 20%, transparent 80%);
    }

    .ambient-glow {
        position: fixed;
        width: 700px;
        height: 700px;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
        filter: blur(120px);
        opacity: 0.18;
        transition: all 1s ease;
    }

    .glow-1 {
        top: -200px;
        left: 50%;
        transform: translateX(-50%);
        background: radial-gradient(circle, #8b5cf6, #3b82f6);
    }

    .glow-2 {
        top: 40%;
        right: -250px;
        background: radial-gradient(circle, #ec4899, #8b5cf6);
        opacity: 0.12;
    }

    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
        position: relative;
        z-index: 1;
    }

    /* Motion Navbar */
    nav {
        position: sticky;
        top: 16px;
        z-index: 100;
        margin-bottom: 48px;
    }

    .nav-inner {
        background: var(--surface-glass);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-radius: 9999px;
        padding: 10px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        transition: border-color 0.3s ease;
    }

    .nav-inner:hover {
        border-color: var(--border-hover);
    }

    .nav-links-menu {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .nav-menu-item {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 9999px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .nav-menu-item:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--border);
    }

    .nav-menu-item.active {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .btn-open-page {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 9999px;
        transition: all 0.2s;
    }

    .btn-open-karyawan {
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
        border: 1px solid rgba(59, 130, 246, 0.35);
    }

    .btn-open-karyawan:hover {
        background: rgba(59, 130, 246, 0.3);
        color: #ffffff;
        transform: translateX(3px);
    }

    .btn-open-manajer {
        background: rgba(168, 85, 247, 0.15);
        color: #d8b4fe;
        border: 1px solid rgba(168, 85, 247, 0.35);
    }

    .btn-open-manajer:hover {
        background: rgba(168, 85, 247, 0.3);
        color: #ffffff;
        transform: translateX(3px);
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: white;
        font-weight: 700;
        font-size: 15px;
        letter-spacing: -0.02em;
    }

    .brand-logo {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #a855f7 0%, #06b6d4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(168, 85, 247, 0.4);
    }

    .brand-logo svg {
        width: 18px;
        height: 18px;
        color: white;
    }

    .nav-tags {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tag-pill {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border);
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .tag-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent-emerald);
        box-shadow: 0 0 8px var(--accent-emerald);
    }

    /* Hero Section */
    .hero {
        text-align: center;
        padding: 40px 0 60px 0;
        max-width: 860px;
        margin: 0 auto;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        background: rgba(168, 85, 247, 0.1);
        border: 1px solid rgba(168, 85, 247, 0.3);
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
        color: #d8b4fe;
        margin-bottom: 24px;
        box-shadow: 0 0 25px -5px rgba(168, 85, 247, 0.3);
        animation: pulse-glow 3s infinite;
    }

    @keyframes pulse-glow {

        0%,
        100% {
            box-shadow: 0 0 25px -5px rgba(168, 85, 247, 0.3);
        }

        50% {
            box-shadow: 0 0 35px 2px rgba(168, 85, 247, 0.5);
        }
    }

    .hero h1 {
        font-size: 56px;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1.1;
        margin-bottom: 20px;
        background: linear-gradient(180deg, #ffffff 40%, rgba(255, 255, 255, 0.6) 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero h1 .gradient-text {
        background: linear-gradient(135deg, #c084fc 0%, #38bdf8 50%, #f472b6 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero p {
        font-size: 18px;
        color: var(--text-muted);
        max-width: 680px;
        margin: 0 auto 36px auto;
        font-weight: 400;
    }

    /* Hero Quick Bento Stats */
    .stats-bento {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 50px;
    }

    @media (max-width: 860px) {
        .stats-bento {
            grid-template-columns: repeat(2, 1fr);
        }

        .hero h1 {
            font-size: 38px;
        }
    }

    .stat-card {
        background: var(--surface-glass);
        backdrop-filter: blur(16px);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 20px;
        text-align: left;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--border-hover);
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.5);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        color: var(--accent-purple);
    }

    .stat-number {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.03em;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* Interactive Tabs Header */
    .tabs-container {
        display: flex;
        justify-content: center;
        margin-bottom: 36px;
    }

    .tab-switcher {
        background: rgba(18, 18, 24, 0.8);
        border: 1px solid var(--border);
        padding: 5px;
        border-radius: 9999px;
        display: flex;
        gap: 6px;
        backdrop-filter: blur(16px);
    }

    .tab-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding: 9px 22px;
        border-radius: 9999px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-btn:hover {
        color: var(--text);
    }

    .tab-btn.active {
        background: linear-gradient(135deg, rgba(168, 85, 247, 0.25), rgba(6, 182, 212, 0.25));
        border: 1px solid rgba(168, 85, 247, 0.4);
        color: #ffffff;
        box-shadow: 0 4px 20px -2px rgba(168, 85, 247, 0.3);
    }

    .tab-btn.active .tab-badge {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    .tab-badge {
        background: rgba(255, 255, 255, 0.08);
        padding: 2px 7px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
    }

    /* Main Content Grid */
    .tab-pane {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .tab-pane.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bento-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 40px;
    }

    @media (max-width: 960px) {
        .bento-grid {
            grid-template-columns: 1fr;
        }
    }

    .motion-card {
        background: var(--surface-glass);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .motion-card:hover {
        border-color: var(--border-hover);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7);
    }

    .card-header-styled {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 18px;
        border-bottom: 1px solid var(--border);
    }

    .card-title-group h2 {
        font-size: 20px;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }

    .card-title-group p {
        font-size: 13px;
        color: var(--text-muted);
    }

    .badge-type {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-parent {
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #93c5fd;
    }

    .badge-child {
        background: rgba(168, 85, 247, 0.15);
        border: 1px solid rgba(168, 85, 247, 0.3);
        color: #d8b4fe;
    }

    /* Sub-sections & Tables */
    .section-tag {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-sub);
        margin: 24px 0 12px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-tag::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .section-tag:first-of-type {
        margin-top: 0;
    }

    .motion-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
        font-size: 13.5px;
        margin-bottom: 12px;
    }

    .motion-table tr {
        background: rgba(255, 255, 255, 0.02);
        transition: all 0.2s ease;
        border-radius: 10px;
    }

    .motion-table tr:hover {
        background: rgba(255, 255, 255, 0.05);
        transform: translateX(3px);
    }

    .motion-table td {
        padding: 10px 14px;
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .motion-table td:first-child {
        border-left: 1px solid rgba(255, 255, 255, 0.03);
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .motion-table td:last-child {
        border-right: 1px solid rgba(255, 255, 255, 0.03);
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        text-align: right;
    }

    .fn-name {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12.5px;
        color: #e2e8f0;
        font-weight: 500;
    }

    .fn-name .fn-accent {
        color: #a855f7;
    }

    .fn-badge {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 6px;
    }

    .fn-badge.vis-private {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
    }

    .fn-badge.vis-public {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
    }

    .fn-badge.vis-override {
        background: rgba(245, 158, 11, 0.15);
        color: #fcd34d;
    }

    .val-result {
        font-weight: 600;
        color: #ffffff;
        font-family: 'JetBrains Mono', monospace;
        font-size: 13px;
    }

    .val-highlight {
        color: #38bdf8;
        font-weight: 700;
    }

    .val-thp {
        color: #34d399;
        font-weight: 800;
        font-size: 14px;
    }

    /* Slip Gaji Terminal Showcase */
    .terminal-box {
        background: #000000;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        margin-top: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
    }

    .terminal-header {
        background: rgba(255, 255, 255, 0.03);
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .terminal-dots {
        display: flex;
        gap: 6px;
    }

    .dot-circle {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .dot-red {
        background: #ef4444;
    }

    .dot-yellow {
        background: #f59e0b;
    }

    .dot-green {
        background: #10b981;
    }

    .terminal-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--text-sub);
    }

    .copy-btn {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid var(--border);
        color: var(--text-muted);
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 11px;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .copy-btn:hover {
        color: white;
        background: rgba(255, 255, 255, 0.12);
    }

    .terminal-body {
        padding: 18px 20px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12.5px;
        color: #d1d5db;
        line-height: 1.55;
        white-space: pre;
        overflow-x: auto;
    }

    /* Setter interactive pill cards */
    .interactive-toggle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border);
        padding: 4px;
        border-radius: 12px;
        margin-bottom: 16px;
    }

    .toggle-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .toggle-btn.active {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
    }

    /* Budget progress bar for Manager */
    .budget-bar-wrapper {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 9999px;
        height: 10px;
        overflow: hidden;
        margin: 12px 0 6px 0;
    }

    .budget-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #a855f7, #06b6d4);
        border-radius: 9999px;
        transition: width 1s ease;
    }

    /* Status badge */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-status.success {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge-status.rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Architecture Visual Map */
    .arch-card {
        background: var(--surface-glass);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 40px;
    }

    .arch-flow {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .arch-node {
        background: #000000;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px 24px;
        min-width: 280px;
        transition: all 0.3s;
    }

    .arch-node:hover {
        border-color: rgba(168, 85, 247, 0.5);
        box-shadow: 0 0 30px rgba(168, 85, 247, 0.2);
    }

    .arch-arrow {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        color: var(--accent-purple);
    }

    footer {
        border-top: 1px solid var(--border);
        padding: 40px 0;
        text-align: center;
        color: var(--text-sub);
        font-size: 13px;
    }

    footer a {
        color: var(--accent-purple);
        text-decoration: none;
    }

    .nav-toggle {
        display: none;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: rgba(255, 255, 255, 0.06);
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 5px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .nav-toggle span {
        display: block;
        width: 18px;
        height: 2px;
        border-radius: 999px;
        background: #ffffff;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .nav-toggle.open span:nth-child(1) {
        transform: translateY(7px) rotate(45deg);
    }

    .nav-toggle.open span:nth-child(2) {
        opacity: 0;
    }

    .nav-toggle.open span:nth-child(3) {
        transform: translateY(-7px) rotate(-45deg);
    }

    @media (max-width: 768px) {

        html,
        body {
            overflow-x: hidden;
            max-width: 100%;
        }

        .container {
            padding: 0 14px;
        }

        nav {
            top: 8px;
            margin-bottom: 20px;
        }

        .nav-inner {
            flex-wrap: wrap;
            border-radius: 20px;
            padding: 10px 12px;
            gap: 8px;
        }

        .brand {
            font-size: 13px;
            gap: 8px;
            min-width: 0;
        }

        .brand span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-toggle {
            display: inline-flex;
            margin-left: auto;
        }

        .nav-tags {
            display: none;
        }

        .nav-links-menu {
            display: none;
            flex-direction: column;
            align-items: stretch;
            width: 100%;
            gap: 6px;
            order: 3;
            padding-top: 8px;
            border-top: 1px solid var(--border);
        }

        .nav-links-menu.open {
            display: flex;
        }

        .nav-menu-item {
            width: 100%;
            text-align: center;
            padding: 10px 12px;
        }

        .hero {
            padding: 12px 0 24px;
        }

        .hero-badge {
            font-size: 12px;
            padding: 5px 12px;
            margin-bottom: 14px;
        }

        .hero h1 {
            font-size: 28px;
            margin-bottom: 12px;
        }

        .hero p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .stats-bento {
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 28px;
        }

        .stat-card {
            padding: 16px;
        }

        .tabs-container {
            margin-bottom: 20px;
        }

        .tab-switcher {
            flex-wrap: wrap;
            justify-content: center;
            border-radius: 18px;
            width: 100%;
        }

        .tab-btn {
            padding: 8px 12px;
            font-size: 13px;
        }

        .bento-grid {
            gap: 16px;
            margin-bottom: 24px;
        }

        .motion-card,
        .arch-card {
            padding: 16px;
            border-radius: 18px;
        }

        .card-header-styled {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .card-header-styled>div:last-child {
            flex-wrap: wrap;
        }

        .arch-node {
            min-width: 0;
            width: 100%;
            padding: 16px;
        }

        .motion-table,
        .motion-table tbody,
        .motion-table tr,
        .motion-table td {
            display: block;
            width: 100%;
        }

        .motion-table {
            font-size: 12px;
        }

        .motion-table tr {
            margin-bottom: 8px;
            border-radius: 10px;
            padding: 8px 10px;
        }

        .motion-table tr:hover {
            transform: none;
        }

        .motion-table td {
            padding: 2px 0;
            border: none;
            white-space: normal;
            word-break: break-word;
        }

        .motion-table td:first-child,
        .motion-table td:last-child {
            border-radius: 0;
            text-align: left;
        }

        .fn-name {
            font-size: 11px;
        }

        footer {
            padding: 24px 0;
        }
    }
    </style>
</head>

<body>
    <div class="bg-grid"></div>
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="container">
        <!-- MOTION NAVBAR -->
        <nav>
            <div class="nav-inner">
                <a href="index.php" class="brand">
                    <div class="brand-logo">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                    </div>
                    <span>UTS PBO — Motion OOP</span>
                </a>

                <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Buka menu navigasi"
                    aria-expanded="false" aria-controls="nav-links-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <!-- MENU PINDAH HALAMAN -->
                <div class="nav-links-menu" id="nav-links-menu">
                    <a href="index.php" class="nav-menu-item active">Beranda</a>
                    <a href="Karyawan.php" class="nav-menu-item" style="color: #93c5fd;">Class Karyawan →</a>
                    <a href="Manajer.php" class="nav-menu-item" style="color: #d8b4fe;">Class Manajer →</a>
                </div>

                <div class="nav-tags">
                    <div class="tag-pill">
                        <span class="dot"></span>
                        <span>PHP 8.0+ Ready</span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- HERO SECTION -->
        <section class="hero">
            <div class="hero-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path
                        d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                </svg>
                <span>Enterprise OOP Architecture</span>
            </div>
            <h1>Clean Encapsulation.<br><span class="gradient-text">Fluent Inheritance.</span></h1>
            <p>Sistem Penggajian & Struktur Manajerial Berbasis Pemrograman Berorientasi Objek PHP dengan Enkapsulasi
                Ketat, Method Bisnis, dan Ekstensi Polimorfik.</p>

            <!-- STATS BENTO -->
            <div class="stats-bento">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect width="7" height="9" x="3" y="3" rx="1" />
                            <rect width="7" height="5" x="14" y="3" rx="1" />
                            <rect width="7" height="9" x="14" y="12" rx="1" />
                            <rect width="7" height="5" x="3" y="16" rx="1" />
                        </svg>
                    </div>
                    <div class="stat-number">2</div>
                    <div class="stat-label">Classes (Parent & Child)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--accent-cyan);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <div class="stat-number">10</div>
                    <div class="stat-label">Encapsulated Properties</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--accent-emerald);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="m16 18 2-2-2-2" />
                            <path d="m8 6-2 2 2 2" />
                            <line x1="12" x2="12" y1="2" y2="22" />
                        </svg>
                    </div>
                    <div class="stat-number">8+</div>
                    <div class="stat-label">Methods & Overrides</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: var(--accent-pink);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Tested & Verified</div>
                </div>
            </div>
        </section>

        <!-- INTERACTIVE TAB SWITCHER -->
        <div class="tabs-container">
            <div class="tab-switcher">
                <button class="tab-btn active" onclick="switchTab('tab-all')">
                    <span>Semua Objek</span>
                    <span class="tab-badge">2</span>
                </button>
                <button class="tab-btn" onclick="switchTab('tab-karyawan')">
                    <span>Karyawan</span>
                    <span class="tab-badge">Base</span>
                </button>
                <button class="tab-btn" onclick="switchTab('tab-manajer')">
                    <span>Manajer</span>
                    <span class="tab-badge">Extends</span>
                </button>
                <button class="tab-btn" onclick="switchTab('tab-arch')">
                    <span>Arsitektur OOP</span>
                </button>
            </div>
        </div>

        <!-- TAB CONTENT: ALL OR FILTERED -->
        <div id="content-container">

            <!-- BENTO ROW: KARYAWAN & MANAJER -->
            <div class="bento-grid" id="main-grid">

                <!-- CARD 1: KARYAWAN -->
                <div class="motion-card" id="card-karyawan">
                    <div class="card-header-styled">
                        <div class="card-title-group">
                            <h2>
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="color: #60a5fa;">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <a href="Karyawan.php" style="color: inherit; text-decoration: none;">Class Karyawan</a>
                            </h2>
                            <p>Entitas Dasar dengan 5 Atribut Terenkapsulasi (Private)</p>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="Karyawan.php" class="btn-open-page btn-open-karyawan">
                                <span>Buka Karyawan.php</span>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                            <span class="badge-type badge-parent">Base Class</span>
                        </div>
                    </div>

                    <!-- Toggle Data Awal vs Setelah Setter -->
                    <div class="interactive-toggle">
                        <button class="toggle-btn active" onclick="toggleKaryawan('awal', this)">Nilai Awal</button>
                        <button class="toggle-btn" onclick="toggleKaryawan('rev', this)">Setelah Setter
                            (Revisi)</button>
                    </div>

                    <!-- GETTER AWAL -->
                    <div id="karyawan-view-awal">
                        <div class="section-tag">A. 5 Atribut via Method Getter</div>
                        <table class="motion-table">
                            <tr>
                                <td><span class="fn-name">getNik()<span class="fn-badge vis-private">priv</span></span>
                                </td>
                                <td class="val-result val-highlight">
                                    <?= htmlspecialchars($karyawanGettersAwal['getNik()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">getNama()<span class="fn-badge vis-private">priv</span></span>
                                </td>
                                <td class="val-result"><?= htmlspecialchars($karyawanGettersAwal['getNama()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">getJabatan()<span
                                            class="fn-badge vis-private">priv</span></span></td>
                                <td class="val-result"><?= htmlspecialchars($karyawanGettersAwal['getJabatan()']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">getGajiPokok()<span
                                            class="fn-badge vis-private">priv</span></span></td>
                                <td class="val-result"><?= $formatRp($karyawanGettersAwal['getGajiPokok()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">getJamLembur()<span
                                            class="fn-badge vis-private">priv</span></span></td>
                                <td class="val-result"><?= $karyawanGettersAwal['getJamLembur()'] ?> Jam</td>
                            </tr>
                        </table>

                        <div class="section-tag">B. 4 Method Bisnis Karyawan</div>
                        <table class="motion-table">
                            <tr>
                                <td><span class="fn-name"><span class="fn-accent">hitungTunjanganJabatan</span>()</span>
                                </td>
                                <td class="val-result"><?= $formatRp($karyawanBisnis['hitungTunjanganJabatan()']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="fn-name"><span class="fn-accent">hitungUangLembur</span>()</span></td>
                                <td class="val-result"><?= $formatRp($karyawanBisnis['hitungUangLembur()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name"><span class="fn-accent">hitungPotongan</span>()</span></td>
                                <td class="val-result" style="color: #f87171;">-
                                    <?= $formatRp($karyawanBisnis['hitungPotongan()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name" style="color: #34d399;"><span
                                            class="fn-accent">hitungGajiBersih</span>()</span></td>
                                <td class="val-result val-thp"><?= $formatRp($karyawanBisnis['hitungGajiBersih()']) ?>
                                </td>
                            </tr>
                        </table>

                        <div class="section-tag">C. Output cetakSlipGaji()</div>
                        <div class="terminal-box">
                            <div class="terminal-header">
                                <div class="terminal-dots">
                                    <span class="dot-circle dot-red"></span>
                                    <span class="dot-circle dot-yellow"></span>
                                    <span class="dot-circle dot-green"></span>
                                </div>
                                <span class="terminal-title">slip_gaji_karyawan_v1.txt</span>
                                <button class="copy-btn" onclick="copyCode('karyawan-slip-awal')">Salin</button>
                            </div>
                            <div class="terminal-body" id="karyawan-slip-awal">
                                <?= htmlspecialchars($slipGajiKaryawanAwal) ?></div>
                        </div>
                    </div>

                    <!-- GETTER SETELAH SETTER -->
                    <div id="karyawan-view-rev" style="display: none;">
                        <div class="section-tag">A. Pengujian Seluruh Setter (Dipromosikan)</div>
                        <table class="motion-table">
                            <tr>
                                <td><span class="fn-name">setNik("KRY-202401-REV")</span></td>
                                <td class="val-result val-highlight">
                                    <?= htmlspecialchars($karyawanGettersSetelahSetter['getNik()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">setNama("Ahmad Fauzi, S.Kom")</span></td>
                                <td class="val-result">
                                    <?= htmlspecialchars($karyawanGettersSetelahSetter['getNama()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">setJabatan("Supervisor")</span></td>
                                <td class="val-result" style="color: #c084fc;">
                                    <?= htmlspecialchars($karyawanGettersSetelahSetter['getJabatan()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">setGajiPokok(6500000)</span></td>
                                <td class="val-result"><?= $formatRp($karyawanGettersSetelahSetter['getGajiPokok()']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">setJamLembur(12)</span></td>
                                <td class="val-result"><?= $karyawanGettersSetelahSetter['getJamLembur()'] ?> Jam</td>
                            </tr>
                        </table>

                        <div class="section-tag">B. Output Slip Gaji Pasca-Setter</div>
                        <div class="terminal-box">
                            <div class="terminal-header">
                                <div class="terminal-dots">
                                    <span class="dot-circle dot-red"></span>
                                    <span class="dot-circle dot-yellow"></span>
                                    <span class="dot-circle dot-green"></span>
                                </div>
                                <span class="terminal-title">slip_gaji_karyawan_promosi.txt</span>
                                <button class="copy-btn" onclick="copyCode('karyawan-slip-rev')">Salin</button>
                            </div>
                            <div class="terminal-body" id="karyawan-slip-rev">
                                <?= htmlspecialchars($slipGajiKaryawanSetelahSetter) ?></div>
                        </div>
                    </div>

                </div>

                <!-- CARD 2: MANAJER -->
                <div class="motion-card" id="card-manajer">
                    <div class="card-header-styled">
                        <div class="card-title-group">
                            <h2>
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" style="color: #c084fc;">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                <a href="Manajer.php" style="color: inherit; text-decoration: none;">Class Manajer</a>
                            </h2>
                            <p>Child Class (extends Karyawan) + 5 Atribut Spesifik & Method Baru</p>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="Manajer.php" class="btn-open-page btn-open-manajer">
                                <span>Buka Manajer.php</span>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                            <span class="badge-type badge-child">Extends Karyawan</span>
                        </div>
                    </div>

                    <!-- Toggle Data Manajer -->
                    <div class="interactive-toggle">
                        <button class="toggle-btn active" onclick="toggleManajer('awal', this)">Data Eksekutif</button>
                        <button class="toggle-btn" onclick="toggleManajer('rev', this)">Revisi Anggaran & Tim</button>
                    </div>

                    <!-- VIEW AWAL MANAJER -->
                    <div id="manajer-view-awal">
                        <div class="section-tag">A. Atribut Warisan & Spesifik Baru</div>
                        <table class="motion-table">
                            <tr>
                                <td><span class="fn-name">getDepartemen()<span
                                            class="fn-badge vis-private">spesifik</span></span></td>
                                <td class="val-result val-highlight">
                                    <?= htmlspecialchars($manajerGetters['getDepartemen()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">getJumlahBawahan()<span
                                            class="fn-badge vis-private">spesifik</span></span></td>
                                <td class="val-result"><?= $manajerGetters['getJumlahBawahan()'] ?> Anggota Tim</td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">getTunjanganManajerial()<span
                                            class="fn-badge vis-private">spesifik</span></span></td>
                                <td class="val-result"><?= $formatRp($manajerGetters['getTunjanganManajerial()']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">getSkorKpi()<span
                                            class="fn-badge vis-private">spesifik</span></span></td>
                                <td class="val-result" style="color: #38bdf8;">
                                    <?= number_format($manajerGetters['getSkorKpi()'], 1) ?> / 100</td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">getAnggaranDepartemen()<span
                                            class="fn-badge vis-private">spesifik</span></span></td>
                                <td class="val-result"><?= $formatRp($manajerGetters['getAnggaranDepartemen()']) ?></td>
                            </tr>
                        </table>

                        <div class="section-tag">B. 4 Method Baru & Override Manajerial</div>
                        <table class="motion-table">
                            <tr>
                                <td><span class="fn-name"><span
                                            class="fn-accent">hitungTunjanganKepemimpinan</span>()</span></td>
                                <td class="val-result"><?= $formatRp($tunjanganKepemimpinan) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name"><span class="fn-accent">hitungBonusKpi</span>()</span></td>
                                <td class="val-result" style="color: #f472b6;"><?= $formatRp($bonusKpi) ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fn-name"><span class="fn-accent">alokasikanAnggaran</span>(Rp 25 Jt,
                                        ERP)</span>
                                </td>
                                <td>
                                    <span class="badge-status success">✓ BERHASIL</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fn-name"><span class="fn-accent">alokasikanAnggaran</span>(Rp 100 Jt,
                                        Overdraft)</span>
                                </td>
                                <td>
                                    <span class="badge-status rejected">✕ DITOLAK (Overdraft)</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fn-name" style="color: #34d399;"><span
                                            class="fn-accent">hitungGajiBersih</span>()</span>
                                    <span class="fn-badge vis-override">override</span>
                                </td>
                                <td class="val-result val-thp"><?= $formatRp($gajiBersihManajer) ?></td>
                            </tr>
                        </table>

                        <div class="section-tag">C. Sisa Alokasi Anggaran Divisi</div>
                        <div class="budget-bar-wrapper">
                            <div class="budget-bar-fill"
                                style="width: <?= ($anggaranSetelahAlokasi1 / $anggaranSebelumAlokasi) * 100 ?>%;">
                            </div>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; font-size: 11.5px; color: var(--text-muted); margin-bottom: 12px;">
                            <span>Sisa Saldo: <b
                                    style="color: white;"><?= $formatRp($anggaranSetelahAlokasi1) ?></b></span>
                            <span>Pagu Awal: <?= $formatRp($anggaranSebelumAlokasi) ?></span>
                        </div>

                        <div class="section-tag">D. Output cetakSlipGajiManajer()</div>
                        <div class="terminal-box">
                            <div class="terminal-header">
                                <div class="terminal-dots">
                                    <span class="dot-circle dot-red"></span>
                                    <span class="dot-circle dot-yellow"></span>
                                    <span class="dot-circle dot-green"></span>
                                </div>
                                <span class="terminal-title">slip_eksekutif_manajer.txt</span>
                                <button class="copy-btn" onclick="copyCode('manajer-slip-awal')">Salin</button>
                            </div>
                            <div class="terminal-body" id="manajer-slip-awal">
                                <?= htmlspecialchars($slipGajiManajerAwal) ?></div>
                        </div>
                    </div>

                    <!-- VIEW SETTER MANAJER -->
                    <div id="manajer-view-rev" style="display: none;">
                        <div class="section-tag">A. Pengujian Seluruh Setter Baru Manajer</div>
                        <table class="motion-table">
                            <tr>
                                <td><span class="fn-name">setDepartemen(...)</span></td>
                                <td class="val-result val-highlight">
                                    <?= htmlspecialchars($manajerGettersSetelahSetter['getDepartemen()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">setJumlahBawahan(10)</span></td>
                                <td class="val-result"><?= $manajerGettersSetelahSetter['getJumlahBawahan()'] ?> Orang
                                </td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">setTunjanganManajerial(5000000)</span></td>
                                <td class="val-result">
                                    <?= $formatRp($manajerGettersSetelahSetter['getTunjanganManajerial()']) ?></td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">setSkorKpi(98.5)</span></td>
                                <td class="val-result" style="color: #38bdf8;">
                                    <?= number_format($manajerGettersSetelahSetter['getSkorKpi()'], 1) ?> / 100</td>
                            </tr>
                            <tr>
                                <td><span class="fn-name">setAnggaranDepartemen(120000000)</span></td>
                                <td class="val-result">
                                    <?= $formatRp($manajerGettersSetelahSetter['getAnggaranDepartemen()']) ?></td>
                            </tr>
                        </table>

                        <div class="section-tag">B. Output Slip Eksekutif Pasca-Setter</div>
                        <div class="terminal-box">
                            <div class="terminal-header">
                                <div class="terminal-dots">
                                    <span class="dot-circle dot-red"></span>
                                    <span class="dot-circle dot-yellow"></span>
                                    <span class="dot-circle dot-green"></span>
                                </div>
                                <span class="terminal-title">slip_manajer_setelah_revisi.txt</span>
                                <button class="copy-btn" onclick="copyCode('manajer-slip-rev')">Salin</button>
                            </div>
                            <div class="terminal-body" id="manajer-slip-rev">
                                <?= htmlspecialchars($slipGajiManajerSetelahSetter) ?></div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- ARSITEKTUR OOP DIAGRAM BENTO -->
            <div class="arch-card" id="arch-panel">
                <div class="card-title-group" style="margin-bottom: 20px;">
                    <h2>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="color: #10b981;">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        Relasi & Arsitektur Kelas OOP (PHP 8.0)
                    </h2>
                    <p>Visualisasi struktur enkapsulasi, pewarisan (inheritance), dan method polymorphism</p>
                </div>

                <div class="arch-flow">
                    <!-- Base Class Node -->
                    <div class="arch-node">
                        <div style="font-size: 11px; color: #3b82f6; font-weight: 700; text-transform: uppercase;">[Base
                            Class]</div>
                        <h3 style="font-size: 18px; color: white; margin: 4px 0 10px 0;">Karyawan</h3>
                        <div style="font-size: 12px; color: var(--text-muted); line-height: 1.6;">
                            <div>🔒 <b>5 Atribut:</b> nik, nama, jabatan, gajiPokok, jamLembur</div>
                            <div style="margin-top: 6px;">⚡ <b>4 Method Bisnis:</b></div>
                            <div style="color: #cbd5e1; font-family: monospace; font-size: 11px;">
                                • hitungTunjanganJabatan()<br>
                                • hitungUangLembur()<br>
                                • hitungPotongan()<br>
                                • hitungGajiBersih()
                            </div>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="arch-arrow">
                        <span
                            style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">extends</span>
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>

                    <!-- Child Class Node -->
                    <div class="arch-node">
                        <div style="font-size: 11px; color: #a855f7; font-weight: 700; text-transform: uppercase;">
                            [Child Class]</div>
                        <h3 style="font-size: 18px; color: white; margin: 4px 0 10px 0;">Manajer</h3>
                        <div style="font-size: 12px; color: var(--text-muted); line-height: 1.6;">
                            <div>🔒 <b>5 Atribut Baru:</b> departemen, bawahan, tunjManajerial, skorKpi, anggaran</div>
                            <div style="margin-top: 6px;">⚡ <b>4 Method Baru & Override:</b></div>
                            <div style="color: #cbd5e1; font-family: monospace; font-size: 11px;">
                                • parent::__construct()<br>
                                • hitungBonusKpi()<br>
                                • hitungTunjanganKepemimpinan()<br>
                                • alokasikanAnggaran()<br>
                                • hitungGajiBersih() [Override]
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <footer>
            <p>UTS Pemrograman Berorientasi Objek — Didesain dengan estetika modern <a href="https://motion.dev"
                    target="_blank" rel="noopener">UI/UX Design</a>.</p>
            <p style="margin-top: 6px; font-size: 12px; color: #52525b;">Dijalankan pada XAMPP Localhost • PHP 8.0+ CLI
                & Browser Compatible</p>
        </footer>
    </div>

    <!-- JAVASCRIPT FOR INTERACTIVE MOTION -->
    <script>
    function toggleNav() {
        const menu = document.getElementById('nav-links-menu');
        const button = document.getElementById('nav-toggle');
        const isOpen = menu.classList.toggle('open');
        button.classList.toggle('open', isOpen);
        button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        button.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    }

    document.getElementById('nav-toggle').addEventListener('click', toggleNav);

    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');

        const cardKaryawan = document.getElementById('card-karyawan');
        const cardManajer = document.getElementById('card-manajer');
        const archPanel = document.getElementById('arch-panel');
        const grid = document.getElementById('main-grid');

        if (tabId === 'tab-all') {
            grid.style.display = 'grid';
            grid.style.gridTemplateColumns = window.innerWidth > 960 ? '1fr 1fr' : '1fr';
            cardKaryawan.style.display = 'block';
            cardManajer.style.display = 'block';
            archPanel.style.display = 'block';
        } else if (tabId === 'tab-karyawan') {
            grid.style.display = 'block';
            cardKaryawan.style.display = 'block';
            cardManajer.style.display = 'none';
            archPanel.style.display = 'none';
        } else if (tabId === 'tab-manajer') {
            grid.style.display = 'block';
            cardKaryawan.style.display = 'none';
            cardManajer.style.display = 'block';
            archPanel.style.display = 'none';
        } else if (tabId === 'tab-arch') {
            grid.style.display = 'none';
            archPanel.style.display = 'block';
        }
    }

    function toggleKaryawan(view, btn) {
        btn.parentElement.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if (view === 'awal') {
            document.getElementById('karyawan-view-awal').style.display = 'block';
            document.getElementById('karyawan-view-rev').style.display = 'none';
        } else {
            document.getElementById('karyawan-view-awal').style.display = 'none';
            document.getElementById('karyawan-view-rev').style.display = 'block';
        }
    }

    function toggleManajer(view, btn) {
        btn.parentElement.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if (view === 'awal') {
            document.getElementById('manajer-view-awal').style.display = 'block';
            document.getElementById('manajer-view-rev').style.display = 'none';
        } else {
            document.getElementById('manajer-view-awal').style.display = 'none';
            document.getElementById('manajer-view-rev').style.display = 'block';
        }
    }

    function copyCode(elementId) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            const btn = event.currentTarget;
            const orig = btn.innerText;
            btn.innerText = 'Tersalin!';
            btn.style.color = '#34d399';
            setTimeout(() => {
                btn.innerText = orig;
                btn.style.color = '';
            }, 1800);
        });
    }
    </script>
</body>

</html>