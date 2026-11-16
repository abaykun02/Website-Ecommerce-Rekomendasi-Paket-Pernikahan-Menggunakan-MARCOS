# DATABASE & ANALISIS LENGKAP PAKET PERNIKAHAN BERDASARKAN VENDOR
> **File Dokumentasi Terstruktur untuk AI & Integrasi Sistem**  
> Sumber data: Ekstraksi gambar 1 s/d 27 dari folder `/public/assets/aistudio/`  
> Format: Markdown (.md) ramah parsing LLM, RAG, dan database SQL/NoSQL.

---

## 📌 Ringkasan Eksekutif & Statistik Data
- **Total Gambar yang Dianalisis**: 23 file gambar asli yang tersedia di direktori (`1` s/d `16`, `18` s/d `24`).
- **Catatan Nomor Gambar**:
  - Gambar 1 sampai 16: **Tersedia**
  - Gambar 17: **Tidak ada di folder / tidak terunggah**
  - Gambar 18 sampai 24: **Tersedia**
  - Gambar 25, 26, 27: **Tidak ada di folder / tidak terunggah**
- **Jumlah Vendor yang Teridentifikasi**: 5 Entitas / Vendor:
  1. **Syarifah Make Up** (Medan Batang Kuis)
  2. **Dyraaerlia Makeup** (Tanjung Morawa)
  3. **Adilla Makeup**
  4. **Adilla Makeup x Evi Wedding Galery** (Kolaborasi)
  5. **Mecca Gallery**
- **Rentang Harga Layanan**:
  - Layanan MUA Personal (Wisuda/Bridesmaid/Lamaran): Rp 200.000 – Rp 400.000
  - Paket Akad / Intimate: Rp 1.500.000 – Rp 2.500.000
  - Paket Prewedding (Studio/Foto/Gown/MUA): Rp 2.100.000
  - Paket Makeup & Attire Resepsi: Rp 2.000.000 – Rp 5.000.000
  - Paket Wedding Lengkap (Tenda, Pelaminan, MUA, Baju, Foto, Sound/Kibot): Rp 9.500.000 – Rp 26.500.000

---

## 🗂️ TABEL PEMETAAN CEPAT GAMBAR 1 - 27 (AI LOOKUP TABLE)

| No. Gambar | Nama File | Nama Vendor | Kategori Layanan | Nama Paket Tertera | Harga (IDR) | Status File |
|:---:|:---|:---|:---|:---|:---|:---:|
| **1** | `1_69f184a4.jpeg` | Syarifah make up | Wedding Lengkap | Package 4, Paket 5, Paket 6 | Rp 15.000.000 / Rp 17.000.000 / Rp 19.000.000 | Tersedia |
| **2** | `2_213162c7.jpeg` | Syarifah make up | Akad & Mini Wedding | Paket Pernikahan (Dekor, Dok, MUA) | Rp 2.500.000 (2,5 JT) | Tersedia |
| **3** | `3_271e5a92.jpeg` | Syarifah make up | MUA & Attire | Makeup Resepsi, Makeup+Attire Non-Prem, Makeup+Attire Prem | Rp 2.000.000 / Rp 4.000.000 / Rp 5.000.000 | Tersedia |
| **4** | `4_7e602730.jpeg` | Syarifah make up | Makeup Service Non-Wedding | Wisuda SMA, Wisuda, Bridesmaid, Lamaran, Prewed | Rp 250.000 – Rp 350.000 | Tersedia |
| **5** | `5_2a452ec6.jpeg` | Syarifah make up | Wedding Lengkap Premium | Paket 7, Paket 8, Paket 9 | Rp 21.500.000 / Rp 23.500.000 / Rp 26.500.000 | Tersedia |
| **6** | `6_cb26f7c3.jpeg` | Syarifah make up | Wedding Lengkap Hemat | Package 1, Paket 2, Paket 3 | Rp 9.500.000 / Rp 10.500.000 / Rp 12.000.000 | Tersedia |
| **7** | `7_48603f15.jpeg` | Dyraaerlia_Makeup | Wedding Lengkap | Paket Wedding Medium | Rp 12.000.000 (12 Jutaan) | Tersedia |
| **8** | `8_3101c7aa.jpeg` | Dyraaerlia_Makeup | Wedding Lengkap | Paket Wedding Mini | Rp 10.000.000 (10 Jutaan) | Tersedia |
| **9** | `9_09d9d45b.jpeg` | Dyraaerlia_Makeup | Wedding Lengkap | Paket Wedding Lux | Rp 13.000.000 (13 Jutaan) | Tersedia |
| **10** | `10_dfe0a07a.jpeg` | Dyraaerlia_Makeup | Wedding Lengkap | Paket Wedding Standart | Rp 11.000.000 (11 Jutaan) | Tersedia |
| **11** | `11_b8a443c6.jpeg` | Adilla Makeup | Akad, Resepsi & MUA Only | Akad Package, Akad+Resepsi, Makeup Only Akad, Makeup Only Akad+Resepsi | Rp 500.000 – Rp 2.000.000 | Tersedia |
| **12** | `12_9f20e751.jpeg` | Adilla Makeup | Makeup Service Non-Wedding | Regular & Premium (Bridesmaid, Graduation, Engagement, Prewed) | Rp 200.000 – Rp 400.000 | Tersedia |
| **13** | `13_f5e80eea.jpeg` | Adilla x Evi Wedding Galery | MUA & Gown Resepsi | Wedding Package Makeup & Gown Akad & Resepsi (1 Hari) | Hubungi Vendor / Paket Khusus | Tersedia |
| **14** | `14_0c19d39c.jpeg` | Mecca Gallery | MUA & Attire | Pretty Gown Premium Exclusive Akad & Resepsi | Hubungi Vendor / Paket Khusus | Tersedia |
| **15** | `15_ac13fd88.jpeg` | Mecca Gallery | Akad Intimate | Paket Akad Intimate (Dekor, Dok, MUA, Attire, Henna) | Hubungi Vendor / Sesuai Tanggal | Tersedia |
| **16** | `16_7b35ab69.jpeg` | Adilla Makeup | Prewedding Promo | Promo Prewedding Package (FG + MUA + Gown) | Rp 2.100.000 | Tersedia |
| **17** | *Tidak ada* | *N/A* | *N/A* | *File tidak tersedia dalam direktori upload* | - | **Absen (Tidak Ada)** |
| **18** | `18_6541d0bd.jpeg` | Dyraaerlia Makeup | Wedding Lengkap | Paket Wedding Silver | Hubungi Vendor / Konsultasi | Tersedia |
| **19** | `19_85a89470.jpeg` | Dyraaerlia Makeup | Wedding Lengkap | Paket Wedding Platinum | Hubungi Vendor / Konsultasi | Tersedia |
| **20** | `20_e2666c70.jpeg` | Dyraaerlia Makeup | Wedding Lengkap | Paket Wedding Gold | Hubungi Vendor / Konsultasi | Tersedia |
| **21** | `21_1394dea5.jpeg` | Dyraaerlia Makeup | Akad Package | Paket Akad (Backdrop, Meja Akad, Cinematic, Foto, MUA) | DP 20% (Hubungi Vendor) | Tersedia |
| **22** | `22_3cadcbad.jpeg` | Dyraaerlia Makeup | Wedding Lengkap | Paket Wedding Diamond | Hubungi Vendor / Konsultasi | Tersedia |
| **23** | `23_9b765204.jpeg` | Adilla Makeup | Wedding Lengkap | Wedding Package (Pelaminan Max 4m, Tenda, MUA, FG) | Hubungi Vendor / Konsultasi | Tersedia |
| **24** | `24_2faba463.jpeg` | Adilla Makeup | Wedding Lengkap | Wedding Package (Pelaminan 6m, Tenda 3 Gang, MUA, FG) | Hubungi Vendor / Konsultasi | Tersedia |
| **25** | *Tidak ada* | *N/A* | *N/A* | *File tidak tersedia dalam direktori upload* | - | **Absen (Tidak Ada)** |
| **26** | *Tidak ada* | *N/A* | *N/A* | *File tidak tersedia dalam direktori upload* | - | **Absen (Tidak Ada)** |
| **27** | *Tidak ada* | *N/A* | *N/A* | *File tidak tersedia dalam direktori upload* | - | **Absen (Tidak Ada)** |

