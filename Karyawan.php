<?php

/**
 * Class Karyawan
 * 
 * Merepresentasikan entitas Karyawan dengan prinsip Enkapsulasi OOP.
 * Memiliki 5 atribut terenkapsulasi, Getter & Setter lengkap,
 * serta 4 Method Bisnis untuk perhitungan gaji dan tunjangan.
 */
class Karyawan
{
    // ==========================================
    // 5 ATRIBUT TERENKAPSULASI (PRIVATE)
    // ==========================================
    private string $nik;
    private string $nama;
    private string $jabatan;
    private float $gajiPokok;
    private int $jamLembur;

    /**
     * Constructor untuk inisialisasi data karyawan
     */
    public function __construct(
        string $nik,
        string $nama,
        string $jabatan,
        float $gajiPokok,
        int $jamLembur = 0
    ) {
        $this->nik = $nik;
        $this->nama = $nama;
        $this->jabatan = $jabatan;
        $this->setGajiPokok($gajiPokok);
        $this->setJamLembur($jamLembur);
    }

    // ==========================================
    // GETTER DAN SETTER (5 ATRIBUT)
    // ==========================================

    // 1. NIK
    public function getNik(): string
    {
        return $this->nik;
    }

    public function setNik(string $nik): void
    {
        $this->nik = trim($nik);
    }

    // 2. Nama
    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $this->nama = trim($nama);
    }

    // 3. Jabatan
    public function getJabatan(): string
    {
        return $this->jabatan;
    }

    public function setJabatan(string $jabatan): void
    {
        $this->jabatan = trim($jabatan);
    }

    // 4. Gaji Pokok
    public function getGajiPokok(): float
    {
        return $this->gajiPokok;
    }

    public function setGajiPokok(float $gajiPokok): void
    {
        if ($gajiPokok < 0) {
            throw new InvalidArgumentException("Gaji pokok tidak boleh bernilai negatif.");
        }
        $this->gajiPokok = $gajiPokok;
    }

    // 5. Jam Lembur
    public function getJamLembur(): int
    {
        return $this->jamLembur;
    }

    public function setJamLembur(int $jamLembur): void
    {
        if ($jamLembur < 0) {
            throw new InvalidArgumentException("Jam lembur tidak boleh bernilai negatif.");
        }
        $this->jamLembur = $jamLembur;
    }

    // ==========================================
    // 4 METHOD BISNIS
    // ==========================================

    /**
     * Method Bisnis 1: Menghitung besaran tunjangan berdasarkan jabatan karyawan.
     */
    public function hitungTunjanganJabatan(): float
    {
        $jabatanClean = strtolower(trim($this->jabatan));

        return match ($jabatanClean) {
            'manager'    => 2500000.0,
            'supervisor' => 1500000.0,
            'staff'      => 500000.0,
            default      => 200000.0,
        };
    }

    /**
     * Method Bisnis 2: Menghitung uang lembur berdasarkan total jam lembur.
     */
    public function hitungUangLembur(float $tarifPerJam = 50000.0): float
    {
        if ($tarifPerJam < 0) {
            throw new InvalidArgumentException("Tarif lembur tidak boleh bernilai negatif.");
        }
        return $this->jamLembur * $tarifPerJam;
    }

    /**
     * Method Bisnis 3: Menghitung total potongan penghasilan (Pajak PPh + BPJS).
     */
    public function hitungPotongan(float $persenPajak = 0.05, float $potonganBpjs = 100000.0): float
    {
        $penghasilanKotor = $this->gajiPokok + $this->hitungTunjanganJabatan() + $this->hitungUangLembur();
        $pajak = $penghasilanKotor * $persenPajak;

        return $pajak + $potonganBpjs;
    }

    /**
     * Method Bisnis 4: Menghitung gaji bersih (Take Home Pay).
     */
    public function hitungGajiBersih(): float
    {
        $penghasilanKotor = $this->gajiPokok + $this->hitungTunjanganJabatan() + $this->hitungUangLembur();
        $totalPotongan = $this->hitungPotongan();

        return $penghasilanKotor - $totalPotongan;
    }

    // ==========================================
    // METHOD TAMBAHAN: CETAK SLIP GAJI
    // ==========================================
    public function cetakSlipGaji(): string
    {
        $tunjangan     = $this->hitungTunjanganJabatan();
        $uangLembur    = $this->hitungUangLembur();
        $potongan      = $this->hitungPotongan();
        $gajiKotor     = $this->gajiPokok + $tunjangan + $uangLembur;
        $gajiBersih    = $this->hitungGajiBersih();

        $formatRupiah = fn(float $nilai) => "Rp " . number_format($nilai, 0, ',', '.');

        $output = "====================================================\n";
        $output .= "                 SLIP GAJI KARYAWAN                 \n";
        $output .= "====================================================\n";
        $output .= sprintf("NIK             : %s\n", $this->nik);
        $output .= sprintf("Nama Karyawan   : %s\n", $this->nama);
        $output .= sprintf("Jabatan         : %s\n", $this->jabatan);
        $output .= sprintf("Jam Lembur      : %d Jam\n", $this->jamLembur);
        $output .= "----------------------------------------------------\n";
        $output .= sprintf("Gaji Pokok      : %s\n", $formatRupiah($this->gajiPokok));
        $output .= sprintf("Tunj. Jabatan   : %s\n", $formatRupiah($tunjangan));
        $output .= sprintf("Uang Lembur     : %s\n", $formatRupiah($uangLembur));
        $output .= "----------------------------------------------------\n";
        $output .= sprintf("Penghasilan Bruto: %s\n", $formatRupiah($gajiKotor));
        $output .= sprintf("Total Potongan  : - %s\n", $formatRupiah($potongan));
        $output .= "====================================================\n";
        $output .= sprintf("GAJI BERSIH (THP): %s\n", $formatRupiah($gajiBersih));
        $output .= "====================================================\n";

        return $output;
    }
}

