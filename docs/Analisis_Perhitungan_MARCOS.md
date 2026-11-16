# Analisis dan Perhitungan Lengkap Metode MARCOS — Sistem Rekomendasi Bundling Pernikahan (E-Commerce)
Dokumen ini berisi hasil audit menyeluruh terhadap perhitungan metode **MARCOS (*Measurement of Alternatives and Ranking according to Compromise Solution*)** pada dokumen sumber (`Proposal_Perhhitungan_cba6b39b.pdf`), disertai perhitungan ulang yang **100% akurat secara matematis** dari awal hingga perankingan akhir untuk diimplementasikan ke dalam sistem *e-commerce*.
---
## 1. Hasil Audit & Temuan Kesalahan pada Dokumen Sumber
Sebelum diimplementasikan ke sistem *e-commerce*, ditemukan **7 kesalahan kritis** pada perhitungan manual di dokumen sumber yang menyebabkan hasil perankingan menjadi terbalik dan tidak valid:
1. **Kesalahan Pembalikan Ganda (*Double Inversion*) pada Kriteria Cost (`C1`, `C2`, `C6`):**
   - Pada Tabel Kriteria, `C1` (Harga), `C2` (Biaya Tambahan), dan `C6` (Risiko Kerusakan) ditetapkan bersifat **Cost** (semakin kecil nilainya semakin baik).
   - Namun pada Tabel Sub-Kriteria, skor sudah dibalik menjadi skala kepuasan (`≤ Rp3.000.000 = 5` dan `> Rp18.000.000 = 1`; `≤ Rp100.000 = 5` dan `> Rp250.000 = 1`; `Sangat rendah = 5` dan `Sangat tinggi = 1`).
   - Saat menentukan Solusi Ideal ($AI$) dan Anti-Ideal ($AAI$), dokumen tetap memakai aturan Cost ($AI = \min X_{ij} = 1$). Akibatnya, paket paling mahal (`> Rp18.000.000`, skor 1) justru dianggap **Solusi Ideal terbaik**, sedangkan paket termurah (`≤ Rp3.000.000`, skor 5) dianggap **Anti-Ideal terburuk**. Inilah penyebab `Bundling 9` (Rp26.500.000) dan `Paket Wedding Gedung Luxury` (Rp21.000.000, fleksibilitas `Kurang Baik`) menempati peringkat 1–2 di dokumen sumber.
2. **Penentuan Nilai $AI$ dan $AAI$ Hanya Mengambil Sampel `A1`–`A15` (Bukan Seluruh `A1`–`A32`):**
   - Dokumen menulis $AAI_{C1}=4$, $AI_{C2}=3$, dan $AAI_{C4}=4$. Nilai tersebut hanya diambil dari baris `A1`–`A15`, padahal di keseluruhan 32 data terdapat nilai `C1 = 5` (`A16`, `A18`, `A21`, `A24`, `A27`), `C2 = 2` (`A28`–`A32`) & `C2 = 5` (`A16`–`A21`, `A24`–`A25`), serta `C4 = 1` (`A28`, `A29`).
3. **Rumus Normalisasi Cost Tidak Sesuai Teori MARCOS (Stević et al., 2020):**
   - Dokumen menuliskan rumus Cost $n_{ij} = \frac{X_{AAI}}{X_{ij}}$, padahal rumus MARCOS baku adalah $n_{ij} = \frac{X_{AI}}{X_{ij}}$ (untuk Cost) dan $n_{ij} = \frac{X_{ij}}{X_{AI}}$ (untuk Benefit) agar seluruh nilai normalisasi berada pada rentang $(0, 1]$ dan $S_{AI} = 1{,}000000$. Akibat rumus terbalik tersebut, normalisasi Cost di dokumen membengkak hingga `4,0000`.
4. **Nilai $S_{AAI} = 0{,}696665$ dan $S_{AI} = 3{,}75$ Tidak Konsisten dengan Matriks Diperluas:**
   - Nilai $S_{AAI}$ dan $S_{AI}$ pada dokumen muncul tanpa perhitungan baris $AAI$ dan $AI$ terbobot. Jika dihitung dari $AI/AAI$ versi dokumen sendiri sekalipun, hasilnya adalah $S_{AI} = 1{,}65$ dan $S_{AAI} = 0{,}76$, bukan `3,75` dan `0,696665`.
5. **Kesalahan Hitung dan Salah Salin Angka pada Langkah Normalisasi, Terbobot ($V_{ij}$), dan $S_i$:**
   - `V35`: tertulis `0,20 × 1,0000 = 1,0000` (seharusnya `0,2000`; di `S3` dipakai `0,2000`).
   - `V114`: tertulis `0,10 × 0,8000 = 0,1600` (seharusnya `0,0800`; di `S11` dipakai `0,08`).
   - `V134`, `V135`, `V137`: salah salin koma (`8,0000` dan `6,0000`), menghasilkan `V135 = 1,2` dan `V137 = 1,2` (seharusnya masing-masing `0,12`). Lalu di `S13`, dokumen justru menyalin angka `0,1600` milik `A14` sehingga `S13` menjadi `1,519995` (identik dengan `A14`), padahal `A13` (`Bundling 7`) memiliki Kualitas Hasil `Cukup` dan Estetika `Cukup Baik`.
   - `n272`: tertulis `4/5 = 0,8000` (seharusnya `4/4 = 1,0000`), membuat `V272` salah menjadi `0,08` (seharusnya `0,10`) dan `S27` menjadi `0,910000` (seharusnya `0,930000`).
   - `V277`: tertulis `02` (hilang tanda koma, seharusnya `0,2`).
6. **Typo Penulisan Rumus & Konstanta Akhir:**
   - Rumus $f(K_i)$ tertulis `ff(Ki+)` dan `ff(Ki-)` (kelebihan huruf `f`).
   - Pada `f(K21+)` dan `f(K21-)`, nilai `K21-` tertulis `1,148328`, berbeda dengan hasil pembagian sebelumnya (`1,148320`).
7. **Duplikasi Nama Paket pada Data Sumber (Penting untuk Database E-Commerce):**
   - Terdapat **3 pasang nama paket yang identik** dengan spesifikasi dan harga berbeda:
     - `Akad Pakage`: **A6** (Rp4.000.000) dan **A21** (Rp1.500.000)
     - `Wedding Pakage 1`: **A22** (Rp3.500.000) dan **A28** (Rp10.000.000)
     - `Wedding Pakage 2`: **A23** (Rp4.000.000) dan **A29** (Rp15.000.000)
   - Oleh karena itu, sistem *e-commerce* wajib menggunakan `Kode` (`A1`–`A32`) / `id` sebagai *primary key*.

---
## 2. Data Penilaian Alternatif (Tabel 4.1 Data Bundling Pernikahan)
Seluruh 32 paket alternatif di bawah ini disalin **persis** sesuai penamaan dan atribut pada Tabel 4.1 dokumen sumber.

