<?php

namespace App\Support;

class KantinMenu
{
    public static function tenants(): array
    {
        return [
            1 => ['id' => 1, 'nama' => 'Dapur Bu Sari', 'deskripsi' => 'Aneka makanan rumahan', 'jenis' => 'satuan'],
            2 => ['id' => 2, 'nama' => 'Kantin Sehat', 'deskripsi' => 'Pilihan makanan sehat', 'jenis' => 'prasmanan'],
            3 => ['id' => 3, 'nama' => 'Warung Barokah', 'deskripsi' => 'Makanan enak dan terjangkau', 'jenis' => 'prasmanan'],
            4 => ['id' => 4, 'nama' => 'Kantin Maju', 'deskripsi' => 'Beragam pilihan makanan', 'jenis' => 'prasmanan'],
            5 => ['id' => 5, 'nama' => 'Resto Kampus', 'deskripsi' => 'Menu makanan dan lauk pilihan', 'jenis' => 'prasmanan'],
            6 => ['id' => 6, 'nama' => 'Pojok Nusantara', 'deskripsi' => 'Makanan nusantara dan gorengan', 'jenis' => 'satuan'],
            7 => ['id' => 7, 'nama' => 'Kedai Kita', 'deskripsi' => 'Camilan dan gorengan favorit', 'jenis' => 'satuan'],
            8 => ['id' => 8, 'nama' => 'Dapoer Rasa', 'deskripsi' => 'Makanan dan aneka gorengan', 'jenis' => 'satuan'],
        ];
    }