---

## 🏢 BAGIAN I: RINCIAN PAKET BERDASARKAN VENDOR

---

### VENDOR 1: SYARIFAH MAKE UP
- **Kontak & Lokasi**:
  - WhatsApp/Telepon: `085262264549`
  - Instagram: `@SYARIFAH_MAKE_UP`
  - Alamat: `JLN MEDAN BT KUIS, GG HJ INCIK RAITAH`
- **Catatan & Biaya Tambahan Umum (General Notes)**:
  - Akad beda hari: cas Rp 500.000
  - Tambah foto 1 roll: Rp 350.000
  - Kursi tiffany akad: cas Rp 500.000
  - Jawa sasak / paes asli: cas Rp 500.000
  - Melati asli: cas Rp 250.000 – Rp 500.000
  - Tambahan makeup by TEAM: Rp 100.000 – Rp 150.000
  - Biaya transport: berlaku di luar Medan
  - Tambahan baju: Rp 500.000 / pasang
  - Izin PLN ditanggung tuan rumah

#### A. Paket Pernikahan Lengkap (Tenda + Pelaminan + MUA + Dokumentasi)

##### 1. Package 1 (Gambar 6)
- **Harga**: Rp 9.500.000
- **Inklusi**:
  - Pelaminan 5 meter
  - Tenda pelaminan + pentas pelaminan 5 m
  - Tenda tamu 2 gawang sisir
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1 + kursi 100
  - Foto 2 roll
  - Makeup pengantin + 3 attire non premium
  - Makeup ibu hajad 1 orang
  - *Catatan khusus*: Tanpa henna, tanpa video/cinematic, tanpa panel listrik.

##### 2. Paket 2 (Gambar 6)
- **Harga**: Rp 10.500.000
- **Inklusi**:
  - Pelaminan 6 meter
  - Tenda pelaminan + pentas pelaminan 6 m
  - Tenda tamu 3 gawang sisir
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1 + kursi 100
  - Foto 2 roll
  - Makeup pengantin + 3 attire non premium
  - Makeup ibu hajad 1 orang
  - *Catatan khusus*: Tanpa henna, tanpa video/cinematic, tanpa panel listrik.

##### 3. Paket 3 (Gambar 6)
- **Harga**: Rp 12.000.000
- **Inklusi**:
  - Pelaminan 6 meter
  - Set akad
  - Tenda pelaminan + pentas pelaminan 6 m
  - Tenda tamu 4 gawang sisir
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1
  - Kursi 100 + bungkus
  - Lampu
  - Foto 4 roll
  - Makeup pengantin + 3 attire non premium
  - Makeup ibu hajad 1 orang
  - Henna

##### 4. Package 4 (Gambar 1)
- **Harga**: Rp 15.000.000
- **Inklusi**:
  - Pelaminan 6 meter
  - Decor akad dalam ruangan
  - Tenda pelaminan + pentas pelaminan 6 m
  - Tenda tamu 4 gawang sisir
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1 + meja VIP 2
  - Kursi 100 + bungkus
  - Full flooring
  - Lampu
  - Foto 4 roll
  - Makeup pengantin + 3 attire non premium
  - Makeup ibu hajad 1 orang
  - Henna & Softlens

##### 5. Paket 5 (Gambar 1)
- **Harga**: Rp 17.000.000
- **Inklusi**:
  - Pelaminan 6 meter
  - Decor akad dalam rumah
  - Tenda pelaminan + pentas pelaminan 6 m
  - Tenda tamu 6 gawang sisir
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1 + meja VIP 2
  - Kursi 150 + 100 bungkus
  - Full flooring
  - Lampu
  - Foto 4 roll + cinematic
  - Makeup pengantin + 4 attire non premium
  - Makeup ibu hajad 1 orang
  - Henna & Softlens

##### 6. Paket 6 (Gambar 1)
- **Harga**: Rp 19.000.000
- **Inklusi**:
  - Pelaminan 8 meter + altar jalan + cermin gate masuk
  - Decor akad dalam rumah
  - Tenda pelaminan + pentas pelaminan 8 m
  - Tenda tamu 6 gawang sisir
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1 + meja VIP 2
  - Kursi 200 + 150 bungkus
  - Full flooring
  - Kain tutup
  - Lampu + 1 blower
  - Foto 4 roll + cinematic
  - Makeup pengantin + 4 attire non premium
  - Makeup ibu hajad 1 orang
  - Henna, softlens + melati asli