| Kode | Nama Paket (Alternatif) | Harga (C1) | Biaya Tambahan (C2) | Kelengkapan (C3) | Fleksibilitas Layanan (C4) | Kualitas Hasil (C5) | Risiko Kerusakan (C6) | Estetika dan Desain (C7) |
| :--- | :--- | ---: | ---: | :--- | :--- | :--- | :--- | :--- |
| A1 | Medium Pakage | Rp12.000.000 | Rp150.000 | Lengkap | Sangat Baik | Baik | Sedang | Baik |
| A2 | Mini Pakage | Rp10.000.000 | Rp150.000 | Cukup Lengkap | Sangat Baik | Baik | Rendah | Cukup Baik |
| A3 | Lux Pakage | Rp13.000.000 | Rp150.000 | Sangat Lengkap | Sangat Baik | Sangat Baik | Rendah | Sangat Baik |
| A4 | Wedding Silver Pakage | Rp16.000.000 | Rp150.000 | Lengkap | Sangat Baik | Sangat Baik | Sedang | Baik |
| A5 | Standart Pakage | Rp11.000.000 | Rp150.000 | Cukup Lengkap | Sangat Baik | Baik | Sedang | Baik |
| A6 | Akad Pakage | Rp4.000.000 | Rp150.000 | Cukup Lengkap | Sangat Baik | Baik | Rendah | Baik |
| A7 | Bundling 1 | Rp9.500.000 | Rp200.000 | Sangat Lengkap | Baik | Sangat Baik | Sedang | Sangat Baik |
| A8 | Bundling 2 | Rp10.500.000 | Rp150.000 | Lengkap | Baik | Baik | Sedang | Baik |
| A9 | Bundling 3 | Rp12.000.000 | Rp150.000 | Sangat Lengkap | Baik | Sangat Baik | Rendah | Sangat Baik |
| A10 | Bundling 4 | Rp15.000.000 | Rp200.000 | Sangat Lengkap | Baik | Baik | Sedang | Baik |
| A11 | Bundling 5 | Rp17.000.000 | Rp150.000 | Lengkap | Baik | Baik | Sedang | Baik |
| A12 | Bundling 6 | Rp19.000.000 | Rp200.000 | Sangat Lengkap | Baik | Baik | Sedang | Baik |
| A13 | Bundling 7 | Rp21.500.000 | Rp200.000 | Lengkap | Baik | Cukup | Sedang | Cukup Baik |
| A14 | Bundling 8 | Rp23.500.000 | Rp200.000 | Lengkap | Baik | Baik | Sedang | Baik |
| A15 | Bundling 9 | Rp26.500.000 | Rp200.000 | Sangat Lengkap | Baik | Baik | Sedang | Baik |
| A16 | Bundling 10 | Rp2.500.000 | Rp100.000 | Cukup Lengkap | Baik | Baik | Rendah | Baik |
| A17 | Bundling 11 | Rp3.500.000 | Rp100.000 | Cukup Lengkap | Baik | Baik | Rendah | Cukup Baik |
| A18 | Bundling 12 | Rp2.000.000 | Rp100.000 | Cukup Lengkap | Baik | Baik | Rendah | Baik |
| A19 | Bundling 13 | Rp4.000.000 | Rp100.000 | Cukup Lengkap | Baik | Baik | Rendah | Cukup Baik |
| A20 | Bundling 14 | Rp5.000.000 | Rp100.000 | Cukup Lengkap | Baik | Baik | Rendah | Baik |
| A21 | Akad Pakage | Rp1.500.000 | Rp100.000 | Cukup Lengkap | Sangat Baik | Baik | Rendah | Baik |
| A22 | Wedding Pakage 1 | Rp3.500.000 | Rp200.000 | Sangat Lengkap | Sangat Baik | Sangat Baik | Sedang | Sangat Baik |
| A23 | Wedding Pakage 2 | Rp4.000.000 | Rp200.000 | Sangat Lengkap | Sangat Baik | Sangat Baik | Sedang | Sangat Baik |
| A24 | Akad + Resepsi Pakage | Rp2.000.000 | Rp100.000 | Sangat Lengkap | Sangat Baik | Baik | Sedang | Baik |
| A25 | Akad Intimate Pakage | Rp3.200.000 | Rp100.000 | Cukup Lengkap | Sangat Baik | Baik | Rendah | Baik |
| A26 | Wedding Pakage 3 | Rp15.000.000 | Rp200.000 | Lengkap | Sangat Baik | Baik | Sedang | Baik |
| A27 | Prewedding Pakage | Rp2.100.000 | Rp150.000 | Lengkap | Sangat Baik | Sangat Baik | Rendah | Sangat Baik |
| A28 | Wedding Pakage 1 | Rp10.000.000 | Rp250.000 | Sangat Lengkap | Tidak Baik | Baik | Sedang | Baik |
| A29 | Wedding Pakage 2 | Rp15.000.000 | Rp250.000 | Sangat Lengkap | Tidak Baik | Baik | Sedang | Baik |
| A30 | Paket Wedding Standart Rumah | Rp10.000.000 | Rp250.000 | Lengkap | Kurang Baik | Baik | Sedang | Baik |
| A31 | Paket Wedding Gedung Luxury | Rp21.000.000 | Rp250.000 | Sangat Lengkap | Kurang Baik | Sangat Baik | Sedang | Sangat Baik |
| A32 | Paket Wedding Rumah | Rp23.000.000 | Rp250.000 | Lengkap | Kurang Baik | Baik | Sedang | Baik |

> **Catatan Data Asli:** Penulisan nama paket seperti `Pakage` dan `Standart` dipertahankan sesuai data sumber agar konsisten dengan daftar produk/proposal Anda.

---
## 3. Penentuan Kriteria, Sifat, dan Nilai Bobot
Terdapat **7 kriteria** dengan total bobot **100% (`1,00`)**:

| Kode | Nama Kriteria | Sifat | Bobot (%) | Bobot Desimal ($w_j$) |
| :---: | :--- | :---: | :---: | :---: |
| C1 | Harga | **Cost** | 20% | 0,20 |
| C2 | Biaya Tambahan | **Cost** | 10% | 0,10 |
| C3 | Kelengkapan | **Benefit** | 15% | 0,15 |
| C4 | Fleksibilitas Layanan | **Benefit** | 10% | 0,10 |
| C5 | Kualitas Hasil | **Benefit** | 20% | 0,20 |
| C6 | Risiko Kerusakan | **Cost** | 5% | 0,05 |
| C7 | Estetika dan Desain | **Benefit** | 20% | 0,20 |
| | **Total** | | **100%** | **1,00** |

---
## 4. Kriteria dan Sub-Kriteria Penilaian (Perbaikan Arah Skala Cost)
Agar sifat kriteria **Cost** (`C1`, `C2`, `C6`) selaras dengan rumus MARCOS ($AI = \min X_{ij}$ dan $n_{ij} = \frac{X_{AI}}{X_{ij}}$), maka pada kriteria **Cost**, nilai **1** merepresentasikan beban biaya/risiko **paling rendah (terbaik)** dan nilai **5** merepresentasikan beban biaya/risiko **paling tinggi (terburuk)**. Sebaliknya, pada kriteria **Benefit** (`C3`, `C4`, `C5`, `C7`), nilai **5** adalah **terbaik** dan **1** adalah **terburuk**.

