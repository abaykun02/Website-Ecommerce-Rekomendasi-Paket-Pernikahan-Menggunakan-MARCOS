# PANDUAN REVISI GAMBAR & SINKRONISASI DESKRIPSI PAKET WEDDING (UNTUK ANTIGRAVITY)

> **Tujuan Dokumen**: Panduan terstruktur bagi AI / **Antigravity** untuk merevisi dan mengganti aset gambar pada paket-paket pernikahan yang sudah ada di dalam sistem sebelumnya, berdasarkan **32 gambar terbaru** beserta **deskripsi teks lengkap** yang dikirimkan oleh pemilik sistem.
>
> **Total File Gambar Dianalisis**: 32 File (`29` gambar unik + `3` file duplikat poster Alesya)  
> **Vendor Baru yang Teridentifikasi**: `@alesya_gallery_wedding` (3 Paket baru: Standart Rumah 10 Jt, Gedung Luxury 21 Jt, Wedding Rumah 23 Jt).

---

## ⚠️ CATATAN KRUSIAL UNTUK ANTIGRAVITY (AUDIT DATA & PERBAIKAN KONSISTENSI)

Sebelum memproses revisi gambar ke dalam sistem, perhatikan temuan audit berikut agar tidak terjadi salah pasang gambar atau salah deskripsi:

1. **Terdapat 3 Pasang File Duplikat (Poster Alesya Gallery Wedding)**:
   - `Alesya_Poster_43815091.jpeg` **IDENTIK** dengan `PAKET_WEDDING_RUMAH___23_JT_3c28ec77.jpeg` *(Paket Wedding Rumah – 23 JT)*.
   - `alesya_poster2_e11ff0a8.jpeg` **IDENTIK** dengan `Paket_Wedding_Gedung_Luxury_719c0d5c.jpeg` *(Paket Wedding Gedung Luxury – 21 Jt)*.
   - `alesya_poster3_8cc95945.jpeg` **IDENTIK** dengan `Paket_Wedding_Standart_Rumah_2dc22ecb.jpeg` *(Paket Wedding Standart Rumah – 10 Jt)*.
   - *Saran untuk sistem*: Gunakan salah satu file saja (disarankan nama file yang deskriptif) agar tidak duplikat di katalog.

2. **Harga yang Ada di Gambar Poster Alesya (Belum Tertulis di Teks Deskripsi)**:
   - Pada teks deskripsi user, judul hanya tertulis `Paket Wedding Standart Rumah` dan `Paket Wedding Gedung Luxury` tanpa nominal harga. Namun di dalam **gambar posternya** tertulis jelas:
     - **Paket Wedding Standart Rumah**: **`10 Jt`** (`Rp 10.000.000`)
     - **Paket Wedding Gedung Luxury**: **`21 Jt`** (`Rp 21.000.000`)
     - **Paket Wedding Rumah**: **`23 JT`** (`Rp 23.000.000`)