##### 7. Paket 7 (Gambar 5)
- **Harga**: Rp 21.500.000
- **Inklusi**:
  - Pelaminan 8 meter + altar jalan
  - Kursi akad tiffany
  - Lantai kaca depan pelaminan
  - Tenda pelaminan + pentas pelaminan 8 m
  - Tenda tamu 6 tenda matahari
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1 + meja VIP 2
  - Kursi 200 + 150 bungkus
  - Full flooring + red carpet
  - Kain tutup
  - Lampu + 2 blower
  - Foto unlimited + cinematic
  - Makeup pengantin + 4 attire premium
  - Makeup ibu hajad 1 orang
  - Henna, softlens + melati asli
  - Prewed

##### 8. Paket 8 (Gambar 5)
- **Harga**: Rp 23.500.000
- **Inklusi**:
  - Pelaminan 10 meter + altar jalan
  - Kursi akad tiffany
  - Lantai kaca depan pelaminan + gazebo
  - Tenda pelaminan + pentas pelaminan 10 m
  - Tenda tamu 7 tenda matahari
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1 + meja VIP 4
  - Kursi 250 + 150 bungkus
  - Full flooring + red carpet
  - Kain tutup keliling
  - Lampu + 3 blower
  - Foto unlimited + cinematic
  - Makeup pengantin + 4 attire premium
  - Makeup ibu hajad 1 orang
  - Henna, softlens + melati asli
  - Free prewed

##### 9. Paket 9 (Gambar 5)
- **Harga**: Rp 26.500.000
- **Inklusi**:
  - Pelaminan 10 meter + altar jalan
  - Kursi akad tiffany
  - Lantai kaca depan pelaminan + gazebo
  - Fresh flower 20% jalan pengantin
  - Tenda pelaminan + pentas pelaminan 10 m
  - Tenda tamu 8 gawang gelombang
  - Tenda kibot 1 gawang + pentas kibot
  - Bekakas dapur 1 set
  - Meja hidang 2 + meja buah 1 + meja VIP 4
  - Kursi 250 + 150 bungkus
  - Full flooring + red carpet
  - Kain tutup keliling
  - Lampu + 4 blower
  - Foto unlimited + cinematic
  - Makeup pengantin + 4 attire premium
  - Makeup ibu hajad 1 orang
  - Henna, softlens + melati asli
  - Free prewed

#### B. Paket Pernikahan Minimalis (Gambar 2)
- **Nama Paket**: Paket Pernikahan (3-in-1 Dekorasi, Dokumentasi, MUA)
- **Harga**: Rp 2.500.000 (2,5 JT)
- **Inklusi**:
  - **Dekorasi**: Dekorasi indoor ukuran 3x3 meter, bucket bunga, kursi, karpet, meja akad.
  - **Dokumentasi**: Durasi maksimal 3 jam, foto tak terbatas, layanan cepat, cetak foto album.
  - **MUA**: Make up, hijab, attire 1 pasang, softlens.

#### C. Price List Makeup Wedding & Attire (Gambar 3)
1. **Makeup Resepsi**: Rp 2.000.000
   - Makeup wedding + retouch
   - Hijabdo & softlens
   - Free makeup 1 orang
2. **Makeup + Attire (Non-Premium)**: Rp 4.000.000
   - Makeup wedding + retouch & hijabdo
   - 3 pasang attire no premium
   - Softlens, melati asli
   - Free makeup 1 orang
3. **Makeup + Attire (Premium)**: Rp 5.000.000
   - Makeup wedding + retouch & hijabdo
   - 3 pasang attire premium
   - Softlens, melati asli
   - Free makeup 1 orang
- *Ketentuan Tambahan*: Akad beda hari cas 500rb, tambahan baju cas 500rb, adat tertentu cas, paes cas 500rb, tambahan makeup 150rb by TEAM, batas retouch jam 6 sore.

#### D. Price List Makeup Non-Wedding Service (Gambar 4)
- **Makeup Wisuda SMA**: Rp 250.000
- **Makeup Wisuda**: Rp 300.000
- **Makeup Bridesmaid**: Rp 300.000
- **Makeup Lamaran**: Rp 300.000
- **Makeup Prewed**: Rp 350.000
- *Catatan*: No softlens, lokasi tertentu dikenakan cas.

---

### VENDOR 2: DYRAAERLIA MAKEUP
- **Kontak & Lokasi**:
  - WhatsApp: `0813 7605 4211` / `081376054211`
  - Instagram: `@Dyraaerlia_Makeup` / `@dyraaerliaa_makeup`
  - Area Layanan: Area Tanjung Morawa (di luar Tanjung Morawa dikenakan biaya transportasi sesuai jarak)
- **Catatan & Biaya Tambahan Khusus**:
  - Hairdo Jawa sasak: tambahan Rp 350.000
  - Aksesoris adat Aceh & Karo: dikenakan cas harga
  - Penambahan baju premium: Rp 350.000 / baju / gaun

#### A. Paket Wedding Seri Nominal (Gambar 7, 8, 9, 10)

##### 1. Paket Wedding Mini (Gambar 8)
- **Harga**: Rp 10.000.000 (Hanya 10 Jutaan)
- **Inklusi**:
  - Tenda 5 gawang, pentas kibot, pentas pelaminan
  - Kursi 150 balut 50
  - Alat dapur
  - Pelaminan 6 meter
  - Meja VIP 2, pondok es & kue, tenda terima tamu
  - Kursi akad depan pelaminan
  - Makeup akad & resepsi di hari yang sama
  - Busana 4 pasang baju standart
  - Foto 2 roll
  - Free Makeup 1 orang / softlens

##### 2. Paket Wedding Standart (Gambar 10)
- **Harga**: Rp 11.000.000 (Hanya 11 Jutaan)
- **Inklusi**:
  - Tenda 6 gawang, pentas kibot, pentas pelaminan
  - Kursi 150 balut 100
  - Alat dapur
  - Pelaminan 6 meter
  - Meja VIP 3, pondok es & kue, tenda terima tamu
  - Kursi akad depan pelaminan
  - Makeup akad & resepsi di hari yang sama
  - Busana 4 pasang baju standart
  - Foto 2 roll
  - Free Makeup 1 orang / softlens

##### 3. Paket Wedding Medium (Gambar 7)
- **Harga**: Rp 12.000.000 (Hanya 12 Jutaan)
- **Inklusi**:
  - Tenda 6 gawang, pentas kibot, pentas pelaminan
  - Kursi 200 balut 100
  - Alat dapur
  - Pelaminan 8 meter
  - Meja VIP 3, pondok es & kue, tenda terima tamu
  - Kursi akad depan pelaminan
  - Makeup akad & resepsi di hari yang sama
  - Busana 4 pasang baju premium
  - Foto 2 roll
  - Free Makeup 1 orang / softlens