| No | Kode | Nama Kriteria | Sifat | Sub-Kriteria | Nilai Skala (Benar) | Nilai di PDF Lama |
| :---: | :---: | :--- | :---: | :--- | :---: | :---: |
| 1 | C1 | Harga | Cost | ≤ Rp3.000.000 | **1** | 5 |
|  |  |  |  | > Rp3.000.000 – Rp7.000.000 | **2** | 4 |
|  |  |  |  | > Rp7.000.000 – Rp12.000.000 | **3** | 3 |
|  |  |  |  | > Rp12.000.000 – Rp18.000.000 | **4** | 2 |
|  |  |  |  | > Rp18.000.000 | **5** | 1 |
| 2 | C2 | Biaya Tambahan | Cost | ≤ Rp100.000 | **1** | 5 |
|  |  |  |  | > Rp100.000 – Rp150.000 | **2** | 4 |
|  |  |  |  | > Rp150.000 – Rp200.000 | **3** | 3 |
|  |  |  |  | > Rp200.000 – Rp250.000 | **4** | 2 |
|  |  |  |  | > Rp250.000 | **5** | 1 |
| 3 | C3 | Kelengkapan | Benefit | Sangat lengkap | **5** | 5 |
|  |  |  |  | Lengkap | **4** | 4 |
|  |  |  |  | Cukup lengkap | **3** | 3 |
|  |  |  |  | Kurang lengkap | **2** | 2 |
|  |  |  |  | Tidak lengkap | **1** | 1 |
| 4 | C4 | Fleksibilitas Layanan | Benefit | Sangat baik | **5** | 5 |
|  |  |  |  | Baik | **4** | 4 |
|  |  |  |  | Cukup baik | **3** | 3 |
|  |  |  |  | Kurang baik | **2** | 2 |
|  |  |  |  | Tidak baik | **1** | 1 |
| 5 | C5 | Kualitas Hasil | Benefit | Sangat baik | **5** | 5 |
|  |  |  |  | Baik | **4** | 4 |
|  |  |  |  | Cukup / Cukup baik | **3** | 3 |
|  |  |  |  | Kurang baik | **2** | 2 |
|  |  |  |  | Tidak baik | **1** | 1 |
| 6 | C6 | Risiko Kerusakan | Cost | Sangat rendah | **1** | 5 |
|  |  |  |  | Rendah | **2** | 4 |
|  |  |  |  | Sedang | **3** | 3 |
|  |  |  |  | Tinggi | **4** | 2 |
|  |  |  |  | Sangat tinggi | **5** | 1 |
| 7 | C7 | Estetika dan Desain | Benefit | Sangat baik | **5** | 5 |
|  |  |  |  | Baik | **4** | 4 |
|  |  |  |  | Cukup baik | **3** | 3 |
|  |  |  |  | Kurang baik | **2** | 2 |
|  |  |  |  | Tidak baik | **1** | 1 |

---
## 5. Perhitungan Metode MARCOS (Lengkap Langkah demi Langkah)
### Langkah 1: Pembentukan Matriks Keputusan ($X$)
Matriks keputusan $X = [x_{ij}]_{32 \times 7}$ dibentuk dengan mengonversi seluruh data Tabel 4.1 berdasarkan tabel sub-kriteria pada Bagian 4:

| Kode | Alternatif | C1 (Cost) | C2 (Cost) | C3 (Benefit) | C4 (Benefit) | C5 (Benefit) | C6 (Cost) | C7 (Benefit) |
| :---: | :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| A1 | Medium Pakage | 3 | 2 | 4 | 5 | 4 | 3 | 4 |
| A2 | Mini Pakage | 3 | 2 | 3 | 5 | 4 | 2 | 3 |
| A3 | Lux Pakage | 4 | 2 | 5 | 5 | 5 | 2 | 5 |
| A4 | Wedding Silver Pakage | 4 | 2 | 4 | 5 | 5 | 3 | 4 |
| A5 | Standart Pakage | 3 | 2 | 3 | 5 | 4 | 3 | 4 |
| A6 | Akad Pakage | 2 | 2 | 3 | 5 | 4 | 2 | 4 |
| A7 | Bundling 1 | 3 | 3 | 5 | 4 | 5 | 3 | 5 |
| A8 | Bundling 2 | 3 | 2 | 4 | 4 | 4 | 3 | 4 |
| A9 | Bundling 3 | 3 | 2 | 5 | 4 | 5 | 2 | 5 |
| A10 | Bundling 4 | 4 | 3 | 5 | 4 | 4 | 3 | 4 |
| A11 | Bundling 5 | 4 | 2 | 4 | 4 | 4 | 3 | 4 |
| A12 | Bundling 6 | 5 | 3 | 5 | 4 | 4 | 3 | 4 |
| A13 | Bundling 7 | 5 | 3 | 4 | 4 | 3 | 3 | 3 |
| A14 | Bundling 8 | 5 | 3 | 4 | 4 | 4 | 3 | 4 |
| A15 | Bundling 9 | 5 | 3 | 5 | 4 | 4 | 3 | 4 |
| A16 | Bundling 10 | 1 | 1 | 3 | 4 | 4 | 2 | 4 |
| A17 | Bundling 11 | 2 | 1 | 3 | 4 | 4 | 2 | 3 |
| A18 | Bundling 12 | 1 | 1 | 3 | 4 | 4 | 2 | 4 |
| A19 | Bundling 13 | 2 | 1 | 3 | 4 | 4 | 2 | 3 |
| A20 | Bundling 14 | 2 | 1 | 3 | 4 | 4 | 2 | 4 |
| A21 | Akad Pakage | 1 | 1 | 3 | 5 | 4 | 2 | 4 |
| A22 | Wedding Pakage 1 | 2 | 3 | 5 | 5 | 5 | 3 | 5 |
| A23 | Wedding Pakage 2 | 2 | 3 | 5 | 5 | 5 | 3 | 5 |
| A24 | Akad + Resepsi Pakage | 1 | 1 | 5 | 5 | 4 | 3 | 4 |
| A25 | Akad Intimate Pakage | 2 | 1 | 3 | 5 | 4 | 2 | 4 |
| A26 | Wedding Pakage 3 | 4 | 3 | 4 | 5 | 4 | 3 | 4 |
| A27 | Prewedding Pakage | 1 | 2 | 4 | 5 | 5 | 2 | 5 |
| A28 | Wedding Pakage 1 | 3 | 4 | 5 | 1 | 4 | 3 | 4 |
| A29 | Wedding Pakage 2 | 4 | 4 | 5 | 1 | 4 | 3 | 4 |
| A30 | Paket Wedding Standart Rumah | 3 | 4 | 4 | 2 | 4 | 3 | 4 |
| A31 | Paket Wedding Gedung Luxury | 5 | 4 | 5 | 2 | 5 | 3 | 5 |
| A32 | Paket Wedding Rumah | 5 | 4 | 4 | 2 | 4 | 3 | 4 |

### Langkah 2: Penentuan Solusi Ideal ($AI$) dan Anti-Ideal ($AAI$) & Matriks Keputusan Diperluas
Sesuai metode MARCOS, solusi Ideal ($AI$) dan Anti-Ideal ($AAI$) dihitung dari **seluruh 32 alternatif (`A1`–`A32`)**:
- Untuk kriteria **Benefit** ($B$): $AI_j = \max_i x_{ij}$ dan $AAI_j = \min_i x_{ij}$
- Untuk kriteria **Cost** ($C$): $AI_j = \min_i x_{ij}$ dan $AAI_j = \max_i x_{ij}$