    public static function menus(): array
    {
        $item = static function (
            string $nama,
            string $kategori,
            int $harga,
            string $gambar,
            string $deskripsi,
            array $pilihan = [],
            string $satuan = 'porsi'
        ): array {
            return [
                'nama' => $nama,
                'kategori' => $kategori,
                'harga' => $harga,
                'harga_pilihan' => $pilihan,
                'satuan' => $satuan,
                'gambar' => $gambar,
                'deskripsi' => $deskripsi,
            ];
        };

        $pilihanPorsi = [2000, 3000, 4000, 5000];

        return [
            // TENANT 1: DAPUR BU SARI
            1 => [
                $item('Nasi Ayam', 'Makanan', 15000, '/images/ayam-goreng.jpg', 'Nasi dengan lauk ayam dan sayuran.'),
                $item('Ayam Geprek', 'Makanan', 13000, '/images/ayam-geprek.png', 'Ayam goreng dengan sambal geprek.'),
                $item('Nasi Goreng', 'Makanan', 12000, '/images/nasi-goreng.jpg', 'Nasi goreng dengan bumbu pilihan.'),
                $item('Mie Goreng', 'Makanan', 10000, '/images/mie-goreng.png', 'Mie goreng dengan rasa gurih.'),
            ],

            // TENANT 2: KANTIN SEHAT
            2 => [
                $item('Nasi Putih', 'Makanan', 5000, '/images/nasi-putih.jpeg', 'Nasi putih hangat.'),
                $item('Ayam Goreng', 'Lauk', 8000, '/images/ayam-goreng2.jpg', 'Ayam goreng berbumbu.', [], 'potong'),
                $item('Telur Balado', 'Lauk', 5000, '/images/telur-balado.jpeg', 'Telur dengan sambal balado.', [], 'butir'),
                $item('Tumis Sayur', 'Sayuran', 2000, '/images/sayur-tumis.jpg', 'Sayuran tumis; pilih harga sesuai porsi.', $pilihanPorsi),
                $item('Tempe Goreng', 'Lauk', 2000, '/images/tempe-goreng.jpeg', 'Tempe goreng renyah.', [], 'potong'),
            ],

            // TENANT 3: WARUNG BAROKAH
            3 => [
                $item('Nasi Putih', 'Makanan', 5000, '/images/nasi-putih.jpeg', 'Nasi putih hangat.'),
                $item('Rendang', 'Lauk', 10000, '/images/rendang-ayam.jpeg', 'Rendang dengan bumbu rempah.', [], 'potong'),
                $item('Ayam Gulai', 'Lauk', 10000, '/images/ayam-gulai.jpeg', 'Ayam dengan kuah gulai.', [], 'potong'),
                $item('Telur Balado', 'Lauk', 5000, '/images/telur-balado2.jpeg', 'Telur dengan sambal balado.', [], 'butir'),
                $item('Daun Singkong', 'Sayuran', 2000, '/images/gulai-singkong.jpeg', 'Sayur daun singkong; pilih harga sesuai porsi.', $pilihanPorsi),
            ],

            // TENANT 4: KANTIN MAJU
            4 => [
                $item('Nasi Putih', 'Makanan', 5000, '/images/nasi-putih.jpeg', 'Nasi putih hangat.'),
                $item('Ayam Goreng', 'Lauk', 8000, '/images/ayam-goreng.jpg', 'Ayam goreng gurih.', [], 'potong'),
                $item('Ikan Goreng', 'Lauk', 9000, '/images/ikan-goreng.jpg', 'Ikan goreng dengan bumbu sederhana.', [], 'potong'),
                $item('Tahu Goreng', 'Lauk', 2000, '/images/tahu-goreng.jpg', 'Tahu goreng gurih.', [], 'potong'),
                $item('Sayur Tumis', 'Sayuran', 2000, '/images/sayur-tumis.jpg', 'Sayuran tumis; pilih harga sesuai porsi.', $pilihanPorsi),
            ],

            // TENANT 5: RESTO KAMPUS
            5 => [
                $item('Nasi Putih', 'Makanan', 5000, '/images/nasi-putih.jpeg', 'Nasi putih hangat.'),
                $item('Ayam Kecap', 'Lauk', 9000, '/images/ayam-kecap.jpeg', 'Ayam dengan bumbu kecap.', [], 'potong'),
                $item('Telur Dadar', 'Lauk', 5000, '/images/telur-dadar.jpg', 'Telur dadar gurih.', [], 'potong'),
                $item('Orek Tempe', 'Lauk', 2000, '/images/orek-tempe.jpg', 'Orek tempe; pilih harga sesuai porsi.', $pilihanPorsi),
                $item('Sayur Sop', 'Sayuran', 2000, '/images/sayur-sop.jpeg', 'Sayur sop; pilih harga sesuai porsi.', $pilihanPorsi),
            ],

            // TENANT 6: POJOK NUSANTARA
            6 => [
                $item('Nasi Rendang', 'Makanan', 18000, '/images/rendang-ayam.jpeg', 'Nasi dengan rendang.'),
                $item('Pempek', 'Makanan', 10000, '/images/pempek.jpeg', 'Pempek dengan kuah cuko.'),
                $item('Bakwan', 'Gorengan', 2000, '/images/bakwan.jpeg', 'Bakwan sayur renyah.', [], 'buah'),
                $item('Pisang Goreng', 'Gorengan', 2000, '/images/pisang-goreng.jpeg', 'Pisang goreng hangat.', [], 'buah'),
                $item('Tahu Isi', 'Gorengan', 2000, '/images/tahu-isi.jpeg', 'Tahu isi sayuran.', [], 'buah'),
            ],

            // TENANT 7: KEDAI KITA
            7 => [
                $item('Roti Bakar', 'Makanan', 8000, '/images/roti-bakar.jpeg', 'Roti bakar dengan isian manis.'),
                $item('Pisang Goreng', 'Gorengan', 2000, '/images/pisang-goreng2.jpg', 'Pisang goreng renyah.', [], 'buah'),
                $item('Risoles', 'Gorengan', 2500, '/images/risoles.jpeg', 'Risoles dengan isian gurih.', [], 'buah'),
                $item('Pastel', 'Gorengan', 2500, '/images/pastel.jpg', 'Pastel dengan isian sayuran.', [], 'buah'),
                $item('Bakwan', 'Gorengan', 2000, '/images/bakwan2.jpg', 'Bakwan sayur renyah.', [], 'buah'),
            ],

            // TENANT 8: DAPOER RASA
            8 => [
                $item('Sate Ayam', 'Makanan', 15000, '/images/sate-ayam.jpeg', 'Sate ayam dengan bumbu kacang.'),
                $item('Bakso', 'Makanan', 13000, '/images/bakso.jpeg', 'Bakso dengan kuah gurih.'),
                $item('Risoles', 'Gorengan', 2500, '/images/risoles2.jpeg', 'Risoles dengan isian gurih.', [], 'buah'),
                $item('Tahu Isi', 'Gorengan', 2000, '/images/tahu-isi2.jpg', 'Tahu isi sayuran.', [], 'buah'),
                $item('Tempe Goreng', 'Gorengan', 2000, '/images/tempe-goreng2.jpg', 'Tempe goreng renyah.', [], 'potong'),
            ],
        ];
    }
}