##### 4. Paket Wedding Lux (Gambar 9)
- **Harga**: Rp 13.000.000 (Hanya 13 Jutaan)
- **Inklusi**:
  - Tenda 7 gawang, pentas kibot, pentas pelaminan
  - Kursi 200 balut 100
  - Alat dapur
  - Pelaminan 8 meter
  - Meja VIP 3, pondok es & kue, tenda terima tamu
  - Kursi akad depan pelaminan
  - Makeup akad & resepsi di hari yang sama
  - Busana 4 pasang baju premium
  - Foto 4 roll
  - Free Makeup 1 orang / softlens
  - Lampu & izin PLN

#### B. Paket Wedding Seri Logam & Batu Mulia (Gambar 18, 19, 20, 22)

##### 5. Paket Wedding Silver (Gambar 18)
- **Inklusi**:
  - Tenda 5 plong (Matahari mix sisir)
  - Pentas pelaminan 8 meter, pentas keyboard
  - Full cover background putih
  - Meja VIP 2, meja hidangan 2, pondok buah 1
  - Kursi + balut 150
  - Alat dapur lengkap + panstove biasa/petak 2 set
  - Tenda terima tamu + meja 1, panel listrik
  - Pelaminan 8 meter, sofa pengantin lengkap 5 pax
  - Standing flower pelaminan, taman pelaminan artificial, pintu masuk
  - Dekorasi akad informasi (no request), gerbang masuk, welcome sign
  - Unlimited photoshoot (s/d pukul 20.00 WIB), cetak album magnetik, file CD video liputan
  - Makeup pengantin, baju pengantin 3 pasang (mix 1 adat)
  - Free makeup keluarga 1 orang, melati asli jika perlu, free softlens, free red henna

##### 6. Paket Wedding Platinum (Gambar 19)
- **Inklusi**:
  - Tenda 6 plong (Matahari)
  - Pentas pelaminan & pentas keyboard
  - Full flooring (lantai papan), full cover background
  - Meja VIP 2, meja hidangan 2, pondok buah 1
  - Kursi + balut 150
  - Alat dapur lengkap + panstove biasa 2 set
  - Tenda terima tamu + meja 1, panel listrik, blower 2 pcs
  - Pelaminan 8 meter, sofa pengantin lengkap, standing flower 2 pcs
  - Kursi dan meja akad depan, pintu masuk
  - Unlimited photoshoot, cetak album exclusive, file CD video liputan
  - Makeup pengantin + 2x retouch
  - Baju pengantin 3 pasang (mix 1 adat)
  - Free makeup keluarga 1 orang, melati asli jika perlu, free softlens, free red henna

##### 7. Paket Wedding Gold (Gambar 20)
- **Inklusi**:
  - Tenda tamu 4, tenda keyboard, terpal masak
  - Pentas pelaminan & pentas keyboard
  - Meja pondok 2, meja hidangan 2, meja VIP 2
  - Alat dapur, panstop 2 set, tenda terima tamu 1
  - Kursi + balut 150
  - Pelaminan 6 - 8 meter, photobooth, gapura pintu masuk
  - Lampu hiasan pelaminan, dekorasi akad nikah, dekor kamar pengantin, kotak uang
  - Makeup pengantin + retouch makeup
  - Baju pengantin 3 pasang (mix 1 adat)
  - Henna, makeup orang tua 1, melati asli jika perlu
  - Acc adat tertentu, free sewa crown, free softlens

##### 8. Paket Wedding Diamond (Gambar 22)
- **Inklusi**:
  - **Tenda**: Tenda biasa 4 gang, lantai karpet / flooring, pentas pelaminan 6 meter, tenda & pentas hiburan, meja prasmanan 2 set, pondok buah 1, meja VIP 3 pcs, kursi 200 balut 150, alat dapur panstop biasa & tungku, tenda cafe & meja souvenir, tenda dapur, ijin PLN & pasang lampu, blower air 2 set.
  - **Dekor**: Pelaminan 6 meter, hias kain kamar, dekor akad / set akad kursi + meja depan pelaminan.
  - **MUA & Attire**: Makeup resepsi, baju 4 pasang, acc adat, softlens normal, melati asli jika diperlukan, free makeup mom bride 1, free henna maroon, free mahar bingkai simple.
  - **Fotografi**: Foto 4 roll, video syuting 2 jam.

#### C. Paket Akad Dyraaerlia (Gambar 21)
- **Inklusi**:
  - Backdrop 2-3 meter, meja akad, lighting standart, welcome sign
  - Video cinematic, foto unlimited (durasi 5 jam kerja), cetak magazine
  - Makeup akad, baju sepasang (akad), free henna
- **Ketentuan**:
  - Booking tanggal DP 20%
  - DP = Booking
  - Pelunasan saat pemasangan dekorasi
  - Bebas pilih model dekorasi

---

### VENDOR 3: ADILLA MAKEUP
- **Kontak & Akun**:
  - WhatsApp: `+6283890749461` / `+62 838-9074-9461`
  - Instagram: `@ADILLAAMAKEUP` / `@adillaamakeup`
- **Ketentuan Umum**:
  - Homeservice dikenakan fee transportasi sesuai jarak
  - Hairdo tambahan: Rp 160.000 – Rp 200.000

#### A. Wedding Price List (Gambar 11)
1. **Akad Package**: Rp 1.500.000 (1,5 JT)
   - Makeup service
   - 1 set gown untuk CPP & CPW
   - Clean hijabdo
   - Melati imitasi premium
   - Softlens
2. **Akad + Resepsi Package**: Rp 2.000.000 (2 JT)
   - Makeup service
   - 3 set gown mix adat (1) untuk CPP & CPW
   - Retouch
   - MUA standby sampai jam 5 sore
   - Hijabdo & softlens
   - Makeup family bride 1 orang
   - Melati imitasi premium
3. **Makeup Service for Akad (Makeup Only)**: Rp 500.000 (500K)
   - Include: Softlens, clean hijabdo, melati imitasi premium
4. **Makeup Service for Akad + Resepsi (Makeup Only)**: Rp 700.000 (700K)
   - Include: Retouch, softlens, clean hijabdo, melati imitasi premium, makeup family 1 orang, MUA standby sampai jam 5 sore
- *Notes*: Hairdo fee tambahan 200K, akad & resepsi beda hari cas 100K.