| Kriteria | Sifat | Nilai Minimum ($\min x_{ij}$) | Nilai Maksimum ($\max x_{ij}$) | Solusi Ideal ($AI_j$) | Solusi Anti-Ideal ($AAI_j$) |
| :---: | :---: | :---: | :---: | :---: | :---: |
| C1 (Harga) | Cost | 1 | 5 | **1** | **5** |
| C2 (Biaya Tambahan) | Cost | 1 | 4 | **1** | **4** |
| C3 (Kelengkapan) | Benefit | 3 | 5 | **5** | **3** |
| C4 (Fleksibilitas Layanan) | Benefit | 1 | 5 | **5** | **1** |
| C5 (Kualitas Hasil) | Benefit | 3 | 5 | **5** | **3** |
| C6 (Risiko Kerusakan) | Cost | 2 | 3 | **2** | **3** |
| C7 (Estetika & Desain) | Benefit | 3 | 5 | **5** | **3** |

Baris $AAI$ dan $AI$ kemudian ditambahkan ke dalam matriks keputusan menjadi **Matriks Keputusan Diperluas ($X_{ext}$)**:
- Baris **$AAI$**: `[5, 4, 3, 1, 3, 3, 3]`
- Baris **$AI$**: `[1, 1, 5, 5, 5, 2, 5]`

### Langkah 3: Normalisasi Matriks Keputusan ($N = [n_{ij}]$)
Rumus normalisasi baku metode MARCOS (Stević et al., 2020):
- **Cost** (`C1`, `C2`, `C6`): $\displaystyle n_{ij} = \frac{X_{AI,j}}{X_{ij}}$
- **Benefit** (`C3`, `C4`, `C5`, `C7`): $\displaystyle n_{ij} = \frac{X_{ij}}{X_{AI,j}}$

> Seluruh hasil normalisasi pasti berada pada rentang $(0, 1]$, dengan nilai baris ideal $n_{AI,j} = 1{,}0000$ untuk semua kriteria.

| Kode | Alternatif | $n_{i1}$ (C1) | $n_{i2}$ (C2) | $n_{i3}$ (C3) | $n_{i4}$ (C4) | $n_{i5}$ (C5) | $n_{i6}$ (C6) | $n_{i7}$ (C7) |
| :---: | :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **AAI** | **Anti-Ideal** | 0,2000 | 0,2500 | 0,6000 | 0,2000 | 0,6000 | 0,6667 | 0,6000 |
| A1 | Medium Pakage | 0,3333 | 0,5000 | 0,8000 | 1,0000 | 0,8000 | 0,6667 | 0,8000 |
| A2 | Mini Pakage | 0,3333 | 0,5000 | 0,6000 | 1,0000 | 0,8000 | 1,0000 | 0,6000 |
| A3 | Lux Pakage | 0,2500 | 0,5000 | 1,0000 | 1,0000 | 1,0000 | 1,0000 | 1,0000 |
| A4 | Wedding Silver Pakage | 0,2500 | 0,5000 | 0,8000 | 1,0000 | 1,0000 | 0,6667 | 0,8000 |
| A5 | Standart Pakage | 0,3333 | 0,5000 | 0,6000 | 1,0000 | 0,8000 | 0,6667 | 0,8000 |
| A6 | Akad Pakage | 0,5000 | 0,5000 | 0,6000 | 1,0000 | 0,8000 | 1,0000 | 0,8000 |
| A7 | Bundling 1 | 0,3333 | 0,3333 | 1,0000 | 0,8000 | 1,0000 | 0,6667 | 1,0000 |
| A8 | Bundling 2 | 0,3333 | 0,5000 | 0,8000 | 0,8000 | 0,8000 | 0,6667 | 0,8000 |
| A9 | Bundling 3 | 0,3333 | 0,5000 | 1,0000 | 0,8000 | 1,0000 | 1,0000 | 1,0000 |
| A10 | Bundling 4 | 0,2500 | 0,3333 | 1,0000 | 0,8000 | 0,8000 | 0,6667 | 0,8000 |
| A11 | Bundling 5 | 0,2500 | 0,5000 | 0,8000 | 0,8000 | 0,8000 | 0,6667 | 0,8000 |
| A12 | Bundling 6 | 0,2000 | 0,3333 | 1,0000 | 0,8000 | 0,8000 | 0,6667 | 0,8000 |
| A13 | Bundling 7 | 0,2000 | 0,3333 | 0,8000 | 0,8000 | 0,6000 | 0,6667 | 0,6000 |
| A14 | Bundling 8 | 0,2000 | 0,3333 | 0,8000 | 0,8000 | 0,8000 | 0,6667 | 0,8000 |
| A15 | Bundling 9 | 0,2000 | 0,3333 | 1,0000 | 0,8000 | 0,8000 | 0,6667 | 0,8000 |
| A16 | Bundling 10 | 1,0000 | 1,0000 | 0,6000 | 0,8000 | 0,8000 | 1,0000 | 0,8000 |
| A17 | Bundling 11 | 0,5000 | 1,0000 | 0,6000 | 0,8000 | 0,8000 | 1,0000 | 0,6000 |
| A18 | Bundling 12 | 1,0000 | 1,0000 | 0,6000 | 0,8000 | 0,8000 | 1,0000 | 0,8000 |
| A19 | Bundling 13 | 0,5000 | 1,0000 | 0,6000 | 0,8000 | 0,8000 | 1,0000 | 0,6000 |
| A20 | Bundling 14 | 0,5000 | 1,0000 | 0,6000 | 0,8000 | 0,8000 | 1,0000 | 0,8000 |
| A21 | Akad Pakage | 1,0000 | 1,0000 | 0,6000 | 1,0000 | 0,8000 | 1,0000 | 0,8000 |
| A22 | Wedding Pakage 1 | 0,5000 | 0,3333 | 1,0000 | 1,0000 | 1,0000 | 0,6667 | 1,0000 |
| A23 | Wedding Pakage 2 | 0,5000 | 0,3333 | 1,0000 | 1,0000 | 1,0000 | 0,6667 | 1,0000 |
| A24 | Akad + Resepsi Pakage | 1,0000 | 1,0000 | 1,0000 | 1,0000 | 0,8000 | 0,6667 | 0,8000 |
| A25 | Akad Intimate Pakage | 0,5000 | 1,0000 | 0,6000 | 1,0000 | 0,8000 | 1,0000 | 0,8000 |
| A26 | Wedding Pakage 3 | 0,2500 | 0,3333 | 0,8000 | 1,0000 | 0,8000 | 0,6667 | 0,8000 |
| A27 | Prewedding Pakage | 1,0000 | 0,5000 | 0,8000 | 1,0000 | 1,0000 | 1,0000 | 1,0000 |
| A28 | Wedding Pakage 1 | 0,3333 | 0,2500 | 1,0000 | 0,2000 | 0,8000 | 0,6667 | 0,8000 |
| A29 | Wedding Pakage 2 | 0,2500 | 0,2500 | 1,0000 | 0,2000 | 0,8000 | 0,6667 | 0,8000 |
| A30 | Paket Wedding Standart Rumah | 0,3333 | 0,2500 | 0,8000 | 0,4000 | 0,8000 | 0,6667 | 0,8000 |
| A31 | Paket Wedding Gedung Luxury | 0,2000 | 0,2500 | 1,0000 | 0,4000 | 1,0000 | 0,6667 | 1,0000 |
| A32 | Paket Wedding Rumah | 0,2000 | 0,2500 | 0,8000 | 0,4000 | 0,8000 | 0,6667 | 0,8000 |
| **AI** | **Ideal** | 1,0000 | 1,0000 | 1,0000 | 1,0000 | 1,0000 | 1,0000 | 1,0000 |

