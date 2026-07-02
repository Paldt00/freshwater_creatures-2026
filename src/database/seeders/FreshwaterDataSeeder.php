<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Fish;
use App\Models\Region;
use App\Models\WebSetting;
use Illuminate\Database\Seeder;

class FreshwaterDataSeeder extends Seeder
{
    public function run(): void
    {
        WebSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Sistem Informasi Ikan Air Tawar',
                'hero_title' => 'Ensiklopedia Digital Ikan Air Tawar Indonesia',
                'hero_subtitle' => 'Temukan informasi ikan air tawar berdasarkan wilayah, kategori, habitat, makanan, ciri-ciri, dan nama ilmiah secara terstruktur.',
                'contact_email' => 'admin@ikan.test',
                'footer_text' => 'Website edukatif untuk mendukung dokumentasi keanekaragaman ikan air tawar.',
            ]
        );

        $sumatra = Region::query()->create([
            'name' => 'Sumatra',
            'description' => 'Wilayah dengan keanekaragaman ikan air tawar yang tinggi, terutama pada sungai besar, danau, rawa, dan perairan gambut.',
        ]);

        $jawa = Region::query()->create([
            'name' => 'Jawa',
            'description' => 'Wilayah dengan berbagai perairan sungai, waduk, dan danau yang menjadi habitat ikan air tawar.',
        ]);

        $kalimantan = Region::query()->create([
            'name' => 'Kalimantan',
            'description' => 'Wilayah yang kaya sungai besar dan kawasan rawa sebagai habitat berbagai spesies ikan air tawar.',
        ]);

        $konsumsi = Category::query()->create([
            'name' => 'Ikan Konsumsi',
            'description' => 'Kelompok ikan air tawar yang umum dimanfaatkan sebagai sumber pangan.',
        ]);

        $hias = Category::query()->create([
            'name' => 'Ikan Hias',
            'description' => 'Kelompok ikan air tawar yang dikenal karena bentuk dan warna tubuhnya.',
        ]);

        $endemik = Category::query()->create([
            'name' => 'Ikan Endemik',
            'description' => 'Kelompok ikan yang memiliki persebaran alami terbatas pada wilayah tertentu.',
        ]);

        Fish::query()->create([
            'region_id' => $sumatra->id,
            'category_id' => $konsumsi->id,
            'name' => 'Ikan Baung',
            'scientific_name' => 'Hemibagrus nemurus',
            'habitat' => 'Sungai, danau, dan perairan berarus lambat',
            'food' => 'Ikan kecil, udang, serangga air, dan organisme dasar',
            'characteristics' => 'Memiliki tubuh memanjang, kumis panjang, dan aktif mencari makanan di dasar perairan.',
            'description' => 'Ikan Baung merupakan ikan air tawar yang banyak ditemukan di sungai dan danau. Ikan ini dikenal sebagai ikan konsumsi bernilai ekonomi.',
            'is_featured' => true,
            'is_published' => true,
        ]);

        Fish::query()->create([
            'region_id' => $jawa->id,
            'category_id' => $konsumsi->id,
            'name' => 'Ikan Nilem',
            'scientific_name' => 'Osteochilus vittatus',
            'habitat' => 'Sungai, kolam, dan danau',
            'food' => 'Fitoplankton, alga, dan bahan organik',
            'characteristics' => 'Tubuh berwarna keperakan, hidup berkelompok, dan banyak dibudidayakan.',
            'description' => 'Ikan Nilem merupakan ikan air tawar yang umum ditemukan di wilayah Jawa dan banyak dimanfaatkan untuk konsumsi.',
            'is_featured' => true,
            'is_published' => true,
        ]);

        Fish::query()->create([
            'region_id' => $kalimantan->id,
            'category_id' => $hias->id,
            'name' => 'Ikan Arwana',
            'scientific_name' => 'Scleropages formosus',
            'habitat' => 'Sungai berarus tenang, rawa, dan kawasan perairan gambut',
            'food' => 'Serangga, ikan kecil, dan hewan air kecil',
            'characteristics' => 'Memiliki sisik besar, tubuh memanjang, dan warna menarik sehingga populer sebagai ikan hias.',
            'description' => 'Ikan Arwana merupakan salah satu ikan air tawar yang terkenal karena nilai estetika dan nilai ekonominya.',
            'is_featured' => true,
            'is_published' => true,
        ]);

        Fish::query()->create([
            'region_id' => $sumatra->id,
            'category_id' => $endemik->id,
            'name' => 'Ikan Bilih',
            'scientific_name' => 'Mystacoleucus padangensis',
            'habitat' => 'Danau Singkarak dan aliran sungai sekitarnya',
            'food' => 'Plankton dan organisme kecil',
            'characteristics' => 'Berukuran kecil, hidup bergerombol, dan dikenal sebagai ikan khas Danau Singkarak.',
            'description' => 'Ikan Bilih merupakan ikan air tawar khas Sumatra Barat yang memiliki nilai ekonomi dan budaya bagi masyarakat sekitar.',
            'is_featured' => false,
            'is_published' => true,
        ]);
    }
}