#### B. Pricelist Regular & Premium Makeup Non-Wedding (Gambar 12)
1. **Regular Makeup** (Include bulu mata palsu, clean hijabdo):
   - Bridesmaid/Party: Rp 200.000 (200 K)
   - Graduation/Photoshoot: Rp 230.000 (230 K)
   - Engagement: Rp 300.000 (300 K)
   - Prawedding: Rp 350.000 (350 K)
2. **Premium Makeup** (Include bulu mata palsu, softlens, clean hijabdo, produk premium):
   - Bridesmaid/Party: Rp 250.000 (250 K)
   - Graduation/Photoshoot: Rp 280.000 (280 K)
   - Engagement: Rp 350.000 (350 K)
   - Prawedding: Rp 400.000 (400 K)
- *Notes*: Hairdo by team fee tambahan 160K.

#### C. Promo Prewedding Package (Gambar 16)
- **Harga**: Rp 2.100.000 (Only Rp. 2.100.000 !!)
- **Inklusi 3-in-1**:
  - **Photographer**: 1 cetak 24 inch + frame, 4 cetak 6 inch + frame, 10 foto edit + all photo (GDrive), free pas foto latar biru/merah, 2 session indoor include studio.
  - **MUA**: Makeup service by @adillaamakeup, softlens, hijabdo (hairdo fee tambahan).
  - **Gown**: 1 set premium gown (bebas pilih).

#### D. Paket Wedding Lengkap Adilla (Gambar 23 & 24)

##### 1. Wedding Package Pelaminan Indoor Max 4m (Gambar 23)
- **Dekorasi**: Pelaminan indoor max 4 meter, sofa pengantin 1 pax, standing flowers & lighting, cover dinding area dekor, set meja akad bawah, welcome sign, tenda matahari 4, lampu & kipas angin atas, kursi plastik & cover 100 pcs, meja hidang 1 set, meja snack 1, meja VIP 2, meja penerima tamu 1, panstove petak 1 set, piring sendok garpu 200 pcs, perlengkapan dapur, panel listrik, kotak amplop 1.
- **MUA & Attire**: Makeup full retouch s/d sore, softlens, melati, makeup ibu 1, attire adat & acc 1 pasang, attire nasional 1 pasang (premium), attire nasional 1 pasang (reguler), hijab do, crown, FREE henna pengantin (red).
- **Fg (Fotografi)**: Shoot 08.00 s/d 20.00 WIB, cetak 3 roll, album magnetic, video shooting 2 disk (*not unlimited).

##### 2. Wedding Package Pelaminan 6m & Tenda 3 Gang (Gambar 24)
- **Dekor**: Pelaminan 6 meter, hias kain kamar, dekor akad simple atau set akad depan pelaminan, tenda/pentas pelaminan 6 mtr, tenda tamu 3 gang, tenda & pentas biasa 1 gang, meja prasmanan 2 set, pondok buah 1, meja VIP 3 pcs, kursi 200 balut 100, alat dapur panstop biasa & tungku, tenda cafe & meja souvenir, ijin PLN & pasang lampu, welcome gate.
- **MUA & Attire**: Makeup resepsi, baju 4 pasang, acc adat, softlens normal, melati asli jika diperlukan, free makeup mom bride 1, free henna maroon.
- **Fg (Fotografi)**: Foto 4 roll, video syuting 2 jam.

---

### VENDOR 4: KOLABORASI ADILLA MAKEUP X EVI WEDDING GALERY
- **Akun Kolaborasi**: `@adillaamakeup` x `@eviweddinggalery`
- **Detail Paket (Gambar 13)**:
  - **Nama Paket**: Wedding Package Makeup & Gown Akad & Resepsi (1 Hari)
  - **Inklusi**:
    - Akad + Resepsi di hari yang sama
    - 3 pasang gown pengantin mix adat by `@eviweddinggalery`
    - Makeup + retouch sampai jam 5 sore
    - Free makeup 1 orang (keluarga/ibu)
    - Hijab do & 1 acc
    - Melati asli (jika diperlukan)
    - Free softlens
  - **Catatan**: Aksesoris adat khusus (Aceh, Karo, Palembang) dikenakan charge tambahan.

---

### VENDOR 5: MECCA GALLERY
- **Kontak & Akun**:
  - WhatsApp: `+62 823-8208-6993`
  - Instagram: `@mecca_gallery23`

#### A. Pretty Gown ~ Premium ~ Exclusive (Gambar 14)
- **Layanan**: Makeup & Attire Akad & Resepsi
- **Inklusi**:
  - Makeup akad & resepsi di hari yang sama
  - Free 1 orang makeup mom of bride
  - Retouch 2x
  - Free softlens
  - Melati akad
  - Henna maroon / white
  - 3 pasang attire mix adat
  - 1 acc adat (sunting Aceh dan Karo dikenakan biaya tambahan)

#### B. Paket Akad Intimate (Gambar 15)
- **Inklusi Lengkap**:
  - **Dekorasi**: Dekorasi indoor ukuran 3x3 meter, free mirror sign.
  - **Dokumentasi**: File photo unlimited, edit dan non-edit, cetak album wedding book, cetak photo plus frame ukuran 8x12 inch 1 pcs, file Google Drive pribadi, durasi 4 jam kerja.
  - **MUA**: Make up, hijab do, free softlens.
  - **Attire**: Sepasang baju akad, free hijab + manset.
  - **Henna**: Henna maroon.

---

## 🔍 BAGIAN II: PENJELASAN GAMBAR PER GAMBAR (1 SAMPAI 27)

### 📸 GAMBAR 1
- **File**: `1_69f184a4.jpeg`
- **Vendor**: Syarifah make up (`@SYARIFAH_MAKE_UP` / `085262264549`)
- **Isi Konten**:
  Berisi 3 tingkatan paket pernikahan lengkap:
  1. **Package 4** (Rp 15.000.000): Pelaminan 6m, tenda tamu 4 gawang, tenda kibot, bekakas dapur, kursi 100+bungkus, full flooring, foto 4 roll, MUA + 3 attire non premium, makeup ibu hajad 1 org, henna, soflens.
  2. **Paket 5** (Rp 17.000.000): Pelaminan 6m, tenda tamu 6 gawang, tenda kibot, kursi 150+100 bungkus, full flooring, foto 4 roll + cinematic, MUA + 4 attire non premium, makeup ibu hajad 1 org, henna, soflens.
  3. **Paket 6** (Rp 19.000.000): Pelaminan 8m + altar jalan + cermin gate, tenda tamu 6 gawang, kursi 200+150 bungkus, full flooring, kain tutup, lampu + 1 blower, foto 4 roll + cinematic, MUA + 4 attire non premium, makeup ibu hajad 1 org, henna, soflens + melati asli.
  - *Catatan kaki*: Ketentuan cas beda hari, foto tambahan, kursi tiffany, paes/sasak, melati asli, transport, dan izin PLN.