### Langkah 4: Normalisasi Terbobot ($V = [v_{ij}]$)
Rumus: $\displaystyle v_{ij} = w_j \times n_{ij}$ dengan bobot $w = [0{,}20;\; 0{,}10;\; 0{,}15;\; 0{,}10;\; 0{,}20;\; 0{,}05;\; 0{,}20]$.

| Kode | Alternatif | $v_{i1}$ (0,20) | $v_{i2}$ (0,10) | $v_{i3}$ (0,15) | $v_{i4}$ (0,10) | $v_{i5}$ (0,20) | $v_{i6}$ (0,05) | $v_{i7}$ (0,20) |
| :---: | :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **AAI** | **Anti-Ideal** | 0,040000 | 0,025000 | 0,090000 | 0,020000 | 0,120000 | 0,033333 | 0,120000 |
| A1 | Medium Pakage | 0,066667 | 0,050000 | 0,120000 | 0,100000 | 0,160000 | 0,033333 | 0,160000 |
| A2 | Mini Pakage | 0,066667 | 0,050000 | 0,090000 | 0,100000 | 0,160000 | 0,050000 | 0,120000 |
| A3 | Lux Pakage | 0,050000 | 0,050000 | 0,150000 | 0,100000 | 0,200000 | 0,050000 | 0,200000 |
| A4 | Wedding Silver Pakage | 0,050000 | 0,050000 | 0,120000 | 0,100000 | 0,200000 | 0,033333 | 0,160000 |
| A5 | Standart Pakage | 0,066667 | 0,050000 | 0,090000 | 0,100000 | 0,160000 | 0,033333 | 0,160000 |
| A6 | Akad Pakage | 0,100000 | 0,050000 | 0,090000 | 0,100000 | 0,160000 | 0,050000 | 0,160000 |
| A7 | Bundling 1 | 0,066667 | 0,033333 | 0,150000 | 0,080000 | 0,200000 | 0,033333 | 0,200000 |
| A8 | Bundling 2 | 0,066667 | 0,050000 | 0,120000 | 0,080000 | 0,160000 | 0,033333 | 0,160000 |
| A9 | Bundling 3 | 0,066667 | 0,050000 | 0,150000 | 0,080000 | 0,200000 | 0,050000 | 0,200000 |
| A10 | Bundling 4 | 0,050000 | 0,033333 | 0,150000 | 0,080000 | 0,160000 | 0,033333 | 0,160000 |
| A11 | Bundling 5 | 0,050000 | 0,050000 | 0,120000 | 0,080000 | 0,160000 | 0,033333 | 0,160000 |
| A12 | Bundling 6 | 0,040000 | 0,033333 | 0,150000 | 0,080000 | 0,160000 | 0,033333 | 0,160000 |
| A13 | Bundling 7 | 0,040000 | 0,033333 | 0,120000 | 0,080000 | 0,120000 | 0,033333 | 0,120000 |
| A14 | Bundling 8 | 0,040000 | 0,033333 | 0,120000 | 0,080000 | 0,160000 | 0,033333 | 0,160000 |
| A15 | Bundling 9 | 0,040000 | 0,033333 | 0,150000 | 0,080000 | 0,160000 | 0,033333 | 0,160000 |
| A16 | Bundling 10 | 0,200000 | 0,100000 | 0,090000 | 0,080000 | 0,160000 | 0,050000 | 0,160000 |
| A17 | Bundling 11 | 0,100000 | 0,100000 | 0,090000 | 0,080000 | 0,160000 | 0,050000 | 0,120000 |
| A18 | Bundling 12 | 0,200000 | 0,100000 | 0,090000 | 0,080000 | 0,160000 | 0,050000 | 0,160000 |
| A19 | Bundling 13 | 0,100000 | 0,100000 | 0,090000 | 0,080000 | 0,160000 | 0,050000 | 0,120000 |
| A20 | Bundling 14 | 0,100000 | 0,100000 | 0,090000 | 0,080000 | 0,160000 | 0,050000 | 0,160000 |
| A21 | Akad Pakage | 0,200000 | 0,100000 | 0,090000 | 0,100000 | 0,160000 | 0,050000 | 0,160000 |
| A22 | Wedding Pakage 1 | 0,100000 | 0,033333 | 0,150000 | 0,100000 | 0,200000 | 0,033333 | 0,200000 |
| A23 | Wedding Pakage 2 | 0,100000 | 0,033333 | 0,150000 | 0,100000 | 0,200000 | 0,033333 | 0,200000 |
| A24 | Akad + Resepsi Pakage | 0,200000 | 0,100000 | 0,150000 | 0,100000 | 0,160000 | 0,033333 | 0,160000 |
| A25 | Akad Intimate Pakage | 0,100000 | 0,100000 | 0,090000 | 0,100000 | 0,160000 | 0,050000 | 0,160000 |
| A26 | Wedding Pakage 3 | 0,050000 | 0,033333 | 0,120000 | 0,100000 | 0,160000 | 0,033333 | 0,160000 |
| A27 | Prewedding Pakage | 0,200000 | 0,050000 | 0,120000 | 0,100000 | 0,200000 | 0,050000 | 0,200000 |
| A28 | Wedding Pakage 1 | 0,066667 | 0,025000 | 0,150000 | 0,020000 | 0,160000 | 0,033333 | 0,160000 |
| A29 | Wedding Pakage 2 | 0,050000 | 0,025000 | 0,150000 | 0,020000 | 0,160000 | 0,033333 | 0,160000 |
| A30 | Paket Wedding Standart Rumah | 0,066667 | 0,025000 | 0,120000 | 0,040000 | 0,160000 | 0,033333 | 0,160000 |
| A31 | Paket Wedding Gedung Luxury | 0,040000 | 0,025000 | 0,150000 | 0,040000 | 0,200000 | 0,033333 | 0,200000 |
| A32 | Paket Wedding Rumah | 0,040000 | 0,025000 | 0,120000 | 0,040000 | 0,160000 | 0,033333 | 0,160000 |
| **AI** | **Ideal** | 0,200000 | 0,100000 | 0,150000 | 0,100000 | 0,200000 | 0,050000 | 0,200000 |

### Langkah 5: Perhitungan Jumlah Terbobot ($S_i$) dan Derajat Utilitas ($K_i^-$, $K_i^+$)
Rumus:
- $\displaystyle S_i = \sum_{j=1}^{7} v_{ij}$
- Dari tabel normalisasi terbobot diperoleh nilai referensi:
  - $\displaystyle S_{AAI} = 0{,}040000 + 0{,}025000 + 0{,}090000 + 0{,}020000 + 0{,}120000 + 0{,}033333 + 0{,}120000 = \mathbf{0,448333}$ (pecahan eksak: $\frac{269}{600}$)
  - $\displaystyle S_{AI} = 0{,}200000 + 0{,}100000 + 0{,}150000 + 0{,}100000 + 0{,}200000 + 0{,}050000 + 0{,}200000 = \mathbf{1,000000}$
- Derajat Utilitas terhadap Anti-Ideal: $\displaystyle K_i^- = \frac{S_i}{S_{AAI}}$
- Derajat Utilitas terhadap Ideal: $\displaystyle K_i^+ = \frac{S_i}{S_{AI}}$

