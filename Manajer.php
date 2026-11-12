<?php

require_once __DIR__ . '/Karyawan.php';

/**
 * Class Manajer
 * 
 * Turunan (Child Class) dari class Karyawan yang mengimplementasikan
 * konsep Inheritance dan Polymorphism dalam OOP PHP.
 */
class Manajer extends Karyawan
{
    // ==========================================
    // 5 ATRIBUT SPESIFIK BARU (PRIVATE)
    // ==========================================
    private string $departemen;
    private int $jumlahBawahan;
    private float $tunjanganManajerial;
    private float $skorKpi;
    private float $anggaranDepartemen;

    // ==========================================
    // METHOD 1: CONSTRUCTOR (parent::__construct)
    // ==========================================
    public function __construct(
        string $nik,
        string $nama,
        float $gajiPokok,
        int $jamLembur,
        string $departemen,
        int $jumlahBawahan,
        float $tunjanganManajerial = 3500000.0,
        float $skorKpi = 85.0,
        float $anggaranDepartemen = 50000000.0,
        string $jabatan = "Manager"
    ) {
        // Memanggil constructor class parent (Karyawan)
        parent::__construct($nik, $nama, $jabatan, $gajiPokok, $jamLembur);

        $this->departemen = $departemen;
        $this->setJumlahBawahan($jumlahBawahan);
        $this->setTunjanganManajerial($tunjanganManajerial);
        $this->setSkorKpi($skorKpi);
        $this->setAnggaranDepartemen($anggaranDepartemen);
    }

    // ==========================================
    // GETTER & SETTER UNTUK 5 ATRIBUT BARU
    // ==========================================
    public function getDepartemen(): string
    {
        return $this->departemen;
    }

    public function setDepartemen(string $departemen): void
    {
        $this->departemen = trim($departemen);
    }

    public function getJumlahBawahan(): int
    {
        return $this->jumlahBawahan;
    }

    public function setJumlahBawahan(int $jumlahBawahan): void
    {
        if ($jumlahBawahan < 0) {
            throw new InvalidArgumentException("Jumlah bawahan tidak boleh bernilai negatif.");
        }
        $this->jumlahBawahan = $jumlahBawahan;
    }

    public function getTunjanganManajerial(): float
    {
        return $this->tunjanganManajerial;
    }

    public function setTunjanganManajerial(float $tunjanganManajerial): void
    {
        if ($tunjanganManajerial < 0) {
            throw new InvalidArgumentException("Tunjangan manajerial tidak boleh bernilai negatif.");
        }
        $this->tunjanganManajerial = $tunjanganManajerial;
    }

    public function getSkorKpi(): float
    {
        return $this->skorKpi;
    }

    public function setSkorKpi(float $skorKpi): void
    {
        if ($skorKpi < 0 || $skorKpi > 100) {
            throw new InvalidArgumentException("Skor KPI harus berada dalam rentang 0 sampai 100.");
        }
        $this->skorKpi = $skorKpi;
    }

    public function getAnggaranDepartemen(): float
    {
        return $this->anggaranDepartemen;
    }

    public function setAnggaranDepartemen(float $anggaranDepartemen): void
    {
        if ($anggaranDepartemen < 0) {
            throw new InvalidArgumentException("Anggaran departemen tidak boleh bernilai negatif.");
        }
        $this->anggaranDepartemen = $anggaranDepartemen;
    }

    // ==========================================
    // METHOD-METHOD BARU SPESIFIK MANAJER
    // ==========================================

    /**
     * METHOD 2: Menghitung bonus berbasis kinerja/KPI
     */
    public function hitungBonusKpi(): float
    {
        if ($this->skorKpi >= 90.0) {
            return $this->getGajiPokok() * 0.20;
        } elseif ($this->skorKpi >= 75.0) {
            return $this->getGajiPokok() * 0.10;
        }

        return 0.0;
    }