### 📸 GAMBAR 2
- **File**: `2_213162c7.jpeg`
- **Vendor**: Syarifah make up (`@Syarifah_make_up` / `085262264549`)
- **Isi Konten**:
  - **Nama Paket**: Paket Pernikahan (3-in-1 Mini/Intimate)
  - **Harga**: 2,5 JT (Rp 2.500.000)
  - **Rincian**:
    - Dekorasi indoor ukuran 3x3 meter, bucket bunga, kursi, karpet, meja akad.
    - Dokumentasi foto tak terbatas (maksimal 3 jam), layanan cepat, cetak foto album.
    - MUA: Makeup, hijab, attire 1 pasang, softlens.

### 📸 GAMBAR 3
- **File**: `3_271e5a92.jpeg`
- **Vendor**: Syarifah make up (`syarifah makeup`)
- **Isi Konten**:
  - **Price List Makeup & Attire**:
    1. **Makeup Resepsi**: Rp 2.000.000 (Makeup wedding + retouch, hijabdo, soflens, free makeup 1 org).
    2. **Makeup + Attire (No Premium)**: Rp 4.000.000 (Makeup wedding + retouch, hijabdo, 3 pasang attire non premium, soflens, free makeup 1 org, melati asli).
    3. **Makeup + Attire (Premium)**: Rp 5.000.000 (Makeup wedding + retouch, hijabdo, 3 pasang attire premium, soflens, free makeup 1 org, melati asli).
  - *Catatan kaki*: Cas beda hari 500rb, tambahan baju 500rb, paes 500rb, tambahan makeup 150rb tim, batas retouch jam 18:00 WIB.

### 📸 GAMBAR 4
- **File**: `4_7e602730.jpeg`
- **Vendor**: Syarifah makeup
- **Isi Konten**:
  - **Price List Makeup Non-Wedding (Makeup Service)**:
    - Makeup Wisuda SMA: Rp 250.000
    - Makeup Wisuda: Rp 300.000
    - Makeup Bridesmaid: Rp 300.000
    - Makeup Lamaran: Rp 300.000
    - Makeup Prewed: Rp 350.000
  - *Catatan*: Tanpa softlens, lokasi tertentu dikenakan cas.

### 📸 GAMBAR 5
- **File**: `5_2a452ec6.jpeg`
- **Vendor**: Syarifah make up (`@SYARIFAH_MAKE_UP` / `085262264549`)
- **Isi Konten**:
  Berisi 3 paket tier tertinggi (Sultan / Premium Wedding Package):
  1. **Paket 7** (Rp 21.500.000): Pelaminan 8m + altar jalan, kursi akad tiffany, lantai kaca, tenda matahari 6, kursi 200+150 bungkus, red carpet, lampu + 2 blower, foto unlimited + cinematic, MUA + 4 attire premium, free prewed.
  2. **Paket 8** (Rp 23.500.000): Pelaminan 10m + altar jalan + gazebo, tenda matahari 7, kursi 250+150 bungkus, kain tutup keliling, lampu + 3 blower, foto unlimited + cinematic, MUA + 4 attire premium, free prewed.
  3. **Paket 9** (Rp 26.500.000): Pelaminan 10m + fresh flower 20% jalan pengantin + gazebo, tenda gelombang 8 gawang, kursi 250+150 bungkus, lampu + 4 blower, foto unlimited + cinematic, MUA + 4 attire premium, free prewed.

### 📸 GAMBAR 6
- **File**: `6_cb26f7c3.jpeg`
- **Vendor**: Syarifah make up (`@SYARIFAH_MAKE_UP` / `085262264549`)
- **Isi Konten**:
  Berisi 3 paket tier ekonomis/starter:
  1. **Package 1** (Rp 9.500.000): Pelaminan 5m, tenda tamu 2 gawang sisir, kursi 100, foto 2 roll, MUA + 3 baju non premium, tanpa henna/cinematic/panel listrik.
  2. **Paket 2** (Rp 10.500.000): Pelaminan 6m, tenda tamu 3 gawang sisir, kursi 100, foto 2 roll, MUA + 3 baju non premium, tanpa henna/cinematic/panel listrik.
  3. **Paket 3** (Rp 12.000.000): Pelaminan 6m, set akad, tenda tamu 4 gawang sisir, kursi 100+bungkus, lampu, foto 4 roll, MUA + 3 baju non premium, henna include.

### 📸 GAMBAR 7
- **File**: `7_48603f15.jpeg`
- **Vendor**: Dyraaerlia_Makeup (`0813 7605 4211`)
- **Isi Konten**:
  - **Paket Wedding Medium**: Harga HANYA 12 JUTAAN (Rp 12.000.000).
  - Tenda 6 gawang, pentas kibot, pentas pelaminan, kursi 200 balut 100, alat dapur, pelaminan 8 meter, meja VIP 3, pondok es & kue, tenda terima tamu, kursi akad depan pelaminan, makeup akad & resepsi hari yang sama, busana 4 pasang baju premium, foto 2 roll, free makeup 1 org / softlens.

### 📸 GAMBAR 8
- **File**: `8_3101c7aa.jpeg`
- **Vendor**: Dyraaerlia_Makeup (`0813 7605 4211`)
- **Isi Konten**:
  - **Paket Wedding Mini**: Harga HANYA 10 JUTAAN (Rp 10.000.000).
  - Tenda 5 gawang, pentas kibot & pelaminan, kursi 150 balut 50, pelaminan 6 meter, meja VIP 2, busana 4 pasang baju standart, foto 2 roll, free makeup 1 org / softlens.

### 📸 GAMBAR 9
- **File**: `9_09d9d45b.jpeg`
- **Vendor**: Dyraaerlia_Makeup (`0813 7605 4211`)
- **Isi Konten**:
  - **Paket Wedding Lux**: Harga HANYA 13 JUTAAN (Rp 13.000.000).
  - Tenda 7 gawang, pelaminan 8 meter, kursi 200 balut 100, meja VIP 3, busana 4 pasang premium, foto 4 roll, include lampu & izin PLN, free makeup 1 org / softlens.