### Langkah 6: Fungsi Utilitas ($f(K_i^+)$, $f(K_i^-)$) dan Nilai Utilitas Akhir ($f(K_i)$)
Rumus:
- $\displaystyle f(K_i^+) = \frac{K_i^-}{K_i^+ + K_i^-}$
- $\displaystyle f(K_i^-) = \frac{K_i^+}{K_i^+ + K_i^-}$
- $\displaystyle f(K_i) = \frac{K_i^+ + K_i^-}{1 + \frac{1 - f(K_i^+)}{f(K_i^+)} + \frac{1 - f(K_i^-)}{f(K_i^-)}}$

Catatan matematis: Karena $K_i^-$ dan $K_i^+$ sama-sama proporsional terhadap $S_i$, nilai $f(K_i^+)$ dan $f(K_i^-)$ bernilai konstan untuk seluruh alternatif:
- $f(K_i^+) = \frac{S_{AI}}{S_{AI} + S_{AAI}} = \frac{1}{1 + 269/600} = \frac{600}{869} = \mathbf{0,690449}$
- $f(K_i^-) = \frac{S_{AAI}}{S_{AI} + S_{AAI}} = \frac{269/600}{1 + 269/600} = \frac{269}{869} = \mathbf{0,309551}$

| Kode | Alternatif | $S_i$ | $K_i^-$ | $K_i^+$ | $f(K_i^+)$ | $f(K_i^-)$ | $f(K_i)$ (Skor Akhir) |
| :---: | :--- | :---: | :---: | :---: | :---: | :---: | :---: |
| A1 | Medium Pakage | 0,690000 | 1,539033 | 0,690000 | 0,690449 | 0,309551 | **0,605910** |
| A2 | Mini Pakage | 0,636667 | 1,420074 | 0,636667 | 0,690449 | 0,309551 | **0,559077** |
| A3 | Lux Pakage | 0,800000 | 1,784387 | 0,800000 | 0,690449 | 0,309551 | **0,702505** |
| A4 | Wedding Silver Pakage | 0,713333 | 1,591078 | 0,713333 | 0,690449 | 0,309551 | **0,626400** |
| A5 | Standart Pakage | 0,660000 | 1,472119 | 0,660000 | 0,690449 | 0,309551 | **0,579567** |
| A6 | Akad Pakage | 0,710000 | 1,583643 | 0,710000 | 0,690449 | 0,309551 | **0,623473** |
| A7 | Bundling 1 | 0,763333 | 1,702602 | 0,763333 | 0,690449 | 0,309551 | **0,670307** |
| A8 | Bundling 2 | 0,670000 | 1,494424 | 0,670000 | 0,690449 | 0,309551 | **0,588348** |
| A9 | Bundling 3 | 0,796667 | 1,776952 | 0,796667 | 0,690449 | 0,309551 | **0,699578** |
| A10 | Bundling 4 | 0,666667 | 1,486989 | 0,666667 | 0,690449 | 0,309551 | **0,585421** |
| A11 | Bundling 5 | 0,653333 | 1,457249 | 0,653333 | 0,690449 | 0,309551 | **0,573712** |
| A12 | Bundling 6 | 0,656667 | 1,464684 | 0,656667 | 0,690449 | 0,309551 | **0,576639** |
| A13 | Bundling 7 | 0,546667 | 1,219331 | 0,546667 | 0,690449 | 0,309551 | **0,480045** |
| A14 | Bundling 8 | 0,626667 | 1,397770 | 0,626667 | 0,690449 | 0,309551 | **0,550295** |
| A15 | Bundling 9 | 0,656667 | 1,464684 | 0,656667 | 0,690449 | 0,309551 | **0,576639** |
| A16 | Bundling 10 | 0,840000 | 1,873606 | 0,840000 | 0,690449 | 0,309551 | **0,737630** |
| A17 | Bundling 11 | 0,700000 | 1,561338 | 0,700000 | 0,690449 | 0,309551 | **0,614692** |
| A18 | Bundling 12 | 0,840000 | 1,873606 | 0,840000 | 0,690449 | 0,309551 | **0,737630** |
| A19 | Bundling 13 | 0,700000 | 1,561338 | 0,700000 | 0,690449 | 0,309551 | **0,614692** |
| A20 | Bundling 14 | 0,740000 | 1,650558 | 0,740000 | 0,690449 | 0,309551 | **0,649817** |
| A21 | Akad Pakage | 0,860000 | 1,918216 | 0,860000 | 0,690449 | 0,309551 | **0,755193** |
| A22 | Wedding Pakage 1 | 0,816667 | 1,821561 | 0,816667 | 0,690449 | 0,309551 | **0,717140** |
| A23 | Wedding Pakage 2 | 0,816667 | 1,821561 | 0,816667 | 0,690449 | 0,309551 | **0,717140** |
| A24 | Akad + Resepsi Pakage | 0,903333 | 2,014870 | 0,903333 | 0,690449 | 0,309551 | **0,793245** |
| A25 | Akad Intimate Pakage | 0,760000 | 1,695167 | 0,760000 | 0,690449 | 0,309551 | **0,667380** |
| A26 | Wedding Pakage 3 | 0,656667 | 1,464684 | 0,656667 | 0,690449 | 0,309551 | **0,576639** |
| A27 | Prewedding Pakage | 0,920000 | 2,052045 | 0,920000 | 0,690449 | 0,309551 | **0,807881** |
| A28 | Wedding Pakage 1 | 0,615000 | 1,371747 | 0,615000 | 0,690449 | 0,309551 | **0,540051** |
| A29 | Wedding Pakage 2 | 0,598333 | 1,334572 | 0,598333 | 0,690449 | 0,309551 | **0,525415** |
| A30 | Paket Wedding Standart Rumah | 0,605000 | 1,349442 | 0,605000 | 0,690449 | 0,309551 | **0,531269** |
| A31 | Paket Wedding Gedung Luxury | 0,688333 | 1,535316 | 0,688333 | 0,690449 | 0,309551 | **0,604447** |
| A32 | Paket Wedding Rumah | 0,578333 | 1,289963 | 0,578333 | 0,690449 | 0,309551 | **0,507852** |

---
## 6. Perankingan Akhir (Hasil Akurat vs Dokumen PDF Lama)
Alternatif diurutkan dari nilai $f(K_i)$ **terbesar hingga terkecil**. Untuk paket yang memiliki skor $f(K_i)$ persis sama (*tie*), diberikan nomor peringkat bersama menggunakan metode *standard competition ranking* (misal peringkat 4 bersama, dilanjutkan peringkat 6).