3. **File Gambar `Standart_Pakage_6a9a80bd.jpeg` Tidak Ada di Teks Deskripsi User**:
   - File gambar `Standart_Pakage_6a9a80bd.jpeg` tersedia di antara 32 gambar yang diunggah, namun deskripsi teksnya tidak ikut tertempel pada pesan terbaru.
   - Berdasarkan data sistem sebelumnya (Brosur #10 Dyraaerlia Makeup), file ini berpasangan dengan **Paket Wedding Standart (11 Jutaan)** milik **Dyraaerlia Makeup**.

4. **Duplikasi / Teks Tergabung pada `Bundling 13`**:
   - Pada teks deskripsi `Bundling 13` yang dikirim user, bagian bawahnya tidak sengaja menempelkan blok `MAKEUP + ATTIRE (3 pasang attire premium)` yang sebenarnya milik **`Bundling 14`**.
   - **Perbaikan yang benar**:
     - **`Bundling 13`** = `MAKEUP + ATTIRE` dengan **`3 pasang attire no premium`** (Rp 4.000.000 di brosur Syarifah #3).
     - **`Bundling 14`** = `MAKEUP + ATTIRE` dengan **`3 pasang attire premium`** (Rp 5.000.000 di brosur Syarifah #3).

5. **Koreksi Typo Minor pada Teks Deskripsi Asli (Tetap Dipertahankan di Detail, namun Catatan Rujukan Disediakan)**:
   - `Bundling 4`: tertulis `"decor akad dim ruangan"` (maksudnya *dlm ruangan*) dan `"pentas plaminan fim"` (di brosur asli: *6 m*).
   - `Bundling 5` & `6`: tertulis `"decor akad dim rumah"` (*dlm rumah*).
   - `Bundling 8`: tertulis `"sofiens melati asli"` (*soflens*).
   - `Bundling 9`: tertulis `"kursi akad tyfari"` (*tyfani*), `"Lante kaca din plaminan"` (*dpn plaminan*), `"full fforing"` (*full floring*), `"4 attire premisam"` (*premium*).
   - `Bundling 12`: tertulis `"akeup wedding + retouch"` (*Makeup*).
   - `Akad Intimate Pakage`: tertulis `"Free Sofien"` (*Soflen*).
   - `Wedding Pakage 3` & `1`: tertulis `"I pax"` / `"I set"` (angka `1` terbaca huruf `I`).

---

## 🗂️ TABEL PEMETAAN 32 GAMBAR → PAKET SISTEM & VENDOR

| No | Nama File Gambar | Analisis Visual Gambar (Isi Foto / Watermark) | Target Paket di Sistem | Vendor Pemilik |
|:---:|:---|:---|:---|:---|
| **1** | `Medium_Pakage_051cd429.jpeg` | Foto pasangan pengantin duduk di sofa krem/putih; wanita bergaun sequin merah marun + hijab marun + mahkota tiara silver; pria berjas hitam dasi emas; latar bunga merah-ungu-putih & lampu hias. | **Medium Pakage** *(sebelumnya: Paket Wedding Medium - 12 Jutaan)* | Dyraaerlia Makeup |
| **2** | `Mini_Pakage_9a6a18d3.jpeg` | Foto pasangan busana abu-abu silver (jas & gaun payet) berdiri saling membelakangi di atas karpet merah; latar tulisan *"Selly & Kiky"* + bunga biru-putih; watermark *Tetesan Embun Photography*. | **Mini Pakage** *(sebelumnya: Paket Wedding Mini - 10 Jutaan)* | Dyraaerlia Makeup |
| **3** | `Lux_Pakage_eb9aadee.jpeg` | Foto pasangan; wanita bergaun ballgown biru navy gelap penuh payet + veil/headpiece silver + buket bunga putih; pria jas hitam; latar bunga biru-putih & backdrop garis; watermark *photogenic*. | **Lux Pakage** *(sebelumnya: Paket Wedding Lux - 13 Jutaan)* | Dyraaerlia Makeup |
| **4** | `Standart_Pakage_6a9a80bd.jpeg` | Foto pasangan akad serba putih mengangkat tangan gembira memegang payung putih kecil & buku nikah; latar tirai biru & tulisan *"Hariany & Fajar"*. | **Standart Pakage** *(sebelumnya: Paket Wedding Standart - 11 Jutaan)* | Dyraaerlia Makeup |
| **5** | `Akad_Pakage_59fd4415.jpeg` | Foto pasangan akad busana putih memegang buku nikah (merah & hijau) di atas karpet hijau; pria duduk mengenakan peci putih & kain songket emas; latar bunga peach-putih. | **Akad Pakage** *(sebelumnya: Paket Akad DP 20%)* | Dyraaerlia Makeup |
| **6** | `Wedding_Pakage_1_640fa17c.jpeg` | Foto pengantin wanita duduk di kursi putih bergaun putih beraksen brokat pink-silver & veil tulle; latar penuh bunga warna-warni bertuliskan *"Simple decor"*. | **Wedding Pakage 1** *(sebelumnya: Paket Wedding Diamond)* | Dyraaerlia Makeup |
| **7** | `Wedding_Pakage_2_f36d052f.jpeg` | Close-up portrait pengantin wanita tanpa hijab, rambut sleek dengan veil lace putih, kalung mutiara bertingkat (choker), dan sarung tangan lace putih; latar abu-abu gelap; watermark *CAH GONDRONG PHOTOGRAPHY*. | **Wedding Pakage 2** *(sebelumnya: Paket Wedding Gold)* | Dyraaerlia Makeup |
| **8** | `Wedding_Pakage_3_d4953b91.jpeg` | Foto dekorasi pelaminan bernuansa krem-emas dengan ukiran panel klasik, 5 kursi pelaminan putih-silver, plafon kain tirai putih-kuning, dan bunga merah-oranye-biru-pink. | **Wedding Pakage 3** *(sebelumnya: Wedding Package Indoor Max 4m)* | Adilla Makeup |
| **9** | `Bundling_1_5b82e044.jpeg` | Foto pasangan berhadapan mesra; wanita bergaun & hijab oranye bata/terracotta berpayet + henna merah; pria berjas abu-abu tua; latar bunga pastel ungu-pink-putih. | **Bundling 1** *(sebelumnya: Package 1 - Rp 9.500.000)* | Syarifah Make Up |
| **10** | `Bundling_2_56670f1a.jpeg` | Foto pasangan adat Jawa hitam-emas (beskap & kebaya beludru + batik parang, wanita berhijab hitam dengan cunduk mentul & melati) di sofa depan backdrop lengkung bunga pastel pink-ungu. | **Bundling 2** *(sebelumnya: Paket 2 - Rp 10.500.000)* | Syarifah Make Up |
| **11** | `Bundling_3_5a1b9094.jpeg` | Foto pasangan busana akad serba putih memegang buku nikah di atas lantai kaca berhias lampu; wanita memakai mahkota siger/sunda silver + melati panjang; latar bunga pink-putih. | **Bundling 3** *(sebelumnya: Paket 3 - Rp 12.000.000)* | Syarifah Make Up |
| **12** | `Bundling_4__ba977a1f.jpeg` | Foto pasangan adat Melayu kuning kunyit/emas cerah lengkap dengan tanjak, rantai dada emas, dan sunting Melayu; duduk/berdiri di dekorasi bunga biru-putih. | **Bundling 4** *(sebelumnya: Package 4 - Rp 15.000.000)* | Syarifah Make Up |
| **13** | `Bundling_5_333f0ce3.jpeg` | Foto pasangan busana akad putih (pria baju kurung Melayu + kain songket silver + peci hitam; wanita gaun brokat putih) memegang buku nikah di atas lantai kaca bercahaya. | **Bundling 5** *(sebelumnya: Paket 5 - Rp 17.000.000)* | Syarifah Make Up |
| **14** | `Bundling_6_ba78b9a6.jpeg` | Foto pasangan adat Jawa hitam-emas bergandengan tangan di depan backdrop bunga mawar putih raksasa 3D dan tirai hitam-emas. | **Bundling 6** *(sebelumnya: Paket 6 - Rp 19.000.000)* | Syarifah Make Up |
| **15** | `Bundling_7_35e76ae6.jpeg` | Foto pasangan adat Jawa Paes Ageng hitam-emas (dengan paes dahi, cunduk mentul & melati) di depan backdrop bunga merah-pink bertuliskan *"PF Putri Fandy"*. | **Bundling 7** *(sebelumnya: Paket 7 - Rp 21.500.000)* | Syarifah Make Up |
| **16** | `Bundling_8_f71d961a.jpeg` | Foto pengantin wanita duduk di sofa mengenakan adat Minang/Padang (Suntiang emas megah + baju kurung beludru merah marun bersulam emas + gelang gadang) dengan latar pelaminan adat Minang. | **Bundling 8** *(sebelumnya: Paket 8 - Rp 23.500.000)* | Syarifah Make Up |
| **17** | `Bundling_9_d5307689.jpeg` | Foto pasangan adat Palembang/Basahan hijau-emas duduk di sofa putih di depan pilar putih, tirai merah, dan bunga penuh merah-ungu, inisial *"F D"*; watermark *FII PHOTOGRAPHY*. | **Bundling 9** *(sebelumnya: Paket 9 - Rp 26.500.000)* | Syarifah Make Up |
| **18** | `Bundling_10_0ca1ff82.jpeg` | Foto pasangan akad busana putih memegang buku nikah hijau dengan mata terpejam tersenyum; wanita bertiara kecil & henna merah; latar *"Ara & Riski"*; watermark *KF Photography*. | **Bundling 10** *(sebelumnya: Paket Pernikahan 3-in-1 - Rp 2.500.000)* | Syarifah Make Up |
| **19** | `Bundling_11_5b861b54.jpeg` | Close-up portrait pengantin wanita memakai hiasan kepala kain merah berornamen lempeng emas + melati panjang, busana brokat merah marun, softlens abu-abu, henna merah. | **Bundling 11** *(Paket Baru: Makeup Resepsi + 3 Attire by syr_gown)* | Syarifah Make Up |
| **20** | `Bundling_12_907db14a.jpeg` | Close-up portrait pengantin wanita berhijab pink pastel inner lace, makeup bold soft glam (eyeshadow cokelat-oranye, lipstik matte nude-mauve), henna merah beraksen emas di tangan. | **Bundling 12** *(sebelumnya: Makeup Resepsi - Rp 2.000.000)* | Syarifah Make Up |
| **21** | `Bundling_13_b4769e09.jpeg` | Close-up portrait pengantin wanita berhijab kuning zaitun/lime dengan cunduk mentul silver & melati panjang; terlihat tulisan dinding *"Syarifah MAKE UP..."* di latar belakang. | **Bundling 13** *(sebelumnya: Makeup + 3 Attire Non Premium - Rp 4.000.000)* | Syarifah Make Up |
| **22** | `Bundling_14_5b7d4496.jpeg` | Close-up portrait pengantin wanita berhijab putih polos dengan veil lace & mahkota tiara silver, makeup pipi merona pink & lipstik glossy berry, softlens abu-abu-biru. | **Bundling 14** *(sebelumnya: Makeup + 3 Attire Premium - Rp 5.000.000)* | Syarifah Make Up |
| **23** | `AKAD_PACKAGE_93e675f4.jpeg` | Close-up portrait pengantin wanita kebaya putih payet silver, hijab putih polos + untaian melati panjang di kanan, latar belakang cokelat tua polos. | **AKAD PACKAGE** *(Rp 1.500.000)* | Adilla Makeup |
| **24** | `AKAD_RESEPSI_PACKAGE_36d3fb01.jpeg` | Close-up portrait pengantin wanita kebaya putih, hijab sampir + bandana mutiara + untaian melati panjang, latar dinding abu-abu terang. | **AKAD + RESEPSI PACKAGE** *(Rp 2.000.000)* | Adilla Makeup |
| **25** | `Prewedding_Pakage_e8b36184.jpeg` | Foto pasangan prewedding outdoor di bawah pohon rindang; wanita kebaya pink pastel + rok batik biru memegang buket; pria kemeja biru motif songket; watermark *HR PHOTOGRAPHY*. | **Prewedding Pakage** *(Promo Prewedding - Rp 2.100.000)* | Adilla Makeup |
| **26** | `Akad_Intimate_Pakage_dc3077f7.jpeg` | Close-up portrait pengantin wanita busana & veil brokat putih mewah, makeup soft glam pink blush, tangan kiri berhias henna putih-emas. | **Akad Intimate Pakage** | Mecca Gallery |
| **27** | `Paket_Wedding_Standart_Rumah_2dc22ecb.jpeg` | Poster IG Story `catalog PL` bertuliskan **"PAKET WEDDING STANDART RUMAH – LETS GET 10 Jt"** (`@alesya_gallery_wedding`). | **Paket Wedding Standart Rumah (10 Jt)** *(Paket Baru)* | Alesya Gallery Wedding |
| **28** | `alesya_poster3_8cc95945.jpeg` | *DUPLIKAT IDENTIK dari `Paket_Wedding_Standart_Rumah_2dc22ecb.jpeg`*. | *(Duplikat Paket Wedding Standart Rumah - 10 Jt)* | Alesya Gallery Wedding |
| **29** | `Paket_Wedding_Gedung_Luxury_719c0d5c.jpeg` | Poster IG Story `catalog PL` bertuliskan **"PAKET WEDDING GEDUNG LUXURY – LETS GET 21 Jt"** (`@alesya_gallery_wedding`). | **Paket Wedding Gedung Luxury (21 Jt)** *(Paket Baru)* | Alesya Gallery Wedding |
| **30** | `alesya_poster2_e11ff0a8.jpeg` | *DUPLIKAT IDENTIK dari `Paket_Wedding_Gedung_Luxury_719c0d5c.jpeg`*. | *(Duplikat Paket Wedding Gedung Luxury - 21 Jt)* | Alesya Gallery Wedding |
| **31** | `PAKET_WEDDING_RUMAH___23_JT_3c28ec77.jpeg` | Poster IG Story `catalog PL` berlatar pelaminan gelap bertuliskan **"PAKET WEDDING RUMAH – 23 JT"** (`@alesya_gallery_wedding`). | **PAKET WEDDING RUMAH – 23 JT** *(Paket Baru)* | Alesya Gallery Wedding |
| **32** | `Alesya_Poster_43815091.jpeg` | *DUPLIKAT IDENTIK dari `PAKET_WEDDING_RUMAH___23_JT_3c28ec77.jpeg`*. | *(Duplikat Paket Wedding Rumah - 23 JT)* | Alesya Gallery Wedding |

---

## 📦 DESKRIPSI LENGKAP PER VENDOR & PAKET (UNTUK REVISI SISTEM)

---

### VENDOR 1: DYRAAERLIA MAKEUP
* **HP**: `0813 7605 4211` / `081376054211`
* **Instagram**: `@Dyraaerlia_Makeup` / `@dyraaerliaa_makeup`

#### 1. Medium Pakage
* **File Gambar Revisi**: `/assets/aistudio/Medium_Pakage_051cd429.jpeg`
* **Harga Referensi Sistem**: Rp 12.000.000 (12 Jutaan)
* **Deskripsi**:
  - Tenda 6 gawang
  - Pentas kibot
  - Pentas pelaminan
  - Kursi 200 balut 100
  - Alat dapur
  - Pelaminan 8 meter
  - Meja vip 3
  - Pondok es & kue
  - Tenda terima tamu
  - Kursi akad depan pelaminan
  - Makeup akad & resepsi di hari yg sama
  - Busana 4 pasang baju premium
  - Foto 2 rol
  - Free Makeup 1 org / soflens
* **Catatan**:
  - Harga tersebut wilayah Tanjung Morawa; di luar Tanjung Morawa dikenakan biaya transportasi sesuai jarak.
  - Hairdo Jawa sasak dikenakan biaya tambahan 350.
  - Acc Aceh & Karo dikenakan cas harga.

#### 2. Mini Pakage
* **File Gambar Revisi**: `/assets/aistudio/Mini_Pakage_9a6a18d3.jpeg`
* **Harga Referensi Sistem**: Rp 10.000.000 (10 Jutaan)
* **Deskripsi**:
  - Tenda 5 gawang
  - Pentas kibot
  - Pentas pelaminan
  - Kursi 150 balut 50
  - Alat dapur
  - Pelaminan 6 meter
  - Meja vip 2
  - Pondok es & kue
  - Tenda terima tamu
  - Kursi akad depan pelaminan
  - Makeup akad & resepsi di hari yg sama
  - Busana 4 pasang baju standart
  - Foto 2 rol
  - Free Makeup 1 org + softlens
* **Catatan**:
  - Harga tersebut wilayah Tanjung Morawa; di luar Tanjung Morawa dikenakan biaya transportasi sesuai jarak.
  - Hairdo Jawa sasak dikenakan biaya tambahan 350.
  - Acc Aceh & Karo dikenakan cas harga.
  - Penambahan baju premium 350/gaun.

#### 3. Lux Pakage
* **File Gambar Revisi**: `/assets/aistudio/Lux_Pakage_eb9aadee.jpeg`
* **Harga Referensi Sistem**: Rp 13.000.000 (13 Jutaan)
* **Deskripsi**:
  - Tenda 7 gawang
  - Pentas kibot
  - Pentas pelaminan
  - Kursi 200 balut 100
  - Alat dapur
  - Pelaminan 8 meter
  - Meja vip 3
  - Pondok es & kue
  - Tenda terima tamu
  - Kursi akad depan pelaminan
  - Makeup akad & resepsi di hari yg sama
  - Busana 4 pasang baju premium
  - Foto 4 rol
  - Free Makeup 1 org / soflens
  - Lampu & izin PLN
* **Catatan**:
  - Harga tersebut wilayah Tanjung Morawa; di luar Tanjung Morawa dikenakan biaya transportasi sesuai jarak.
  - Hairdo Jawa sasak dikenakan biaya tambahan 350.
  - Penambahan baju premium dikenakan biaya 350/baju.
  - Acc Aceh & Karo dikenakan cas harga.

#### 4. Standart Pakage *(Catatan: Gambar terunggah, deskripsi diambil dari sistem lama)*
* **File Gambar Revisi**: `/assets/aistudio/Standart_Pakage_6a9a80bd.jpeg`
* **Harga Referensi Sistem**: Rp 11.000.000 (11 Jutaan)
* **Deskripsi (dari sistem sebelumnya)**:
  - Tenda 6 gawang, pentas kibot, pentas pelaminan, kursi 150 balut 100, alat dapur, pelaminan 6 meter, meja VIP 3, pondok es & kue, tenda terima tamu, kursi akad depan pelaminan, makeup akad & resepsi di hari yg sama, busana 4 pasang baju standart, foto 2 rol, free Makeup 1 org / soflens.

#### 5. Akad Pakage
* **File Gambar Revisi**: `/assets/aistudio/Akad_Pakage_59fd4415.jpeg`
* **Deskripsi**:
  - Backdrop 2-3 meter
  - Meja akad
  - Lighting Standart
  - Welcome Sign
  - Cinematic
  - Foto Unlimeted (5 jam kerja)
  - Cetak magazine
  - Makeup akad
  - Baju sepasang (akad)
  - Free Henna
* **Note**:
  - Booking tanggal DP 20%
  - DP = booking
  - Pelunasan saat pemasangan dekorasi
  - Bebas pilih model dekorasi

#### 6. Wedding Pakage 1 *(Mapping sistem lama: Paket Wedding Diamond - Dyraaerlia)*
* **File Gambar Revisi**: `/assets/aistudio/Wedding_Pakage_1_640fa17c.jpeg`
* **Deskripsi**:
  - **Tenda**: Tenda biasa 4 gang, lantai karpet/flooring, pentas pelaminan 6 meter, tenda & pentas hiburan, meja prasmanan 2 set, pondok buah 1, meja VIP 3 pcs, kursi 200 balut 150, alat dapur panstop biasa & tungku, tenda cafe & meja souvenir, tenda dapur, ijin PLN & pasang lampu, blower air 2 set.
  - **Dekor**: Pelaminan 6 meter, hias kain kamar, dekor akad / set akad kursi + meja depan pelaminan.
  - **MUA & Attire**: Makeup resepsi, baju 4 pasang, acc adat, soflens normal, melati asli / jika diperlukan, free makeup mom bride 1, free henna maroon, free mahar bingkai simple.
  - **Fg**: Foto 4 Roll, video syuting 2 jam.

#### 7. Wedding Pakage 2 *(Mapping sistem lama: Paket Wedding Gold - Dyraaerlia)*
* **File Gambar Revisi**: `/assets/aistudio/Wedding_Pakage_2_f36d052f.jpeg`
* **Deskripsi**:
  - Tenda tamu 4, tenda keyboard, terpal masak, pentas pelaminan, pentas keyboard
  - Meja pondok 2, meja hidangan 2, alat dapur, panstop 2 set, tenda terima tamu 1, meja VIP 2, kursi + balut 150
  - Pelaminan 6 - 8 meter, photobooth, gapura pintu masuk, lampu hiasan pelaminan, dekorasi akad nikah, dekor kamar pengantin, kotak uang
  - Makeup pengantin, retouch makeup, baju pengantin 3 pasang (mix 1 adat), henna, makeup orang tua 1, melati asli jika perlu, acc adat tertentu, free sewa crown, free softlens.

---

### VENDOR 2: SYARIFAH MAKE UP
* **Catatan Tambahan Umum (Berlaku untuk Bundling 1 – 9)**:
  - Akad beda hari cas 500
  - Tambah foto 1 roll 350
  - Kursi tifani akad cas 500
  - Jawa sasak/paes asli cas 500
  - Melati asli cas 250-500
  - Tambahan makeup 100-150 by TEAM
  - Biaya transport d luar Medan
  - Tambahan baju 1 psg 500
  - Ijin PLN d tanggung tuan rumah

#### 1. Bundling 1 *(Package 1 - Rp 9.500.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_1_5b82e044.jpeg`
* **Inklusi**: Pelaminan 5 meter, tenda plaminan + pentas plaminan 5 m, tenda tamu 2 gawang sisir, tenda kibot 1 gawang pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + kursi 100, foto 2 roll, makeup pengantin 3 attire non premium, makeup ibu hajad 1 org, tanpa henna, tanpa video/cenematik, tanpa panel listrik.

#### 2. Bundling 2 *(Paket 2 - Rp 10.500.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_2_56670f1a.jpeg`
* **Inklusi**: Pelaminan 6 meter, tenda plaminan + pentas plaminan 6 m, tenda tamu 3 gawang sisir, tenda kibot 1 gawang + pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + kursi 100, foto 2 roll, makeup pengantin + 3 attire non premium, makeup ibu hajad 1 org, tanpa henna, tanpa video/cenematik, tanpa panel listrik.

#### 3. Bundling 3 *(Paket 3 - Rp 12.000.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_3_5a1b9094.jpeg`
* **Inklusi**: Pelaminan 6 meter, set akad, tenda plaminan + pentas plaminan 6 m, tenda tamu 4 gawang sisir, tenda kibot 1 gawang + pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1, kursi 100 + bungkus, lampu, foto 4 roll, makeup pengantin + 3 attire non premium, makeup ibu hajad 1 org, henna.

#### 4. Bundling 4 *(Package 4 - Rp 15.000.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_4__ba977a1f.jpeg`
* **Inklusi**: Pelaminan 6 meter, decor akad dlm ruangan, tenda plaminan pentas plaminan 6 m, tenda tamu 4 gawang sisir, tenda kibot 1 gawang pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + meja vip 2, kursi 100 bungkus, full floring, lampu, foto 4 roll, makeup pengantin 3 attire non premium, makeup ibu hajad 1 org, henna, soflens.

#### 5. Bundling 5 *(Paket 5 - Rp 17.000.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_5_333f0ce3.jpeg`
* **Inklusi**: Pelaminan 6 meter, decor akad dlm rumah, tenda plaminan pentas plaminan 6 m, tenda tamu 6 gawang sisir, tenda kibot 1 gawang + pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + meja vip 2, kursi 150 + 100 bungkus, full floring, lampu, foto 4 roll + cenematik, makeup pengantin + 4 attire non premium, makeup ibu hajad 1 org, henna, soflens.

#### 6. Bundling 6 *(Paket 6 - Rp 19.000.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_6_ba78b9a6.jpeg`
* **Inklusi**: Pelaminan 8 meter + altar jalan cermin get masuk, decor akad dlm rumah, tenda plaminan pentas plaminan 8 m, tenda tamu 6 gawang sisir, tenda kibot 1 gawang + pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + meja vip 2, kursi 200 + 150 bungkus, full floring, lampu + 1 blower, kain tutup, foto 4 roll + cenematik, makeup pengantin + 4 attire non premium, makeup ibu hajad 1 org, henna, soflens + melati asli.

#### 7. Bundling 7 *(Paket 7 - Rp 21.500.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_7_35e76ae6.jpeg`
* **Inklusi**: Pelaminan 8 meter + altar jalan, kursi akad tyfani, lantai kaca depan pelaminan, tenda plaminan + pentas plaminan 8m, tenda tamu 6 tenda matahari, tenda kibot 1 gawang + pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + meja VIP 2, kursi 200 + 150 bungkus, full flooring + red karpet, kain tutup keliling, lampu + 2 blower, foto unlimited + cinematik, makeup pengantin + 4 attire premium, makeup ibu hajad 1 orang, henna, softlens + melati asli, prewed.

#### 8. Bundling 8 *(Paket 8 - Rp 23.500.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_8_f71d961a.jpeg`
* **Inklusi**: Plaminan 10 + altar jalan, kursi akad tyfani, lante kaca dpn plaminan + gajebo, tenda plaminan pentas plaminan 10 m, tenda tamu 7 tenda matahari, tenda kibot 1 gawang pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + meja vip 4, kursi 250 + 150 bungkus, full floring red karpet, kain tutup keliling, foto unlimited cenematik, makeup pengantin + 4 attire premium, lampu 3 blower, makeup ibu hajad 1 org, henna, soflens melati asli, free prewed.

#### 9. Bundling 9 *(Paket 9 - Rp 26.500.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_9_d5307689.jpeg`
* **Inklusi**: Plaminan 10 + altar jalan, kursi akad tyfani, lante kaca dpn plaminan + gajebo, fresh flower 20% jalan pengantin, tenda plaminan pentas plaminan 10 m, tenda tamu 8 gawang gelombang, tenda kibot 1 gawang + pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + meja vip 4, kursi 250 + 150 bungkus, full floring red karpet, kain tutup keliling, lampu + 4 blower, foto unlimited cenematik, makeup pengantin 4 attire premium, makeup ibu hajad 1 org, henna, soflens melati asli, free Prewed.

#### 10. Bundling 10 *(Paket Pernikahan 3-in-1 - Rp 2.500.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_10_0ca1ff82.jpeg`
* **Inklusi**:
  - **Dekorasi**: Dekorasi indoor ukuran 3x3 meter, Bucket Bunga, Kursi, Karpet, meja akad.
  - **Dokumentasi**: Durasi maksimal 3 jam, Foto tak terbatas, Layanan cepat, cetak foto album.
  - **MUA**: Make up, Hijab, attire 1 psg, soflens.

#### 11. Bundling 11 *(Paket Baru: Makeup Resepsi + 3 Attire by syr_gown)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_11_5b861b54.jpeg`
* **Inklusi**:
  - Makeup resepsi + retouch
  - Attire 3 pasang (attire by `syr_gown`)
  - Acecories pengantin
  - Hijab do
  - Free soflens
  - Free makeup 1 org
* **Note**: Adat tertentu cas, akad beda hari cas, melati asli cas, paes/jawa sasak cas.

#### 12. Bundling 12 *(Makeup Resepsi - Rp 2.000.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_12_907db14a.jpeg`
* **Inklusi**: Makeup wedding + retouch, hijabdo, soflens, free makeup 1 org.
* **Note**: Akad beda hari cas 500, tambahan baju cas 500, adat tertentu cas, paes cas 500, tambahan makeup 150 by TEAM, batas retouch jam 6 sore.

#### 13. Bundling 13 *(Makeup + Attire Non Premium - Rp 4.000.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_13_b4769e09.jpeg`
* **Inklusi**: Makeup wedding + retouch, hijabdo, 3 pasang attire no premium, soflens, free makeup 1 org, melati asli.
* **Note**: Akad beda hari cas 500, tambahan baju cas 500, adat tertentu cas, paes cas 500, tambahan makeup 150 by TEAM, batas retouch jam 6 sore.

#### 14. Bundling 14 *(Makeup + Attire Premium - Rp 5.000.000)*
* **File Gambar Revisi**: `/assets/aistudio/Bundling_14_5b7d4496.jpeg`
* **Inklusi**: Makeup wedding + retouch, hijabdo, 3 pasang attire premium, soflens, free makeup 1 org, melati asli.
* **Note**: Akad beda hari cas 500, tambahan baju cas 500, adat tertentu cas, paes cas 500, tambahan makeup 150 by TEAM, batas retouch jam 6 sore.

---

### VENDOR 3: ADILLA MAKEUP

#### 1. AKAD PACKAGE *(Rp 1.500.000)*
* **File Gambar Revisi**: `/assets/aistudio/AKAD_PACKAGE_93e675f4.jpeg`
* **Inklusi**: MAKEUP SERVICE, 1 SET GOWN UNTUK CPP & CPW, CLEAN HIJABDO, MELATI IMITASI PREMIUM, SOFLENS.
* **Noted**: Homeservice dikenakan fee transportasi sesuai jarak; Hairdo dikenakan fee tambahan 200K; Akad & Resepsi beda hari dikenakan fee tambahan 100K.

#### 2. AKAD+RESEPSI PACKAGE *(Rp 2.000.000)*
* **File Gambar Revisi**: `/assets/aistudio/AKAD_RESEPSI_PACKAGE_36d3fb01.jpeg`
* **Inklusi**: MAKEUP SERVICE, 3 SET GOWN MIX ADAT (1) UNTUK CPP & CPW, RETOUCH, MUA STANDBY SAMPAI JAM 5 SORE, HIJABDO, SOFLENS, MAKEUP FAMILY BRIDE 1 ORANG, MELATI IMITASI PREMIUM.
* **Noted**: Homeservice dikenakan fee transportasi sesuai jarak; Hairdo dikenakan fee tambahan 200K; Akad & Resepsi beda hari dikenakan fee tambahan 100K.

#### 3. Prewedding Pakage *(Rp 2.100.000)*
* **File Gambar Revisi**: `/assets/aistudio/Prewedding_Pakage_e8b36184.jpeg`
* **Inklusi**:
  - **Photographer**: 1 cetak 24 Inch + Frame, 4 cetak 6 Inch + Frame, 10 Foto Edit + All foto (gdrive), free pas foto latar biru/merah, 2 Session indoor include studio.
  - **MUA**: Makeup Service, Soflens, Hijabdo *(noted: Hairdo dikenakan fee tambahan)*.
  - **Gown**: 1 set premium gown bebas pilih.

#### 4. Wedding Pakage 3 *(Wedding Package Pelaminan Indoor Max 4 Meter)*
* **File Gambar Revisi**: `/assets/aistudio/Wedding_Pakage_3_d4953b91.jpeg`
* **Inklusi**:
  - **Dekorasi**: Pelaminan Indoor Max 4 Meter, Sofa Pengantin (1 pax), Standing flowers & lighting, Cover dinding area dekor, Set Meja Akad Bawah, Welcome Sign, Tenda matahari 4, Lampu & kipas angin atas, Kursi plastik & cover 100 pcs, Meja hidang 1 set, Meja snack 1, Meja VIP 2, Meja penerima tamu 1, Panstove petak 1 set, Piring sendok garpu 200 pcs, Perlengkapan dapur, Panel listrik, Kotak amplop 1.
  - **MUA & Attire**: Makeup full retouch s/d sore, Softlens, Melati, Makeup ibu 1, Attire Adat & Acc 1 pasang, Attire Nasional 1 pasang (Premium), Attire Nasional 1 pasang (Reguler), Hijab do, Crown, FREE Henna Pengantin (Red).
  - **Fg**: Shoot 08.00 Wib s/d 20.00 wib, Cetak 3 roll, Album magnetic, Video Shooting 2 Disk (*not unlimited).

---

### VENDOR 4: MECCA GALLERY

#### 1. Akad Intimate Pakage
* **File Gambar Revisi**: `/assets/aistudio/Akad_Intimate_Pakage_dc3077f7.jpeg`
* **Inklusi**:
  - **DEKORASI**: Dekorasi indoor ukuran 3x3 meter, Free Miror Sign.
  - **DOKUMENTASI**: File Photo Unlimited, Edit dan Non Edit, Cetak album wedding book, Cetak photo plus frame ukuran 8x12 inch 1pcs, file g drive pribadi, 4 jam kerja.
  - **MUA**: Make up, Hijab do, Free Soflen.
  - **ATTIRE**: Sepasang Baju Akad, Free hijab + Manset.
  - **HENA**: Hena Maroon.

---

### VENDOR 5: ALESYA GALLERY WEDDING *(VENDOR BARU)*
* **Instagram**: `@alesya_gallery_wedding`

#### 1. Paket Wedding Standart Rumah – 10 Jt
* **File Gambar Utama**: `/assets/aistudio/Paket_Wedding_Standart_Rumah_2dc22ecb.jpeg` *(Duplikat: `alesya_poster3_8cc95945.jpeg`)*
* **Harga**: **Rp 10.000.000 (10 Jt)** *(tertera pada gambar poster)*
* **Inklusi**:
  - **TENDA / PERALATAN**: Tenda 5 Plong (sisir), 1 Tenda Terima Tamu, Kursi 100 pcs, Pentas Music, Pentas Pelaminan, Peralatan Dapur, 1 Meja Hidangan, 1 Meja Pondokan.
  - **DECOR**: Pelaminan 4-5 meter, Sofa Pelaminan Pengantin, Kursi Akad + Meja Tiffani, Bunga Depan Pelaminan, Gapura Masuk, Welcome Sign Banner.
  - **MUA**: Makeup Akad & Resepsi, 3 Baju (Mix 1 Adat) + ACC, Free Softlens, Free Melati Asli, Henna.
  - **DOKUMENTASI**: Foto Unlimited, Cetak Album Foto 100, All file G-Drive, Duty sampai jam 6 sore.

#### 2. Paket Wedding Gedung Luxury – 21 Jt
* **File Gambar Utama**: `/assets/aistudio/Paket_Wedding_Gedung_Luxury_719c0d5c.jpeg` *(Duplikat: `alesya_poster2_e11ff0a8.jpeg`)*
* **Harga**: **Rp 21.000.000 (21 Jt)** *(tertera pada gambar poster)*
* **Inklusi**:
  - **DECOR**: Dekorasi 10-12 meter, Taman Pelaminan Fresh Flower, Set Meja Akad, Altar Jalan, Gazebo, Lighting, Hias Terima Tamu, Hias Backdrop Music, Photobooth, Kotak Amplop, Gapura Masuk, Welcome Gate Photo, Mirror Sign.
  - **MUA**: Makeup Akad & Resepsi, 3 Baju (Mix 1 Adat), Hijabdo, Free Makeup 1 orang, Free Softlens, Free Melati Asli, Henna.
  - **DOKUMENTASI**: Foto Unlimited, Cetak Album 100 pcs, Vidio Cinematic, All file G-Drive.
  - **WO**: MC, 3 Crew Professional, Rundown Acara.

#### 3. PAKET WEDDING RUMAH – 23 JT
* **File Gambar Utama**: `/assets/aistudio/PAKET_WEDDING_RUMAH___23_JT_3c28ec77.jpeg` *(Duplikat: `Alesya_Poster_43815091.jpeg`)*
* **Harga**: **Rp 23.000.000 (23 JT)**
* **Inklusi**:
  - **TENDA & PERKAKAS**: Tenda 8 Plong Tanpa Tiang Tengah, Full Flooring + Karpet, Full Cover Kain Keliling, Panggung Pelaminan, Music, Kursi 150 Balut, Meja Hidangan 2 Set, Pondokan Snack 1, Meja VIP 3, Tenda Terima Tamu + Meja, Alat Dapur + Panstop, Rolling, Piring + Sendok + Garpu 150 Pcs, Blower 2 Unit, Kipas Angin Biasa 2 *(Note: Belum Termasuk Izin Panel Listrik)*.
  - **DEKOR**: Pelaminan 8–10 Meter, Sofa Pengantin + Ortu, Taman Depan Pelaminan, Dekor Akad + Meja Tiffani, Altar Jalan, Standing Bunga, Lampu Jalan, Mirror, Welcome Gate.
  - **DOKUMENTASI**: Foto Unlimited, Cetak Album, Cinematic, File.
  - **MUA & ATTIRE**: Make up + Re touch, 3 Baju Pengantin (Mix 1 Adat) + ACC, Free Softlens, Free Melati, Hijabdo, Durasi sampai jam 5 sore.
  - **FREE**: Henna Maroon, Make up Mom, Galery Foto, Kotak Amplop.

---

## 🤖 BLOK JSON SINKRONISASI REVISI GAMBAR (KHUSUS ANTIGRAVITY)

```json
{
  "action": "replace_package_images_and_sync_descriptions",
  "image_mappings": [
    { "new_file": "/assets/aistudio/Medium_Pakage_051cd429.jpeg", "target_package": "Medium Pakage (Paket Wedding Medium)", "vendor": "Dyraaerlia Makeup", "price": 12000000 },
    { "new_file": "/assets/aistudio/Mini_Pakage_9a6a18d3.jpeg", "target_package": "Mini Pakage (Paket Wedding Mini)", "vendor": "Dyraaerlia Makeup", "price": 10000000 },
    { "new_file": "/assets/aistudio/Lux_Pakage_eb9aadee.jpeg", "target_package": "Lux Pakage (Paket Wedding Lux)", "vendor": "Dyraaerlia Makeup", "price": 13000000 },
    { "new_file": "/assets/aistudio/Standart_Pakage_6a9a80bd.jpeg", "target_package": "Standart Pakage (Paket Wedding Standart)", "vendor": "Dyraaerlia Makeup", "price": 11000000, "note": "Gambar tersedia, deskripsi dari sistem sebelumnya" },
    { "new_file": "/assets/aistudio/Akad_Pakage_59fd4415.jpeg", "target_package": "Akad Pakage (Paket Akad)", "vendor": "Dyraaerlia Makeup", "price": null },
    { "new_file": "/assets/aistudio/Wedding_Pakage_1_640fa17c.jpeg", "target_package": "Wedding Pakage 1 (Paket Wedding Diamond)", "vendor": "Dyraaerlia Makeup", "price": null },
    { "new_file": "/assets/aistudio/Wedding_Pakage_2_f36d052f.jpeg", "target_package": "Wedding Pakage 2 (Paket Wedding Gold)", "vendor": "Dyraaerlia Makeup", "price": null },
    { "new_file": "/assets/aistudio/Wedding_Pakage_3_d4953b91.jpeg", "target_package": "Wedding Pakage 3 (Indoor Max 4 Meter)", "vendor": "Adilla Makeup", "price": null },
    { "new_file": "/assets/aistudio/Bundling_1_5b82e044.jpeg", "target_package": "Bundling 1 (Package 1)", "vendor": "Syarifah Make Up", "price": 9500000 },
    { "new_file": "/assets/aistudio/Bundling_2_56670f1a.jpeg", "target_package": "Bundling 2 (Paket 2)", "vendor": "Syarifah Make Up", "price": 10500000 },
    { "new_file": "/assets/aistudio/Bundling_3_5a1b9094.jpeg", "target_package": "Bundling 3 (Paket 3)", "vendor": "Syarifah Make Up", "price": 12000000 },
    { "new_file": "/assets/aistudio/Bundling_4__ba977a1f.jpeg", "target_package": "Bundling 4 (Package 4)", "vendor": "Syarifah Make Up", "price": 15000000 },
    { "new_file": "/assets/aistudio/Bundling_5_333f0ce3.jpeg", "target_package": "Bundling 5 (Paket 5)", "vendor": "Syarifah Make Up", "price": 17000000 },
    { "new_file": "/assets/aistudio/Bundling_6_ba78b9a6.jpeg", "target_package": "Bundling 6 (Paket 6)", "vendor": "Syarifah Make Up", "price": 19000000 },
    { "new_file": "/assets/aistudio/Bundling_7_35e76ae6.jpeg", "target_package": "Bundling 7 (Paket 7)", "vendor": "Syarifah Make Up", "price": 21500000 },
    { "new_file": "/assets/aistudio/Bundling_8_f71d961a.jpeg", "target_package": "Bundling 8 (Paket 8)", "vendor": "Syarifah Make Up", "price": 23500000 },
    { "new_file": "/assets/aistudio/Bundling_9_d5307689.jpeg", "target_package": "Bundling 9 (Paket 9)", "vendor": "Syarifah Make Up", "price": 26500000 },
    { "new_file": "/assets/aistudio/Bundling_10_0ca1ff82.jpeg", "target_package": "Bundling 10 (Paket Pernikahan 3-in-1)", "vendor": "Syarifah Make Up", "price": 2500000 },
    { "new_file": "/assets/aistudio/Bundling_11_5b861b54.jpeg", "target_package": "Bundling 11 (Makeup Resepsi + 3 Attire syr_gown)", "vendor": "Syarifah Make Up", "price": null },
    { "new_file": "/assets/aistudio/Bundling_12_907db14a.jpeg", "target_package": "Bundling 12 (Makeup Resepsi)", "vendor": "Syarifah Make Up", "price": 2000000 },
    { "new_file": "/assets/aistudio/Bundling_13_b4769e09.jpeg", "target_package": "Bundling 13 (Makeup + 3 Attire Non Premium)", "vendor": "Syarifah Make Up", "price": 4000000 },
    { "new_file": "/assets/aistudio/Bundling_14_5b7d4496.jpeg", "target_package": "Bundling 14 (Makeup + 3 Attire Premium)", "vendor": "Syarifah Make Up", "price": 5000000 },
    { "new_file": "/assets/aistudio/AKAD_PACKAGE_93e675f4.jpeg", "target_package": "AKAD PACKAGE", "vendor": "Adilla Makeup", "price": 1500000 },
    { "new_file": "/assets/aistudio/AKAD_RESEPSI_PACKAGE_36d3fb01.jpeg", "target_package": "AKAD+RESEPSI PACKAGE", "vendor": "Adilla Makeup", "price": 2000000 },
    { "new_file": "/assets/aistudio/Prewedding_Pakage_e8b36184.jpeg", "target_package": "Prewedding Pakage", "vendor": "Adilla Makeup", "price": 2100000 },
    { "new_file": "/assets/aistudio/Akad_Intimate_Pakage_dc3077f7.jpeg", "target_package": "Akad Intimate Pakage", "vendor": "Mecca Gallery", "price": null },
    { "new_file": "/assets/aistudio/Paket_Wedding_Standart_Rumah_2dc22ecb.jpeg", "duplicate_file": "/assets/aistudio/alesya_poster3_8cc95945.jpeg", "target_package": "Paket Wedding Standart Rumah", "vendor": "Alesya Gallery Wedding", "price": 10000000 },
    { "new_file": "/assets/aistudio/Paket_Wedding_Gedung_Luxury_719c0d5c.jpeg", "duplicate_file": "/assets/aistudio/alesya_poster2_e11ff0a8.jpeg", "target_package": "Paket Wedding Gedung Luxury", "vendor": "Alesya Gallery Wedding", "price": 21000000 },
    { "new_file": "/assets/aistudio/PAKET_WEDDING_RUMAH___23_JT_3c28ec77.jpeg", "duplicate_file": "/assets/aistudio/Alesya_Poster_43815091.jpeg", "target_package": "PAKET WEDDING RUMAH – 23 JT", "vendor": "Alesya Gallery Wedding", "price": 23000000 }
  ]
}
```