    /**
     * METHOD 3: Menghitung tunjangan supervisi/kepemimpinan
     */
    public function hitungTunjanganKepemimpinan(float $tarifPerBawahan = 150000.0): float
    {
        return $this->jumlahBawahan * $tarifPerBawahan;
    }

    /**
     * METHOD 4: Mengalokasikan dana dari anggaran departemen
     */
    public function alokasikanAnggaran(float $nominal, string $keperluan): bool
    {
        if ($nominal <= 0) {
            throw new InvalidArgumentException("Nominal alokasi anggaran harus lebih dari 0.");
        }

        if ($nominal > $this->anggaranDepartemen) {
            return false;
        }

        $this->anggaranDepartemen -= $nominal;
        return true;
    }

    /**
     * Method Override: Gaji Bersih Manajer (Polymorphism)
     */
    public function hitungGajiBersih(): float
    {
        $penghasilanKotor = $this->getGajiPokok()
            + $this->hitungTunjanganJabatan()
            + $this->hitungUangLembur()
            + $this->tunjanganManajerial
            + $this->hitungTunjanganKepemimpinan()
            + $this->hitungBonusKpi();

        $pajak = $penghasilanKotor * 0.05;
        $potonganBpjs = 100000.0;

        return $penghasilanKotor - ($pajak + $potonganBpjs);
    }

    /**
     * Method Tambahan: Cetak Slip Gaji Eksekutif
     */
    public function cetakSlipGajiManajer(): string
    {
        $formatRupiah = fn(float $nilai) => "Rp " . number_format($nilai, 0, ',', '.');

        $tunjJabatan      = $this->hitungTunjanganJabatan();
        $uangLembur       = $this->hitungUangLembur();
        $tunjKepemimpinan = $this->hitungTunjanganKepemimpinan();
        $bonusKpi         = $this->hitungBonusKpi();
        $gajiKotor        = $this->getGajiPokok() + $tunjJabatan + $uangLembur 
                            + $this->tunjanganManajerial + $tunjKepemimpinan + $bonusKpi;
        $totalPotongan    = ($gajiKotor * 0.05) + 100000.0;
        $gajiBersih       = $this->hitungGajiBersih();

        $output = "====================================================\n";
        $output .= "            SLIP GAJI EKSEKUTIF (MANAJER)           \n";
        $output .= "====================================================\n";
        $output .= sprintf("NIK              : %s\n", $this->getNik());
        $output .= sprintf("Nama Manajer     : %s\n", $this->getNama());
        $output .= sprintf("Jabatan          : %s\n", $this->getJabatan());
        $output .= sprintf("Departemen       : %s\n", $this->departemen);
        $output .= sprintf("Jumlah Anggota Tim: %d Orang\n", $this->jumlahBawahan);
        $output .= sprintf("Skor KPI Kinerja : %.1f / 100\n", $this->skorKpi);
        $output .= sprintf("Sisa Anggaran Div: %s\n", $formatRupiah($this->anggaranDepartemen));
        $output .= "----------------------------------------------------\n";
        $output .= sprintf("Gaji Pokok       : %s\n", $formatRupiah($this->getGajiPokok()));
        $output .= sprintf("Tunj. Jabatan    : %s\n", $formatRupiah($tunjJabatan));
        $output .= sprintf("Tunj. Manajerial : %s\n", $formatRupiah($this->tunjanganManajerial));
        $output .= sprintf("Tunj. Kepemimpinan: %s\n", $formatRupiah($tunjKepemimpinan));
        $output .= sprintf("Bonus Capaian KPI: %s\n", $formatRupiah($bonusKpi));
        $output .= sprintf("Uang Lembur      : %s\n", $formatRupiah($uangLembur));
        $output .= "----------------------------------------------------\n";
        $output .= sprintf("Total Bruto      : %s\n", $formatRupiah($gajiKotor));
        $output .= sprintf("Total Potongan   : - %s\n", $formatRupiah($totalPotongan));
        $output .= "====================================================\n";
        $output .= sprintf("TAKE HOME PAY    : %s\n", $formatRupiah($gajiBersih));
        $output .= "====================================================\n";

        return $output;
    }
}