| Peringkat | Kode | Nama Paket (Alternatif) | Harga | Biaya Tambahan | $S_i$ | $f(K_i)$ | Peringkat PDF Lama | Keterangan |
| :---: | :---: | :--- | ---: | ---: | :---: | :---: | :---: | :--- |
| **1** | A27 | Prewedding Pakage | Rp2.100.000 | Rp150.000 | 0,920000 | **0,807881** | #22 | Unik |
| **2** | A24 | Akad + Resepsi Pakage | Rp2.000.000 | Rp100.000 | 0,903333 | **0,793245** | #24 | Unik |
| **3** | A21 | Akad Pakage | Rp1.500.000 | Rp100.000 | 0,860000 | **0,755193** | #28 | Unik |
| **4** | A16 | Bundling 10 | Rp2.500.000 | Rp100.000 | 0,840000 | **0,737630** | #29 | Seri (nilai identik) |
| **4** | A18 | Bundling 12 | Rp2.000.000 | Rp100.000 | 0,840000 | **0,737630** | #29 | Seri (nilai identik) |
| **6** | A22 | Wedding Pakage 1 | Rp3.500.000 | Rp200.000 | 0,816667 | **0,717140** | #14 | Seri (nilai identik) |
| **6** | A23 | Wedding Pakage 2 | Rp4.000.000 | Rp200.000 | 0,816667 | **0,717140** | #14 | Seri (nilai identik) |
| **8** | A3 | Lux Pakage | Rp13.000.000 | Rp150.000 | 0,800000 | **0,702505** | #7 | Unik |
| **9** | A9 | Bundling 3 | Rp12.000.000 | Rp150.000 | 0,796667 | **0,699578** | #16 | Unik |
| **10** | A7 | Bundling 1 | Rp9.500.000 | Rp200.000 | 0,763333 | **0,670307** | #12 | Unik |
| **11** | A25 | Akad Intimate Pakage | Rp3.200.000 | Rp100.000 | 0,760000 | **0,667380** | #26 | Unik |
| **12** | A20 | Bundling 14 | Rp5.000.000 | Rp100.000 | 0,740000 | **0,649817** | #27 | Unik |
| **13** | A4 | Wedding Silver Pakage | Rp16.000.000 | Rp150.000 | 0,713333 | **0,626400** | #10 | Unik |
| **14** | A6 | Akad Pakage | Rp4.000.000 | Rp150.000 | 0,710000 | **0,623473** | #25 | Unik |
| **15** | A17 | Bundling 11 | Rp3.500.000 | Rp100.000 | 0,700000 | **0,614692** | #29 | Seri (nilai identik) |
| **15** | A19 | Bundling 13 | Rp4.000.000 | Rp100.000 | 0,700000 | **0,614692** | #29 | Seri (nilai identik) |
| **17** | A1 | Medium Pakage | Rp12.000.000 | Rp150.000 | 0,690000 | **0,605910** | #19 | Unik |
| **18** | A31 | Paket Wedding Gedung Luxury | Rp21.000.000 | Rp250.000 | 0,688333 | **0,604447** | #1 | Unik |
| **19** | A8 | Bundling 2 | Rp10.500.000 | Rp150.000 | 0,670000 | **0,588348** | #20 | Unik |
| **20** | A10 | Bundling 4 | Rp15.000.000 | Rp200.000 | 0,666667 | **0,585421** | #9 | Unik |
| **21** | A5 | Standart Pakage | Rp11.000.000 | Rp150.000 | 0,660000 | **0,579567** | #21 | Unik |
| **22** | A12 | Bundling 6 | Rp19.000.000 | Rp200.000 | 0,656667 | **0,576639** | #2 | Seri (nilai identik) |
| **22** | A15 | Bundling 9 | Rp26.500.000 | Rp200.000 | 0,656667 | **0,576639** | #2 | Seri (nilai identik) |
| **22** | A26 | Wedding Pakage 3 | Rp15.000.000 | Rp200.000 | 0,656667 | **0,576639** | #11 | Seri (nilai identik) |
| **25** | A11 | Bundling 5 | Rp17.000.000 | Rp150.000 | 0,653333 | **0,573712** | #13 | Unik |
| **26** | A2 | Mini Pakage | Rp10.000.000 | Rp150.000 | 0,636667 | **0,559077** | #23 | Unik |
| **27** | A14 | Bundling 8 | Rp23.500.000 | Rp200.000 | 0,626667 | **0,550295** | #5 | Unik |
| **28** | A28 | Wedding Pakage 1 | Rp10.000.000 | Rp250.000 | 0,615000 | **0,540051** | #17 | Unik |
| **29** | A30 | Paket Wedding Standart Rumah | Rp10.000.000 | Rp250.000 | 0,605000 | **0,531269** | #18 | Unik |
| **30** | A29 | Wedding Pakage 2 | Rp15.000.000 | Rp250.000 | 0,598333 | **0,525415** | #8 | Unik |
| **31** | A32 | Paket Wedding Rumah | Rp23.000.000 | Rp250.000 | 0,578333 | **0,507852** | #4 | Unik |
| **32** | A13 | Bundling 7 | Rp21.500.000 | Rp200.000 | 0,546667 | **0,480045** | #5 | Unik |

### Analisis Hasil Perankingan yang Benar:
- **Peringkat 1 — `A27` (`Prewedding Pakage`):** Harga sangat ekonomis (`Rp2.100.000`, skor Cost = 1), Biaya Tambahan rendah (`Rp150.000`), Risiko Kerusakan `Rendah`, serta unggul di kriteria Benefit utama: Fleksibilitas `Sangat Baik`, Kualitas Hasil `Sangat Baik`, dan Estetika & Desain `Sangat Baik` ($f(K_{27}) = 0{,}807881$).
- **Peringkat 2 — `A24` (`Akad + Resepsi Pakage`):** Harga sangat terjangkau (`Rp2.000.000`), Biaya Tambahan paling rendah (`Rp100.000`), Kelengkapan & Fleksibilitas `Sangat Lengkap / Sangat Baik` ($f(K_{24}) = 0{,}793245$).
- **Peringkat 3 — `A21` (`Akad Pakage`):** Harga termurah (`Rp1.500.000`), Biaya Tambahan terendah (`Rp100.000`), Fleksibilitas `Sangat Baik`, Risiko `Rendah` ($f(K_{21}) = 0{,}755193$).
- **Paket Kelas Premium Terbaik — `A3` (`Lux Pakage`, Peringkat 8):** Meskipun harganya `Rp13.000.000`, seluruh kriteria Benefit mendapat nilai sempurna (5) dan Risiko `Rendah`, sehingga tetap masuk 10 besar ($f(K_3) = 0{,}702505$).
- **Peringkat Terbawah (32) — `A13` (`Bundling 7`):** Harga mahal (`Rp21.500.000`, Cost = 5), Biaya Tambahan `Rp200.000`, serta kualitas hasil (`Cukup`) dan estetika (`Cukup Baik`) paling rendah di antara seluruh paket ($f(K_{13}) = 0{,}480045$).

---
## 7. Panduan Implementasi ke Sistem E-Commerce
Agar metode MARCOS berjalan akurat, dinamis, dan bebas bug pada sistem *e-commerce* Anda, perhatikan 4 hal berikut:

### 7.1. Gunakan ID Unik (`kode` / `product_id`), Jangan Hanya Nama Paket
Karena terdapat duplikasi nama (`Akad Pakage`, `Wedding Pakage 1`, `Wedding Pakage 2`), database produk harus membedakan item berdasarkan `id` / `kode` (`A1`–`A32`), bukan `name`.

### 7.2. Opsi Menggunakan Nominal Rupiah Langsung untuk `C1` dan `C2` (Opsional untuk E-Commerce)
Pada dokumen proposal, `C1` (Harga) dan `C2` (Biaya Tambahan) dikelompokkan terlebih dahulu ke dalam 5 interval sub-kriteria (1–5). Jika di sistem *e-commerce* Anda ingin memakai **nominal Rupiah asli** secara langsung tanpa pengelompokan interval (agar `Rp1.500.000` lebih unggul dari `Rp2.500.000` meskipun sama-sama di bawah `Rp3.000.000`), berikut perbandingan Top 10 hasilnya dengan rumus MARCOS Cost $n_{ij} = \frac{\min X}{X_{ij}}$ ($S_{AI}=1{,}000000$, $S_{AAI}=0{,}434654$):