### 📸 GAMBAR 10
- **File**: `10_dfe0a07a.jpeg`
- **Vendor**: Dyraaerlia_Makeup (`0813 7605 4211`)
- **Isi Konten**:
  - **Paket Wedding Standart**: Harga HANYA 11 JUTAAN (Rp 11.000.000).
  - Tenda 6 gawang, pelaminan 6 meter, kursi 150 balut 100, meja VIP 3, busana 4 pasang baju standart, foto 2 roll, free makeup 1 org / softlens.

### 📸 GAMBAR 11
- **File**: `11_b8a443c6.jpeg`
- **Vendor**: Adilla Makeup (`@ADILLAAMAKEUP` / `+6283890749461`)
- **Isi Konten**:
  - **Wedding Price List**:
    1. **Akad Package** (Rp 1.500.000 / 1,5 JT): Makeup, 1 set gown CPP & CPW, clean hijabdo, melati imitasi premium, softlens.
    2. **Akad + Resepsi Package** (Rp 2.000.000 / 2 JT): Makeup, 3 set gown mix adat (1), retouch, standby s/d 17:00, hijabdo, softlens, makeup family 1 orang, melati imitasi.
    3. **Makeup Service for Akad (Makeup Only)**: Rp 500.000 (500K).
    4. **Makeup Service for Akad+Resepsi (Makeup Only)**: Rp 700.000 (700K).

### 📸 GAMBAR 12
- **File**: `12_9f20e751.jpeg`
- **Vendor**: Adilla Makeup (`@adillaamakeup` / `+6283890749461`)
- **Isi Konten**:
  - **Pricelist Regular & Premium Makeup**:
    - Regular: Bridesmaid/Party 200K, Graduation 230K, Engagement 300K, Prawedding 350K.
    - Premium: Bridesmaid/Party 250K, Graduation 280K, Engagement 350K, Prawedding 400K.

### 📸 GAMBAR 13
- **File**: `13_f5e80eea.jpeg`
- **Vendor**: Kolaborasi `@adillaamakeup` x `@eviweddinggalery`
- **Isi Konten**:
  - **Wedding Package Makeup & Gown Akad & Resepsi (1 Hari)**:
    - 3 pasang gown pengantin mix adat by `@eviweddinggalery`
    - Makeup + retouch sampai jam 5 sore
    - Free makeup 1 family, hijab do & 1 acc
    - Melati asli jika diperlukan, free softlens
    - Charge khusus adat Aceh, Karo, Palembang.

### 📸 GAMBAR 14
- **File**: `14_0c19d39c.jpeg`
- **Vendor**: Mecca Gallery (`@mecca_gallery23` / `+62 823-8208-6993`)
- **Isi Konten**:
  - **Pretty Gown ~ Premium ~ Exclusive**:
    - Makeup Akad & Resepsi di hari yang sama
    - Free 1 orang makeup mom of bride
    - Retouch 2x, free softlens, melati akad
    - Henna maroon/white
    - 3 pasang attire mix adat, 1 acc adat (sunting Aceh/Karo cas tambahan).

### 📸 GAMBAR 15
- **File**: `15_ac13fd88.jpeg`
- **Vendor**: Mecca Gallery (`@mecca_gallery23` / `+62 823-8208-6993`)
- **Isi Konten**:
  - **Paket Akad Intimate**:
    - Dekorasi indoor 3x3 meter, free mirror sign.
    - Dokumentasi: File foto unlimited (edit & non edit), cetak album wedding book, cetak foto + frame 8x12 inch, GDrive pribadi, 4 jam kerja.
    - MUA: Makeup, hijab do, free softlens.
    - Attire: Sepasang baju akad, free hijab + manset.
    - Henna: Henna maroon.

### 📸 GAMBAR 16
- **File**: `16_7b35ab69.jpeg`
- **Vendor**: Adilla Makeup (`@adillaamakeup`)
- **Isi Konten**:
  - **Promo Prewedding Package**: Harga Only Rp. 2.100.000 !!
  - Photographer: 1 cetak 24" + frame, 4 cetak 6" + frame, 10 foto edit + all photo GDrive, free pas foto latar biru/merah, 2 sesi indoor include studio.
  - MUA: Makeup service by adillaamakeup, softlens, hijabdo.
  - Gown: 1 set premium gown bebas pilih.

### 📸 GAMBAR 17
- **File**: *Tidak ada di direktori upload*
- **Status**: **ABSEN / TIDAK TERSEDIA**
- **Penjelasan**: File bernomor 17 tidak terdapat di berkas arsip yang diunggah oleh pengguna (`/public/assets/aistudio/`).

### 📸 GAMBAR 18
- **File**: `18_6541d0bd.jpeg`
- **Vendor**: Dyraaerlia Makeup (`@dyraaerliaa_makeup` / `081376054211`)
- **Isi Konten**:
  - **Paket Wedding Silver**:
    - Tenda 5 plong (matahari mix sisir), pentas pelaminan 8m, pentas keyboard, full cover background putih.
    - Meja VIP 2, meja hidang 2, pondok buah 1, kursi + balut 150, alat dapur lengkap + panstove 2 set, tenda terima tamu + meja, panel listrik.
    - Pelaminan 8 meter, sofa pengantin 5 pax, standing flower pelaminan, taman pelaminan artificial, pintu masuk, dekor akad info, gerbang masuk, welcome sign.
    - Dokumentasi: Unlimited photoshoot (s/d 20.00 WIB), cetak album magnetik, file CD video liputan.
    - MUA: Makeup pengantin, 3 pasang baju (mix 1 adat), free makeup keluarga 1 orang, melati asli jika perlu, free softlens, free red henna.

### 📸 GAMBAR 19
- **File**: `19_85a89470.jpeg`
- **Vendor**: Dyraaerlia Makeup (`@dyraaerliaa_makeup` / `081376054211`)
- **Isi Konten**:
  - **Paket Wedding Platinum**:
    - Tenda 6 plong (matahari), pentas pelaminan & keyboard, full flooring (lantai papan), full cover background.
    - Kursi + balut 150, meja VIP 2, hidang 2, pondok buah 1, alat dapur & panstove 2 set, tenda terima tamu, panel listrik, blower 2 pcs.
    - Pelaminan 8 meter, sofa pengantin lengkap, standing flower 2 pcs, kursi & meja akad depan, pintu masuk.
    - Dokumentasi: Unlimited photoshoot, cetak album exclusive, file CD video liputan.
    - MUA: Makeup pengantin + 2x retouch, baju 3 pasang (mix 1 adat), free makeup keluarga 1 orang, melati asli, softlens, red henna.