// ==========================================
// HALAMAN KHUSUS MANAJER (MOTION.DEV STYLE)
// ==========================================
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    // Mode CLI Terminal
    if (php_sapi_name() === 'cli') {
        echo "=== DEMO PENGUJIAN CLASS MANAJER (INHERITANCE OOP PHP) ===\n\n";
        $manajer = new Manajer(
            nik: "MGR-001",
            nama: "Hendro Wibowo, M.Kom",
            gajiPokok: 12000000,
            jamLembur: 6,
            departemen: "Teknologi Informasi & Sistem",
            jumlahBawahan: 8,
            tunjanganManajerial: 4000000,
            skorKpi: 92.5,
            anggaranDepartemen: 75000000
        );
        echo $manajer->cetakSlipGajiManajer();
        exit(0);
    }

    // Instansiasi untuk Halaman Web
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
    $formatRp = fn(float $v) => "Rp " . number_format($v, 0, ',', '.');
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Halaman Manajer (Child Class) — UTS PBO</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --bg: #09090b;
                --surface: #121217;
                --surface-glass: rgba(18, 18, 24, 0.75);
                --border: rgba(255, 255, 255, 0.08);
                --border-hover: rgba(255, 255, 255, 0.18);
                --text: #f4f4f5;
                --text-muted: #a1a1aa;
                --accent-purple: #a855f7;
                --accent-pink: #ec4899;
            }
            * { box-sizing: border-box; margin: 0; padding: 0; }
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
                background: radial-gradient(circle, #a855f7, #ec4899);
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
                color: #d8b4fe;
                text-decoration: none;
                font-size: 13.5px;
                font-weight: 600;
                padding: 6px 14px;
                border-radius: 9999px;
                background: rgba(168, 85, 247, 0.1);
                border: 1px solid rgba(168, 85, 247, 0.25);
                transition: all 0.2s;
            }
            .nav-link-back:hover {
                background: rgba(168, 85, 247, 0.2);
                transform: translateX(-3px);
            }
            .nav-btn-prev {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: #93c5fd;
                text-decoration: none;
                font-size: 13px;
                font-weight: 600;
                padding: 6px 14px;
                border-radius: 9999px;
                background: rgba(59, 130, 246, 0.1);
                border: 1px solid rgba(59, 130, 246, 0.25);
                transition: all 0.2s;
            }
            .nav-btn-prev:hover {
                background: rgba(59, 130, 246, 0.2);
            }
            .hero-header {
                text-align: center;
                margin-bottom: 40px;
            }
            .pill-badge {
                display: inline-block;
                padding: 4px 14px;
                border-radius: 9999px;
                background: rgba(168, 85, 247, 0.15);
                border: 1px solid rgba(168, 85, 247, 0.3);
                color: #d8b4fe;
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
            td:first-child { border-radius: 8px 0 0 8px; font-family: 'JetBrains Mono', monospace; font-size: 13px; }
            td:last-child { border-radius: 0 8px 8px 0; text-align: right; font-weight: 600; font-family: 'JetBrains Mono', monospace; }
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
                html, body {
                    overflow-x: hidden;
                    max-width: 100%;
                }
                .container { padding: 14px; }
                nav {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 8px;
                    border-radius: 18px;
                    padding: 12px;
                    margin-bottom: 24px;
                }
                .nav-link-back,
                .nav-btn-prev {
                    width: 100%;
                    justify-content: center;
                    text-align: center;
                }
                .hero-header { margin-bottom: 22px; }
                .hero-header h1 { font-size: 28px; }
                .hero-header p { font-size: 14px; }
                .bento-card { padding: 16px; }
                table, tbody, tr, td { display: block; width: 100%; }
                table { font-size: 12.5px; }
                tr { margin-bottom: 8px; border-radius: 8px; padding: 8px 10px; }
                td { padding: 2px 0; white-space: normal; border-radius: 0; }
                td:last-child { text-align: left; }
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
                <a href="Karyawan.php" class="nav-btn-prev">
                    <span>← Buka Halaman Karyawan</span>
                </a>
            </nav>

            <div class="hero-header">
                <span class="pill-badge">Child Class (extends Karyawan)</span>
                <h1>Class Manajer</h1>
                <p>Implementasi Inheritance, parent::__construct, 5 Atribut Manajerial, dan Method Overriding.</p>
            </div>

            <!-- CARD 1: 5 ATRIBUT SPESIFIK & GETTER -->
            <div class="bento-card">
                <div class="card-title">
                    <span style="color: #c084fc;">🔒</span> 5 Atribut Spesifik Manajer (Private) & Method Getter
                </div>
                <table>
                    <tr>
                        <td>getDepartemen() <span style="font-size: 10px; background: rgba(168,85,247,0.2); color: #d8b4fe; padding: 2px 6px; border-radius: 4px;">spesifik</span></td>
                        <td style="color: #c084fc;"><?= htmlspecialchars($manajer->getDepartemen()) ?></td>
                    </tr>
                    <tr>
                        <td>getJumlahBawahan() <span style="font-size: 10px; background: rgba(168,85,247,0.2); color: #d8b4fe; padding: 2px 6px; border-radius: 4px;">spesifik</span></td>
                        <td><?= $manajer->getJumlahBawahan() ?> Anggota Tim</td>
                    </tr>
                    <tr>
                        <td>getTunjanganManajerial() <span style="font-size: 10px; background: rgba(168,85,247,0.2); color: #d8b4fe; padding: 2px 6px; border-radius: 4px;">spesifik</span></td>
                        <td><?= $formatRp($manajer->getTunjanganManajerial()) ?></td>
                    </tr>
                    <tr>
                        <td>getSkorKpi() <span style="font-size: 10px; background: rgba(168,85,247,0.2); color: #d8b4fe; padding: 2px 6px; border-radius: 4px;">spesifik</span></td>
                        <td style="color: #38bdf8;"><?= number_format($manajer->getSkorKpi(), 1) ?> / 100</td>
                    </tr>
                    <tr>
                        <td>getAnggaranDepartemen() <span style="font-size: 10px; background: rgba(168,85,247,0.2); color: #d8b4fe; padding: 2px 6px; border-radius: 4px;">spesifik</span></td>
                        <td><?= $formatRp($manajer->getAnggaranDepartemen()) ?></td>
                    </tr>
                </table>
            </div>

            <!-- CARD 2: METHOD SPESIFIK & OVERRIDE -->
            <div class="bento-card">
                <div class="card-title">
                    <span style="color: #ec4899;">⚡</span> Method Baru & Override (Polymorphism)
                </div>
                <table>
                    <tr>
                        <td>hitungTunjanganKepemimpinan()</td>
                        <td><?= $formatRp($manajer->hitungTunjanganKepemimpinan()) ?></td>
                    </tr>
                    <tr>
                        <td>hitungBonusKpi()</td>
                        <td style="color: #f472b6;"><?= $formatRp($manajer->hitungBonusKpi()) ?></td>
                    </tr>
                    <tr>
                        <td>alokasikanAnggaran(Rp 25.000.000)</td>
                        <td style="color: #34d399;">✓ Berhasil Dialokasikan</td>
                    </tr>
                    <tr>
                        <td>hitungGajiBersih() <span style="font-size: 10px; background: rgba(245,158,11,0.2); color: #fcd34d; padding: 2px 6px; border-radius: 4px;">override</span></td>
                        <td style="color: #34d399; font-size: 16px;"><?= $formatRp($manajer->hitungGajiBersih()) ?></td>
                    </tr>
                </table>
            </div>

            <!-- CARD 3: SLIP GAJI EKSEKUTIF -->
            <div class="bento-card">
                <div class="card-title">
                    <span style="color: #34d399;">📄</span> Output Method cetakSlipGajiManajer()
                </div>
                <div class="terminal"><?= htmlspecialchars($manajer->cetakSlipGajiManajer()) ?></div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