// ==========================================
// HALAMAN KHUSUS KARYAWAN (MOTION.DEV STYLE)
// ==========================================
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    // Mode CLI Terminal
    if (php_sapi_name() === 'cli') {
        echo "=== DEMO PENGUJIAN CLASS KARYAWAN (OOP PHP) ===\n\n";
        $karyawan1 = new Karyawan("KRY-001", "Budi Santoso", "Staff", 5000000, 8);
        echo $karyawan1->cetakSlipGaji();
        exit(0);
    }

    // Instansiasi untuk Halaman Web
    $karyawan = new Karyawan("KRY-202401", "Ahmad Fauzi", "Staff", 5000000.0, 8);
    $formatRp = fn(float $v) => "Rp " . number_format($v, 0, ',', '.');
    ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Karyawan (Parent Class) — UTS PBO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --bg: #09090b;
        --surface: #121217;
        --surface-glass: rgba(18, 18, 24, 0.75);
        --border: rgba(255, 255, 255, 0.08);
        --border-hover: rgba(255, 255, 255, 0.18);
        --text: #f4f4f5;
        --text-muted: #a1a1aa;
        --accent-blue: #3b82f6;
        --accent-purple: #a855f7;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background-color: var(--bg);
        color: var(--text);
        font-family: 'Plus Jakarta Sans', sans-serif;
        min-height: 100vh;
        line-height: 1.6;
    }

    .bg-grid {
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
        z-index: 0;
    }

    .ambient-glow {
        position: fixed;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 650px;
        height: 650px;
        border-radius: 50%;
        background: radial-gradient(circle, #3b82f6, #8b5cf6);
        filter: blur(140px);
        opacity: 0.18;
        pointer-events: none;
        z-index: 0;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 24px;
        position: relative;
        z-index: 1;
    }

    nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--surface-glass);
        backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-radius: 9999px;
        padding: 10px 22px;
        margin-bottom: 40px;
    }

    .nav-link-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #93c5fd;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 9999px;
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.25);
        transition: all 0.2s;
    }

    .nav-link-back:hover {
        background: rgba(59, 130, 246, 0.2);
        transform: translateX(-3px);
    }

    .nav-nav-links {
        display: flex;
        gap: 12px;
    }

    .nav-btn-next {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #d8b4fe;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 9999px;
        background: rgba(168, 85, 247, 0.1);
        border: 1px solid rgba(168, 85, 247, 0.25);
        transition: all 0.2s;
    }

    .nav-btn-next:hover {
        background: rgba(168, 85, 247, 0.2);
        transform: translateX(3px);
    }

    .hero-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .pill-badge {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 9999px;
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #93c5fd;
        font-size: 12.5px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .hero-header h1 {
        font-size: 40px;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin-bottom: 8px;
    }

    .hero-header p {
        color: var(--text-muted);
        font-size: 16px;
    }

    .bento-card {
        background: var(--surface-glass);
        backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 28px;
        margin-bottom: 24px;
        transition: border-color 0.3s;
    }

    .bento-card:hover {
        border-color: var(--border-hover);
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
        font-size: 14px;
    }

    tr {
        background: rgba(255, 255, 255, 0.02);
    }

    td {
        padding: 10px 14px;
    }

    td:first-child {
        border-radius: 8px 0 0 8px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 13px;
    }

    td:last-child {
        border-radius: 0 8px 8px 0;
        text-align: right;
        font-weight: 600;
        font-family: 'JetBrains Mono', monospace;
    }

    .terminal {
        background: #000000;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 18px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12.5px;
        white-space: pre;
        overflow-x: auto;
        line-height: 1.5;
        color: #e2e8f0;
        max-width: 100%;
    }

    @media (max-width: 768px) {

        html,
        body {
            overflow-x: hidden;
            max-width: 100%;
        }

        .container {
            padding: 14px;
        }

        nav {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
            border-radius: 18px;
            padding: 12px;
            margin-bottom: 24px;
        }

        .nav-nav-links {
            width: 100%;
        }

        .nav-link-back,
        .nav-btn-next {
            width: 100%;
            justify-content: center;
            text-align: center;
        }

        .hero-header {
            margin-bottom: 22px;
        }

        .hero-header h1 {
            font-size: 28px;
        }

        .hero-header p {
            font-size: 14px;
        }

        .bento-card {
            padding: 16px;
        }

        table,
        tbody,
        tr,
        td {
            display: block;
            width: 100%;
        }

        table {
            font-size: 12.5px;
        }

        tr {
            margin-bottom: 8px;
            border-radius: 8px;
            padding: 8px 10px;
        }

        td {
            padding: 2px 0;
            white-space: normal;
            border-radius: 0;
        }

        td:last-child {
            text-align: left;
        }
    }
    </style>
</head>

<body>
    <div class="bg-grid"></div>
    <div class="ambient-glow"></div>

    <div class="container">
        <nav>
            <a href="index.php" class="nav-link-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Beranda (index.php)</span>
            </a>
            <div class="nav-nav-links">
                <a href="Manajer.php" class="nav-btn-next">
                    <span>Buka Halaman Manajer</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </nav>

        <div class="hero-header">
            <span class="pill-badge">Base / Parent Class</span>
            <h1>Class Karyawan</h1>
            <p>Implementasi Enkapsulasi 5 Atribut Private, Validasi Getter/Setter, dan 4 Method Bisnis.</p>
        </div>

        <!-- CARD 1: 5 ATRIBUT & GETTER -->
        <div class="bento-card">
            <div class="card-title">
                <span style="color: #60a5fa;">🔒</span> 5 Atribut Terenkapsulasi (Private) & Method Getter
            </div>
            <table>
                <tr>
                    <td>getNik() <span
                            style="font-size: 10px; background: rgba(239,68,68,0.2); color: #fca5a5; padding: 2px 6px; border-radius: 4px;">private
                            $nik</span></td>
                    <td style="color: #60a5fa;"><?= htmlspecialchars($karyawan->getNik()) ?></td>
                </tr>
                <tr>
                    <td>getNama() <span
                            style="font-size: 10px; background: rgba(239,68,68,0.2); color: #fca5a5; padding: 2px 6px; border-radius: 4px;">private
                            $nama</span></td>
                    <td><?= htmlspecialchars($karyawan->getNama()) ?></td>
                </tr>
                <tr>
                    <td>getJabatan() <span
                            style="font-size: 10px; background: rgba(239,68,68,0.2); color: #fca5a5; padding: 2px 6px; border-radius: 4px;">private
                            $jabatan</span></td>
                    <td><?= htmlspecialchars($karyawan->getJabatan()) ?></td>
                </tr>
                <tr>
                    <td>getGajiPokok() <span
                            style="font-size: 10px; background: rgba(239,68,68,0.2); color: #fca5a5; padding: 2px 6px; border-radius: 4px;">private
                            $gajiPokok</span></td>
                    <td><?= $formatRp($karyawan->getGajiPokok()) ?></td>
                </tr>
                <tr>
                    <td>getJamLembur() <span
                            style="font-size: 10px; background: rgba(239,68,68,0.2); color: #fca5a5; padding: 2px 6px; border-radius: 4px;">private
                            $jamLembur</span></td>
                    <td><?= $karyawan->getJamLembur() ?> Jam</td>
                </tr>
            </table>
        </div>

        <!-- CARD 2: 4 METHOD BISNIS -->
        <div class="bento-card">
            <div class="card-title">
                <span style="color: #a855f7;">⚡</span> 4 Method Bisnis
            </div>
            <table>
                <tr>
                    <td>hitungTunjanganJabatan()</td>
                    <td><?= $formatRp($karyawan->hitungTunjanganJabatan()) ?></td>
                </tr>
                <tr>
                    <td>hitungUangLembur()</td>
                    <td><?= $formatRp($karyawan->hitungUangLembur()) ?></td>
                </tr>
                <tr>
                    <td>hitungPotongan()</td>
                    <td style="color: #f87171;">- <?= $formatRp($karyawan->hitungPotongan()) ?></td>
                </tr>
                <tr>
                    <td>hitungGajiBersih()</td>
                    <td style="color: #34d399; font-size: 16px;"><?= $formatRp($karyawan->hitungGajiBersih()) ?></td>
                </tr>
            </table>
        </div>

        <!-- CARD 3: SLIP GAJI -->
        <div class="bento-card">
            <div class="card-title">
                <span style="color: #34d399;">📄</span> Output Method cetakSlipGaji()
            </div>
            <div class="terminal"><?= htmlspecialchars($karyawan->cetakSlipGaji()) ?></div>
        </div>
    </div>
</body>

</html>
<?php
}