| Peringkat (Nominal Asli) | Kode | Nama Paket | Harga | Biaya Tambahan | $f(K_i)$ (Nominal Asli) | Peringkat (Sub-Kriteria 1–5) |
| :---: | :---: | :--- | ---: | ---: | :---: | :---: |
| **1** | A27 | Prewedding Pakage | Rp2.100.000 | Rp150.000 | 0,777180 | #1 |
| **2** | A21 | Akad Pakage | Rp1.500.000 | Rp100.000 | 0,759928 | #3 |
| **3** | A24 | Akad + Resepsi Pakage | Rp2.000.000 | Rp100.000 | 0,754037 | #2 |
| **4** | A22 | Wedding Pakage 1 | Rp3.500.000 | Rp200.000 | 0,723741 | #6 |
| **5** | A23 | Wedding Pakage 2 | Rp4.000.000 | Rp200.000 | 0,714273 | #6 |
| **6** | A18 | Bundling 12 | Rp2.000.000 | Rp100.000 | 0,698073 | #4 |
| **7** | A3 | Lux Pakage | Rp13.000.000 | Rp150.000 | 0,697847 | #8 |
| **8** | A9 | Bundling 3 | Rp12.000.000 | Rp150.000 | 0,681873 | #9 |
| **9** | A16 | Bundling 10 | Rp2.500.000 | Rp100.000 | 0,671564 | #4 |
| **10** | A25 | Akad Intimate Pakage | Rp3.200.000 | Rp100.000 | 0,666042 | #11 |

### 7.3. Fungsi TypeScript MARCOS Siap Pakai untuk Sistem E-Commerce
```typescript
export type CriterionType = 'cost' | 'benefit';

export interface CriterionConfig {
  code: 'C1' | 'C2' | 'C3' | 'C4' | 'C5' | 'C6' | 'C7';
  name: string;
  type: CriterionType;
  weight: number;
}

export interface PackageScoreInput {
  code: string;
  name: string;
  values: number[]; // [C1, C2, C3, C4, C5, C6, C7]
}

export const WEDDING_CRITERIA: CriterionConfig[] = [
  { code: 'C1', name: 'Harga', type: 'cost', weight: 0.20 },
  { code: 'C2', name: 'Biaya Tambahan', type: 'cost', weight: 0.10 },
  { code: 'C3', name: 'Kelengkapan', type: 'benefit', weight: 0.15 },
  { code: 'C4', name: 'Fleksibilitas Layanan', type: 'benefit', weight: 0.10 },
  { code: 'C5', name: 'Kualitas Hasil', type: 'benefit', weight: 0.20 },
  { code: 'C6', name: 'Risiko Kerusakan', type: 'cost', weight: 0.05 },
  { code: 'C7', name: 'Estetika dan Desain', type: 'benefit', weight: 0.20 },
];

export function mapSubCriteriaValues(raw: {
  harga: number;
  biayaTambahan: number;
  kelengkapan: string;
  fleksibilitas: string;
  kualitas: string;
  risiko: string;
  estetika: string;
}): number[] {
  // Cost C1: 1 = termurah (terbaik), 5 = termahal (terburuk)
  const c1 = raw.harga <= 3_000_000 ? 1
    : raw.harga <= 7_000_000 ? 2
    : raw.harga <= 12_000_000 ? 3
    : raw.harga <= 18_000_000 ? 4 : 5;

  // Cost C2: 1 = termurah (terbaik), 5 = termahal (terburuk)
  const c2 = raw.biayaTambahan <= 100_000 ? 1
    : raw.biayaTambahan <= 150_000 ? 2
    : raw.biayaTambahan <= 200_000 ? 3
    : raw.biayaTambahan <= 250_000 ? 4 : 5;

  const benefit5 = (v: string, map: Record<string, number>) => map[v] ?? 1;
  const c3 = benefit5(raw.kelengkapan, {
    'Sangat Lengkap': 5, 'Lengkap': 4, 'Cukup Lengkap': 3, 'Kurang Lengkap': 2, 'Tidak Lengkap': 1,
  });
  const std5: Record<string, number> = {
    'Sangat Baik': 5, 'Baik': 4, 'Cukup Baik': 3, 'Cukup': 3, 'Kurang Baik': 2, 'Tidak Baik': 1,
  };
  const c4 = benefit5(raw.fleksibilitas, std5);
  const c5 = benefit5(raw.kualitas, std5);
  // Cost C6: 1 = sangat rendah (terbaik), 5 = sangat tinggi (terburuk)
  const c6 = benefit5(raw.risiko, {
    'Sangat Rendah': 1, 'Rendah': 2, 'Sedang': 3, 'Tinggi': 4, 'Sangat Tinggi': 5,
  });
  const c7 = benefit5(raw.estetika, std5);
  return [c1, c2, c3, c4, c5, c6, c7];
}

export function calculateMARCOS(items: PackageScoreInput[], criteria = WEDDING_CRITERIA) {
  const m = items.length;
  const n = criteria.length;
  if (m === 0) return [];

  // 1. Solusi Ideal (AI) dan Anti-Ideal (AAI) dari seluruh alternatif
  const AI = criteria.map((c, j) => {
    const col = items.map(item => item.values[j]);
    return c.type === 'benefit' ? Math.max(...col) : Math.min(...col);
  });
  const AAI = criteria.map((c, j) => {
    const col = items.map(item => item.values[j]);
    return c.type === 'benefit' ? Math.min(...col) : Math.max(...col);
  });

  // 2. Normalisasi & Normalisasi Terbobot untuk AI dan AAI
  const nAI = criteria.map(() => 1);
  const nAAI = criteria.map((c, j) => c.type === 'benefit' ? AAI[j] / AI[j] : AI[j] / AAI[j]);
  const S_AI = criteria.reduce((acc, c, j) => acc + c.weight * nAI[j], 0);
  const S_AAI = criteria.reduce((acc, c, j) => acc + c.weight * nAAI[j], 0);

  // 3. Hitung tiap alternatif
  const results = items.map(item => {
    const normalized = criteria.map((c, j) =>
      c.type === 'benefit' ? item.values[j] / AI[j] : AI[j] / item.values[j]
    );
    const weighted = criteria.map((c, j) => c.weight * normalized[j]);
    const Si = weighted.reduce((a, b) => a + b, 0);
    const KiMinus = Si / S_AAI;
    const KiPlus = Si / S_AI;
    const fKiPlus = KiMinus / (KiPlus + KiMinus);
    const fKiMinus = KiPlus / (KiPlus + KiMinus);
    const fKi = (KiPlus + KiMinus) / (1 + (1 - fKiPlus) / fKiPlus + (1 - fKiMinus) / fKiMinus);
    return { code: item.code, name: item.name, normalized, weighted, Si, KiMinus, KiPlus, fKiPlus, fKiMinus, fKi };
  });

  // 4. Urutkan descending berdasarkan f(Ki) dan tentukan ranking (mendukung tie)
  results.sort((a, b) => b.fKi - a.fKi);
  let prevScore: number | null = null;
  let prevRank = 0;
  return results.map((res, idx) => {
    const rank = prevScore !== null && Math.abs(res.fKi - prevScore) < 1e-9 ? prevRank : idx + 1;
    prevScore = res.fKi;
    prevRank = rank;
    return { ...res, rank };
  });
}
```