### 📸 GAMBAR 20
- **File**: `20_e2666c70.jpeg`
- **Vendor**: Dyraaerlia Makeup (`@dyraaerliaa_makeup` / `081376054211`)
- **Isi Konten**:
  - **Paket Wedding Gold**:
    - Tenda tamu 4, tenda keyboard, terpal masak, pentas pelaminan & keyboard, meja pondok 2, hidang 2, VIP 2, alat dapur + panstop 2 set.
    - Kursi + balut 150, pelaminan 6-8 meter, photobooth, gapura pintu masuk, lampu hiasan, dekor akad nikah, dekor kamar pengantin, kotak uang.
    - MUA: Makeup pengantin + retouch, baju 3 pasang (mix 1 adat), henna, makeup orang tua 1, melati asli, acc adat tertentu, free sewa crown, free softlens.

### 📸 GAMBAR 21
- **File**: `21_1394dea5.jpeg`
- **Vendor**: Dyraaerlia Makeup (`@dyraaerliaa_makeup` / `081376054211`)
- **Isi Konten**:
  - **Paket Akad**:
    - Backdrop 2-3 meter, meja akad, lighting standart, welcome sign.
    - Cinematic video, foto unlimited (5 jam kerja), cetak magazine.
    - Makeup akad, baju sepasang (akad), free henna.
    - Ketentuan: Booking DP 20%, pelunasan saat pemasangan dekor, bebas pilih model dekorasi.

### 📸 GAMBAR 22
- **File**: `22_3cadcbad.jpeg`
- **Vendor**: Dyraaerlia Makeup (`@dyraaerliaa_makeup` / `081376054211`)
- **Isi Konten**:
  - **Paket Wedding Diamond**:
    - Tenda biasa 4 gang, lantai karpet/flooring, pentas pelaminan 6m, tenda & pentas hiburan, prasmanan 2 set, pondok buah 1, meja VIP 3, kursi 200 balut 150, dapur + panstop + tungku, tenda cafe & meja souvenir, tenda dapur, ijin PLN & pasang lampu, blower air 2 set.
    - Dekor: Pelaminan 6 meter, hias kain kamar, dekor akad kursi + meja depan pelaminan.
    - MUA & Attire: Makeup resepsi, baju 4 pasang, acc adat, softlens normal, melati asli jika perlu, free makeup mom bride 1, free henna maroon, free mahar bingkai simple.
    - Fotografi: Foto 4 roll, video shooting 2 jam.

### 📸 GAMBAR 23
- **File**: `23_9b765204.jpeg`
- **Vendor**: Adilla Makeup (`@adillaamakeup` / `+62 838-9074-9461`)
- **Isi Konten**:
  - **Wedding Package (Pelaminan Indoor Max 4m)**:
    - Dekorasi: Pelaminan indoor max 4m, sofa pengantin 1 pax, standing flowers & lighting, cover dinding dekor, meja akad bawah, welcome sign, tenda matahari 4, lampu & kipas angin atas, kursi plastik cover 100 pcs, meja hidang 1 set, snack 1, VIP 2, meja tamu 1, panstove petak 1 set, piring sendok garpu 200 pcs, perlengkapan dapur, panel listrik, kotak amplop 1.
    - MUA & Attire: Makeup full retouch s/d sore, softlens, melati, makeup ibu 1, baju adat & acc 1 pasang, baju nasional premium 1 pasang, baju nasional reguler 1 pasang, hijab do, crown, FREE henna pengantin red.
    - Fotografi: Shoot 08.00 s/d 20.00 WIB, cetak 3 roll, album magnetic, video shooting 2 disk (*not unlimited).

### 📸 GAMBAR 24
- **File**: `24_2faba463.jpeg`
- **Vendor**: Adilla Makeup (`@adillaamakeup` / `+62 838-9074-9461`)
- **Isi Konten**:
  - **Wedding Package (Pelaminan 6m & Tenda 3 Gang)**:
    - Dekor: Pelaminan 6 meter, hias kain kamar, dekor akad simple atau depan pelaminan, tenda/pentas pelaminan 6m, tenda tamu 3 gang, tenda & pentas biasa 1 gang, meja prasmanan 2 set, pondok buah 1, meja VIP 3, kursi 200 balut 100, dapur + panstop + tungku, tenda cafe & meja souvenir, ijin PLN & pasang lampu, welcome gate.
    - MUA & Attire: Makeup resepsi, baju 4 pasang, acc adat, softlens normal, melati asli jika perlu, free makeup mom bride 1, free henna maroon.
    - Fotografi: Foto 4 roll, video syuting 2 jam.

### 📸 GAMBAR 25, 26, 27
- **File**: *Tidak ada di direktori upload*
- **Status**: **ABSEN / TIDAK TERSEDIA**
- **Penjelasan**: Berkas gambar nomor 25, 26, dan 27 tidak terdapat di dalam direktori penyimpanan aset (`/public/assets/aistudio/`).

---

## 💻 BAGIAN III: STRUKTUR DATA JSON (UNTUK INTEGRASI SISTEM & AI)

```json
{
  "system_info": {
    "generated_for": "Ingestion AI & System Wedding Database",
    "total_images_analyzed": 23,
    "missing_images": [17, 25, 26, 27],
    "vendor_count": 5
  },
  "vendors": [
    {
      "vendor_id": "syarifah_makeup",
      "vendor_name": "Syarifah Make Up",
      "instagram": "@SYARIFAH_MAKE_UP",
      "phone": "085262264549",
      "address": "Jln Medan Bt Kuis, Gg Hj Incik Raitah",
      "image_references": [1, 2, 3, 4, 5, 6]
    },
    {
      "vendor_id": "dyraaerlia_makeup",
      "vendor_name": "Dyraaerlia Makeup",
      "instagram": "@dyraaerliaa_makeup",
      "phone": "081376054211",
      "address": "Tanjung Morawa",
      "image_references": [7, 8, 9, 10, 18, 19, 20, 21, 22]
    },
    {
      "vendor_id": "adilla_makeup",
      "vendor_name": "Adilla Makeup",
      "instagram": "@adillaamakeup",
      "phone": "+6283890749461",
      "image_references": [11, 12, 16, 23, 24]
    },
    {
      "vendor_id": "adilla_x_evi",
      "vendor_name": "Adilla Makeup x Evi Wedding Galery",
      "instagram": "@adillaamakeup x @eviweddinggalery",
      "image_references": [13]
    },
    {
      "vendor_id": "mecca_gallery",
      "vendor_name": "Mecca Gallery",
      "instagram": "@mecca_gallery23",
      "phone": "+6282382086993",
      "image_references": [14, 15]
    }
  ]
}
```
