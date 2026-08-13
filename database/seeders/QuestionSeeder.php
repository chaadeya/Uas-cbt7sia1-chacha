<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $bahasaIndonesia = Subject::firstOrCreate(
            ['name' => 'Bahasa Indonesia'],
            ['description' => 'Mata pelajaran Bahasa Indonesia', 'is_active' => true]
        );
        $ips = Subject::firstOrCreate(
            ['name' => 'IPS'],
            ['description' => 'Ilmu Pengetahuan Sosial', 'is_active' => true]
        );
        $pjok = Subject::firstOrCreate(
            ['name' => 'PJOK'],
            ['description' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'is_active' => true]
        );

        DB::transaction(function () use ($bahasaIndonesia, $ips, $pjok) {
            $this->seedSubjectQuestions($bahasaIndonesia->id, $this->bahasaIndonesiaBank(), 150);
            $this->seedSubjectQuestions($ips->id, $this->ipsBank(), 150);
            $this->seedSubjectQuestions($pjok->id, $this->pjokBank(), 150);
        });
    }

    /**
     * =========================================================
     * GENERATOR: bank soal unik -> N soal
     * Kalau bank soal >= target, langsung dipotong (SEMUA UNIK, tanpa pengulangan).
     * Kalau bank soal < target, sisa kekurangan diisi dari pengulangan bank
     * dengan urutan opsi diacak ulang (supaya jawaban benar tidak selalu di posisi sama).
     * =========================================================
     */
    protected function seedSubjectQuestions(int $subjectId, array $bank, int $total): void
    {
        $bankCount = count($bank);
        $generated = [];

        if ($bankCount >= $total) {
            // Semua soal yang dipakai adalah soal unik, tidak ada pengulangan.
            $selected = array_slice($bank, 0, $total);
            foreach ($selected as $item) {
                $generated[] = $this->shuffleOptions($item);
            }
        } else {
            // Pakai semua soal unik dulu (masing-masing tetap diacak posisinya)
            foreach ($bank as $item) {
                $generated[] = $this->shuffleOptions($item);
            }

            // Sisa kekurangan diisi dari pengulangan bank dengan opsi diacak ulang
            $remaining = $total - $bankCount;
            $i = 0;
            while ($remaining > 0) {
                $item = $bank[$i % $bankCount];
                $generated[] = $this->shuffleOptions($item);
                $i++;
                $remaining--;
            }
        }

        foreach ($generated as $item) {
            $questionId = DB::table('questions')->insertGetId([
                'subject_id'  => $subjectId,
                'payload'     => $item['question'],
                'score'       => 1,
                'description' => null,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $answers = [];
            foreach ($item['options'] as $letter => $text) {
                $answers[] = [
                    'question_id' => $questionId,
                    'option'      => $letter,
                    'text'        => $text,
                    'is_correct'  => $letter === $item['correct'],
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            DB::table('answers')->insert($answers);
        }
    }

    protected function shuffleOptions(array $item): array
    {
        $values = array_values($item['options']);
        $correctText = $item['options'][$item['correct']];

        $keys = ['A', 'B', 'C', 'D'];
        shuffle($values);

        $newOptions = array_combine($keys, $values);
        $newCorrect = array_search($correctText, $newOptions, true);

        return [
            'question' => $item['question'],
            'options'  => $newOptions,
            'correct'  => $newCorrect,
        ];
    }

    /**
     * =========================================================
     * BANK SOAL BAHASA INDONESIA (unik)
     * =========================================================
     */
    protected function bahasaIndonesiaBank(): array
    {
        return [
            // --- Tata bahasa & jenis kata ---
            ['question' => 'Kalimat yang digunakan untuk menyampaikan gagasan utama disebut ...', 'options' => ['A' => 'kalimat utama', 'B' => 'kalimat penjelas', 'C' => 'kalimat tanya', 'D' => 'kalimat seru'], 'correct' => 'A'],
            ['question' => 'Gagasan utama dalam sebuah paragraf disebut juga ...', 'options' => ['A' => 'ide pokok', 'B' => 'kata penghubung', 'C' => 'kalimat penjelas', 'D' => 'judul'], 'correct' => 'A'],
            ['question' => 'Kata yang digunakan untuk menanyakan sesuatu disebut kata ...', 'options' => ['A' => 'tanya', 'B' => 'kerja', 'C' => 'sifat', 'D' => 'bilangan'], 'correct' => 'A'],
            ['question' => 'Lawan kata "tinggi" adalah ...', 'options' => ['A' => 'rendah', 'B' => 'besar', 'C' => 'panjang', 'D' => 'lebar'], 'correct' => 'A'],
            ['question' => 'Persamaan kata "indah" adalah ...', 'options' => ['A' => 'elok', 'B' => 'buruk', 'C' => 'sempit', 'D' => 'keras'], 'correct' => 'A'],
            ['question' => 'Tanda baca yang digunakan pada akhir kalimat tanya adalah ...', 'options' => ['A' => '?', 'B' => '.', 'C' => '!', 'D' => ','], 'correct' => 'A'],
            ['question' => 'Tanda baca yang digunakan pada akhir kalimat perintah adalah ...', 'options' => ['A' => '!', 'B' => '.', 'C' => '?', 'D' => ';'], 'correct' => 'A'],
            ['question' => 'Kata "berlari" termasuk kata ...', 'options' => ['A' => 'kerja', 'B' => 'benda', 'C' => 'sifat', 'D' => 'bilangan'], 'correct' => 'A'],
            ['question' => 'Kata "rumah" termasuk kata ...', 'options' => ['A' => 'benda', 'B' => 'kerja', 'C' => 'sifat', 'D' => 'tanya'], 'correct' => 'A'],
            ['question' => 'Kata "cantik" termasuk kata ...', 'options' => ['A' => 'sifat', 'B' => 'kerja', 'C' => 'benda', 'D' => 'bilangan'], 'correct' => 'A'],
            ['question' => 'Kata "dan" termasuk kata ...', 'options' => ['A' => 'hubung', 'B' => 'benda', 'C' => 'kerja', 'D' => 'sifat'], 'correct' => 'A'],
            ['question' => 'Kata "karena" biasanya digunakan untuk menunjukkan ...', 'options' => ['A' => 'sebab', 'B' => 'pilihan', 'C' => 'urutan', 'D' => 'tempat'], 'correct' => 'A'],
            ['question' => 'Kata "kemudian" menunjukkan hubungan ...', 'options' => ['A' => 'urutan waktu', 'B' => 'sebab', 'C' => 'pertentangan', 'D' => 'pilihan'], 'correct' => 'A'],
            ['question' => 'Kata "walaupun" menyatakan hubungan ...', 'options' => ['A' => 'pertentangan', 'B' => 'sebab akibat', 'C' => 'perbandingan', 'D' => 'tujuan'], 'correct' => 'A'],
            ['question' => 'Kata "agar" biasanya digunakan untuk menyatakan ...', 'options' => ['A' => 'tujuan', 'B' => 'sebab', 'C' => 'waktu', 'D' => 'tempat'], 'correct' => 'A'],
            ['question' => 'Kata "meskipun" termasuk kata penghubung yang menyatakan ...', 'options' => ['A' => 'pertentangan', 'B' => 'penjumlahan', 'C' => 'urutan waktu', 'D' => 'tujuan'], 'correct' => 'A'],
            ['question' => 'Awalan "ber-" pada kata "berlari" menyatakan makna ...', 'options' => ['A' => 'melakukan pekerjaan', 'B' => 'memiliki', 'C' => 'menjadi', 'D' => 'menuju'], 'correct' => 'A'],
            ['question' => 'Kata "dibaca" merupakan kata kerja berimbuhan ...', 'options' => ['A' => 'awalan', 'B' => 'akhiran', 'C' => 'sisipan', 'D' => 'gabungan'], 'correct' => 'A'],
            ['question' => 'Kata ulang "berjalan-jalan" termasuk jenis kata ulang ...', 'options' => ['A' => 'berimbuhan', 'B' => 'dwipurwa', 'C' => 'dwilingga', 'D' => 'semu'], 'correct' => 'A'],
            ['question' => 'Kalimat "Buku itu dibaca oleh Andi." merupakan kalimat ...', 'options' => ['A' => 'pasif', 'B' => 'aktif', 'C' => 'tanya', 'D' => 'perintah'], 'correct' => 'A'],
            ['question' => 'Kalimat "Andi membaca buku itu." merupakan kalimat ...', 'options' => ['A' => 'aktif', 'B' => 'pasif', 'C' => 'tanya', 'D' => 'perintah'], 'correct' => 'A'],
            ['question' => 'Kalimat "Dilarang membuang sampah sembarangan!" termasuk kalimat ...', 'options' => ['A' => 'perintah', 'B' => 'berita', 'C' => 'tanya', 'D' => 'harapan'], 'correct' => 'A'],
            ['question' => 'Kalimat yang berisi ajakan biasanya menggunakan kata ...', 'options' => ['A' => 'mari', 'B' => 'tetapi', 'C' => 'karena', 'D' => 'sehingga'], 'correct' => 'A'],
            ['question' => 'Kalimat yang berisi pertanyaan disebut kalimat ...', 'options' => ['A' => 'interogatif', 'B' => 'deklaratif', 'C' => 'imperatif', 'D' => 'persuasif'], 'correct' => 'A'],
            ['question' => 'Kalimat yang berisi perintah disebut kalimat ...', 'options' => ['A' => 'imperatif', 'B' => 'interogatif', 'C' => 'deklaratif', 'D' => 'deskriptif'], 'correct' => 'A'],
            ['question' => 'Kalimat yang menyatakan informasi disebut kalimat ...', 'options' => ['A' => 'deklaratif', 'B' => 'interogatif', 'C' => 'imperatif', 'D' => 'seruan'], 'correct' => 'A'],
            ['question' => 'Kalimat yang menggunakan kata baku yang benar adalah "Saya sudah ... itu."', 'options' => ['A' => 'tahu', 'B' => 'tau', 'C' => 'taw', 'D' => 'thu'], 'correct' => 'A'],
            ['question' => 'Kata baku dari "apotik" adalah ...', 'options' => ['A' => 'apotek', 'B' => 'apotika', 'C' => 'apoteker', 'D' => 'apotic'], 'correct' => 'A'],
            ['question' => 'Kata baku dari "nasehat" adalah ...', 'options' => ['A' => 'nasihat', 'B' => 'nasehad', 'C' => 'nasiat', 'D' => 'nasehet'], 'correct' => 'A'],
            ['question' => 'Kata baku dari "praktek" adalah ...', 'options' => ['A' => 'praktik', 'B' => 'prahtek', 'C' => 'praktic', 'D' => 'praktekk'], 'correct' => 'A'],
            ['question' => 'Kalimat yang menggunakan huruf kapital dengan benar adalah ...', 'options' => ['A' => 'Kami tinggal di Kota Bandung.', 'B' => 'Kami tinggal di kota Bandung.', 'C' => 'Kami tinggal Di Kota bandung.', 'D' => 'kami tinggal di Kota Bandung.'], 'correct' => 'A'],
            ['question' => 'Kalimat efektif yang tepat di bawah ini adalah ...', 'options' => ['A' => 'Kami semua setuju dengan usulan itu.', 'B' => 'Para siswa-siswa sedang belajar.', 'C' => 'Kami semua semuanya setuju.', 'D' => 'Bagi yang belum, harap dikumpulkan.'], 'correct' => 'A'],
            ['question' => 'Kata penghubung yang tepat untuk melengkapi kalimat "Aku ingin pergi, ... hujan turun deras." adalah ...', 'options' => ['A' => 'tetapi', 'B' => 'dan', 'C' => 'karena', 'D' => 'atau'], 'correct' => 'A'],

            // --- Majas & puisi ---
            ['question' => 'Majas yang membandingkan dua hal secara langsung dengan kata "seperti" atau "bagai" disebut ...', 'options' => ['A' => 'simile', 'B' => 'metafora', 'C' => 'personifikasi', 'D' => 'hiperbola'], 'correct' => 'A'],
            ['question' => 'Majas yang memberikan sifat manusia kepada benda mati disebut ...', 'options' => ['A' => 'personifikasi', 'B' => 'metafora', 'C' => 'litotes', 'D' => 'ironi'], 'correct' => 'A'],
            ['question' => 'Majas yang melebih-lebihkan sesuatu disebut ...', 'options' => ['A' => 'hiperbola', 'B' => 'simile', 'C' => 'metafora', 'D' => 'litotes'], 'correct' => 'A'],
            ['question' => 'Pantun bersajak ...', 'options' => ['A' => 'a-b-a-b', 'B' => 'a-a-a-a', 'C' => 'a-b-b-a', 'D' => 'a-a-b-b'], 'correct' => 'A'],
            ['question' => 'Bagian pantun yang berisi sampiran terletak pada baris ke ...', 'options' => ['A' => '1 dan 2', 'B' => '2 dan 3', 'C' => '3 dan 4', 'D' => '1 dan 4'], 'correct' => 'A'],
            ['question' => 'Bagian pantun yang berisi isi terletak pada baris ke ...', 'options' => ['A' => '3 dan 4', 'B' => '1 dan 2', 'C' => '2 dan 3', 'D' => '1 dan 4'], 'correct' => 'A'],
            ['question' => 'Puisi lama yang terdiri dari dua baris dan berisi nasihat disebut ...', 'options' => ['A' => 'gurindam', 'B' => 'pantun', 'C' => 'syair', 'D' => 'mantra'], 'correct' => 'A'],
            ['question' => 'Puisi lama yang setiap baitnya terdiri dari empat baris dan bersajak sama disebut ...', 'options' => ['A' => 'syair', 'B' => 'pantun', 'C' => 'gurindam', 'D' => 'mantra'], 'correct' => 'A'],
            ['question' => 'Kata "kambing hitam" dalam kalimat "Ia dijadikan kambing hitam" merupakan bentuk ...', 'options' => ['A' => 'idiom', 'B' => 'peribahasa', 'C' => 'sinonim', 'D' => 'antonim'], 'correct' => 'A'],
            ['question' => 'Peribahasa "berat sama dipikul, ringan sama dijinjing" bermakna ...', 'options' => ['A' => 'saling membantu', 'B' => 'saling bermusuhan', 'C' => 'bekerja sendiri', 'D' => 'tidak peduli sesama'], 'correct' => 'A'],
            ['question' => 'Peribahasa "air beriak tanda tak dalam" bermakna ...', 'options' => ['A' => 'orang yang banyak bicara biasanya kurang berilmu', 'B' => 'sungai yang dalam pasti tenang', 'C' => 'air yang beriak pasti dalam', 'D' => 'orang pendiam pasti bodoh'], 'correct' => 'A'],
            ['question' => 'Kata "meja hijau" dalam kalimat "Kasus itu dibawa ke meja hijau" bermakna ...', 'options' => ['A' => 'pengadilan', 'B' => 'meja berwarna hijau', 'C' => 'ruang tamu', 'D' => 'kantor polisi'], 'correct' => 'A'],

            // --- Sastra & unsur cerita ---
            ['question' => 'Cerita yang berasal dari khayalan disebut cerita ...', 'options' => ['A' => 'fiksi', 'B' => 'nonfiksi', 'C' => 'ilmiah', 'D' => 'laporan'], 'correct' => 'A'],
            ['question' => 'Buku yang berisi fakta dan pengetahuan disebut ...', 'options' => ['A' => 'nonfiksi', 'B' => 'fiksi', 'C' => 'dongeng', 'D' => 'cerpen'], 'correct' => 'A'],
            ['question' => 'Tokoh yang memiliki peran utama dalam cerita disebut ...', 'options' => ['A' => 'tokoh utama', 'B' => 'tokoh tambahan', 'C' => 'narator', 'D' => 'pembaca'], 'correct' => 'A'],
            ['question' => 'Tempat terjadinya suatu peristiwa dalam cerita disebut ...', 'options' => ['A' => 'latar tempat', 'B' => 'tema', 'C' => 'amanat', 'D' => 'alur'], 'correct' => 'A'],
            ['question' => 'Pesan yang ingin disampaikan pengarang disebut ...', 'options' => ['A' => 'amanat', 'B' => 'tema', 'C' => 'tokoh', 'D' => 'latar'], 'correct' => 'A'],
            ['question' => 'Urutan peristiwa dalam cerita disebut ...', 'options' => ['A' => 'alur', 'B' => 'tema', 'C' => 'amanat', 'D' => 'judul'], 'correct' => 'A'],
            ['question' => 'Pokok pembicaraan dalam cerita disebut ...', 'options' => ['A' => 'tema', 'B' => 'latar', 'C' => 'tokoh', 'D' => 'alur'], 'correct' => 'A'],
            ['question' => 'Karya sastra yang mengisahkan kehidupan tokoh secara panjang dan kompleks disebut ...', 'options' => ['A' => 'novel', 'B' => 'cerpen', 'C' => 'puisi', 'D' => 'drama'], 'correct' => 'A'],
            ['question' => 'Karya sastra fiksi yang lebih pendek dari novel disebut ...', 'options' => ['A' => 'cerpen', 'B' => 'novel', 'C' => 'gurindam', 'D' => 'syair'], 'correct' => 'A'],
            ['question' => 'Bagian awal cerita yang memperkenalkan tokoh dan latar disebut ...', 'options' => ['A' => 'orientasi', 'B' => 'komplikasi', 'C' => 'resolusi', 'D' => 'koda'], 'correct' => 'A'],
            ['question' => 'Bagian cerita yang berisi puncak permasalahan disebut ...', 'options' => ['A' => 'komplikasi', 'B' => 'orientasi', 'C' => 'resolusi', 'D' => 'koda'], 'correct' => 'A'],
            ['question' => 'Bagian cerita yang berisi penyelesaian masalah disebut ...', 'options' => ['A' => 'resolusi', 'B' => 'orientasi', 'C' => 'komplikasi', 'D' => 'koda'], 'correct' => 'A'],
            ['question' => 'Sudut pandang orang pertama ditandai dengan penggunaan kata ganti ...', 'options' => ['A' => 'aku, saya', 'B' => 'dia, ia', 'C' => 'kamu, engkau', 'D' => 'mereka'], 'correct' => 'A'],
            ['question' => 'Sudut pandang orang ketiga ditandai dengan penggunaan kata ganti ...', 'options' => ['A' => 'dia, ia', 'B' => 'aku, saya', 'C' => 'kamu, engkau', 'D' => 'kita'], 'correct' => 'A'],
            ['question' => 'Dongeng yang tokohnya berupa hewan dan berperilaku seperti manusia disebut ...', 'options' => ['A' => 'fabel', 'B' => 'legenda', 'C' => 'mite', 'D' => 'sage'], 'correct' => 'A'],
            ['question' => 'Cerita rakyat yang berkaitan dengan asal-usul suatu tempat disebut ...', 'options' => ['A' => 'legenda', 'B' => 'fabel', 'C' => 'mite', 'D' => 'sage'], 'correct' => 'A'],

            // --- Teks & ragam tulisan ---
            ['question' => 'Teks yang bertujuan menggambarkan suatu objek secara rinci disebut teks ...', 'options' => ['A' => 'deskripsi', 'B' => 'narasi', 'C' => 'eksposisi', 'D' => 'persuasi'], 'correct' => 'A'],
            ['question' => 'Teks yang menceritakan rangkaian peristiwa secara runtut disebut teks ...', 'options' => ['A' => 'narasi', 'B' => 'deskripsi', 'C' => 'eksposisi', 'D' => 'persuasi'], 'correct' => 'A'],
            ['question' => 'Teks yang bertujuan memberikan informasi atau penjelasan disebut teks ...', 'options' => ['A' => 'eksposisi', 'B' => 'narasi', 'C' => 'deskripsi', 'D' => 'persuasi'], 'correct' => 'A'],
            ['question' => 'Teks yang bertujuan membujuk atau memengaruhi pembaca disebut teks ...', 'options' => ['A' => 'persuasi', 'B' => 'narasi', 'C' => 'deskripsi', 'D' => 'eksposisi'], 'correct' => 'A'],
            ['question' => 'Struktur teks laporan hasil observasi terdiri atas ...', 'options' => ['A' => 'pernyataan umum dan deskripsi bagian', 'B' => 'orientasi, komplikasi, resolusi', 'C' => 'tesis, argumentasi, penegasan ulang', 'D' => 'pembuka, isi, penutup'], 'correct' => 'A'],
            ['question' => 'Ide pokok sebuah paragraf biasanya terdapat pada ...', 'options' => ['A' => 'kalimat utama', 'B' => 'kalimat penjelas', 'C' => 'kalimat penutup', 'D' => 'seluruh paragraf secara acak'], 'correct' => 'A'],
            ['question' => 'Paragraf yang kalimat utamanya berada di awal disebut paragraf ...', 'options' => ['A' => 'deduktif', 'B' => 'induktif', 'C' => 'campuran', 'D' => 'naratif'], 'correct' => 'A'],
            ['question' => 'Paragraf yang kalimat utamanya berada di akhir disebut paragraf ...', 'options' => ['A' => 'induktif', 'B' => 'deduktif', 'C' => 'campuran', 'D' => 'deskriptif'], 'correct' => 'A'],
            ['question' => 'Ringkasan adalah bentuk tulisan yang ...', 'options' => ['A' => 'lebih singkat dari aslinya', 'B' => 'lebih panjang dari aslinya', 'C' => 'tanpa isi penting', 'D' => 'berupa dialog'], 'correct' => 'A'],
            ['question' => 'Surat yang ditulis untuk keperluan resmi disebut surat ...', 'options' => ['A' => 'resmi', 'B' => 'pribadi', 'C' => 'undangan santai', 'D' => 'curhat'], 'correct' => 'A'],
            ['question' => 'Bagian surat yang berisi identitas pengirim dan tanggal penulisan disebut ...', 'options' => ['A' => 'kepala surat', 'B' => 'isi surat', 'C' => 'penutup surat', 'D' => 'lampiran'], 'correct' => 'A'],
            ['question' => 'Wawancara dilakukan dengan cara ...', 'options' => ['A' => 'tanya jawab dengan narasumber', 'B' => 'membaca buku saja', 'C' => 'menonton televisi', 'D' => 'menulis puisi'], 'correct' => 'A'],
            ['question' => 'Kalimat tanya yang tepat untuk menanyakan waktu adalah ...', 'options' => ['A' => 'Kapan kamu berangkat?', 'B' => 'Mengapa kamu terlambat?', 'C' => 'Di mana kamu tinggal?', 'D' => 'Bagaimana caramu belajar?'], 'correct' => 'A'],
            ['question' => 'Kalimat tanya yang tepat untuk menanyakan alasan adalah ...', 'options' => ['A' => 'Mengapa kamu terlambat?', 'B' => 'Kapan kamu berangkat?', 'C' => 'Di mana kamu tinggal?', 'D' => 'Siapa namamu?'], 'correct' => 'A'],
            ['question' => 'Kalimat tanya yang tepat untuk menanyakan tempat adalah ...', 'options' => ['A' => 'Di mana kamu tinggal?', 'B' => 'Kapan kamu berangkat?', 'C' => 'Mengapa kamu terlambat?', 'D' => 'Siapa namamu?'], 'correct' => 'A'],
        ];
    }

    /**
     * =========================================================
     * BANK SOAL IPS (unik)
     * =========================================================
     */
    protected function ipsBank(): array
    {
        return [
            // --- Ekonomi ---
            ['question' => 'Kegiatan menghasilkan barang atau jasa disebut ...', 'options' => ['A' => 'produksi', 'B' => 'konsumsi', 'C' => 'distribusi', 'D' => 'transportasi'], 'correct' => 'A'],
            ['question' => 'Kegiatan menggunakan barang atau jasa disebut ...', 'options' => ['A' => 'konsumsi', 'B' => 'produksi', 'C' => 'distribusi', 'D' => 'ekspor'], 'correct' => 'A'],
            ['question' => 'Kegiatan menyalurkan barang dari produsen kepada konsumen disebut ...', 'options' => ['A' => 'distribusi', 'B' => 'produksi', 'C' => 'konsumsi', 'D' => 'investasi'], 'correct' => 'A'],
            ['question' => 'Orang yang menghasilkan barang atau jasa disebut ...', 'options' => ['A' => 'produsen', 'B' => 'konsumen', 'C' => 'distributor', 'D' => 'pembeli'], 'correct' => 'A'],
            ['question' => 'Orang yang menggunakan barang atau jasa disebut ...', 'options' => ['A' => 'konsumen', 'B' => 'produsen', 'C' => 'distributor', 'D' => 'pedagang'], 'correct' => 'A'],
            ['question' => 'Kegiatan menjual barang ke luar negeri disebut ...', 'options' => ['A' => 'ekspor', 'B' => 'impor', 'C' => 'distribusi', 'D' => 'produksi'], 'correct' => 'A'],
            ['question' => 'Kegiatan membeli barang dari luar negeri disebut ...', 'options' => ['A' => 'impor', 'B' => 'ekspor', 'C' => 'produksi', 'D' => 'konsumsi'], 'correct' => 'A'],
            ['question' => 'Kegiatan membeli dan menjual barang disebut ...', 'options' => ['A' => 'perdagangan', 'B' => 'pertanian', 'C' => 'produksi', 'D' => 'transportasi'], 'correct' => 'A'],
            ['question' => 'Tempat bertemunya penjual dan pembeli disebut ...', 'options' => ['A' => 'pasar', 'B' => 'sekolah', 'C' => 'kantor', 'D' => 'rumah sakit'], 'correct' => 'A'],
            ['question' => 'Alat tukar yang sah digunakan dalam kegiatan jual beli disebut ...', 'options' => ['A' => 'uang', 'B' => 'barter', 'C' => 'surat', 'D' => 'perjanjian'], 'correct' => 'A'],
            ['question' => 'Kegiatan tukar-menukar barang tanpa uang disebut ...', 'options' => ['A' => 'barter', 'B' => 'jual beli', 'C' => 'sewa', 'D' => 'pinjaman'], 'correct' => 'A'],
            ['question' => 'Modal, tenaga kerja, dan sumber daya alam termasuk ...', 'options' => ['A' => 'faktor produksi', 'B' => 'hasil produksi', 'C' => 'kegiatan konsumsi', 'D' => 'kegiatan distribusi'], 'correct' => 'A'],
            ['question' => 'Bank berfungsi sebagai tempat untuk ...', 'options' => ['A' => 'menyimpan dan meminjam uang', 'B' => 'menjual sayur', 'C' => 'belajar mengajar', 'D' => 'mengobati orang sakit'], 'correct' => 'A'],
            ['question' => 'Koperasi adalah badan usaha yang berlandaskan asas ...', 'options' => ['A' => 'kekeluargaan', 'B' => 'persaingan bebas', 'C' => 'monopoli', 'D' => 'individualisme'], 'correct' => 'A'],

            // --- Geografi & kenampakan alam ---
            ['question' => 'Peta digunakan untuk menunjukkan ...', 'options' => ['A' => 'letak suatu wilayah', 'B' => 'harga barang', 'C' => 'jumlah penduduk saja', 'D' => 'jenis pekerjaan'], 'correct' => 'A'],
            ['question' => 'Garis khatulistiwa melewati wilayah ...', 'options' => ['A' => 'Indonesia', 'B' => 'Australia saja', 'C' => 'Eropa', 'D' => 'Antartika'], 'correct' => 'A'],
            ['question' => 'Indonesia dikenal sebagai negara ...', 'options' => ['A' => 'kepulauan', 'B' => 'kontinental', 'C' => 'gurun', 'D' => 'kutub'], 'correct' => 'A'],
            ['question' => 'Gunung, sungai, dan danau termasuk kenampakan ...', 'options' => ['A' => 'alam', 'B' => 'buatan', 'C' => 'ekonomi', 'D' => 'sosial'], 'correct' => 'A'],
            ['question' => 'Jalan raya dan jembatan termasuk kenampakan ...', 'options' => ['A' => 'buatan', 'B' => 'alam', 'C' => 'laut', 'D' => 'hutan'], 'correct' => 'A'],
            ['question' => 'Alat yang digunakan untuk menunjukkan arah disebut ...', 'options' => ['A' => 'kompas', 'B' => 'termometer', 'C' => 'barometer', 'D' => 'mikroskop'], 'correct' => 'A'],
            ['question' => 'Simbol pada peta digunakan untuk ...', 'options' => ['A' => 'mewakili objek tertentu', 'B' => 'menghitung uang', 'C' => 'menentukan suhu', 'D' => 'mengukur berat'], 'correct' => 'A'],
            ['question' => 'Ibu kota negara Indonesia saat ini secara resmi dipindahkan ke ...', 'options' => ['A' => 'Nusantara di Kalimantan Timur', 'B' => 'Jakarta', 'C' => 'Surabaya', 'D' => 'Bandung'], 'correct' => 'A'],
            ['question' => 'Pulau terbesar di Indonesia adalah ...', 'options' => ['A' => 'Kalimantan', 'B' => 'Jawa', 'C' => 'Bali', 'D' => 'Madura'], 'correct' => 'A'],
            ['question' => 'Selat yang memisahkan Pulau Sumatra dan Pulau Jawa adalah ...', 'options' => ['A' => 'Selat Sunda', 'B' => 'Selat Malaka', 'C' => 'Selat Bali', 'D' => 'Selat Karimata'], 'correct' => 'A'],
            ['question' => 'Dataran yang terletak di ketinggian dan udaranya sejuk disebut ...', 'options' => ['A' => 'dataran tinggi', 'B' => 'dataran rendah', 'C' => 'pantai', 'D' => 'lembah'], 'correct' => 'A'],
            ['question' => 'Dataran yang terletak di ketinggian rendah dekat permukaan laut disebut ...', 'options' => ['A' => 'dataran rendah', 'B' => 'dataran tinggi', 'C' => 'pegunungan', 'D' => 'plato'], 'correct' => 'A'],

            // --- Sumber daya & kependudukan ---
            ['question' => 'Sumber daya alam yang dapat diperbarui contohnya ...', 'options' => ['A' => 'tumbuhan', 'B' => 'minyak bumi', 'C' => 'batu bara', 'D' => 'emas'], 'correct' => 'A'],
            ['question' => 'Sumber daya alam yang tidak dapat diperbarui contohnya ...', 'options' => ['A' => 'minyak bumi', 'B' => 'air', 'C' => 'angin', 'D' => 'tumbuhan'], 'correct' => 'A'],
            ['question' => 'Perubahan jumlah penduduk dari waktu ke waktu disebut ...', 'options' => ['A' => 'pertumbuhan penduduk', 'B' => 'produksi', 'C' => 'distribusi', 'D' => 'urbanisasi'], 'correct' => 'A'],
            ['question' => 'Perpindahan penduduk dari desa ke kota disebut ...', 'options' => ['A' => 'urbanisasi', 'B' => 'transmigrasi', 'C' => 'imigrasi', 'D' => 'emigrasi'], 'correct' => 'A'],
            ['question' => 'Perpindahan penduduk dari satu pulau padat ke pulau yang jarang penduduknya disebut ...', 'options' => ['A' => 'transmigrasi', 'B' => 'urbanisasi', 'C' => 'imigrasi', 'D' => 'emigrasi'], 'correct' => 'A'],
            ['question' => 'Mata pencaharian penduduk di daerah pantai banyak yang menjadi ...', 'options' => ['A' => 'nelayan', 'B' => 'petani teh', 'C' => 'peternak sapi pegunungan', 'D' => 'penambang salju'], 'correct' => 'A'],
            ['question' => 'Penduduk di daerah pertanian banyak bekerja sebagai ...', 'options' => ['A' => 'petani', 'B' => 'nelayan', 'C' => 'pelaut', 'D' => 'pedagang laut'], 'correct' => 'A'],
            ['question' => 'Penduduk di daerah pegunungan sering bekerja sebagai ...', 'options' => ['A' => 'petani sayur dan buah', 'B' => 'nelayan tangkap', 'C' => 'pembudidaya rumput laut', 'D' => 'pelaut jarak jauh'], 'correct' => 'A'],

            // --- Sejarah & budaya ---
            ['question' => 'Candi merupakan salah satu peninggalan ...', 'options' => ['A' => 'sejarah', 'B' => 'teknologi modern', 'C' => 'industri', 'D' => 'perdagangan'], 'correct' => 'A'],
            ['question' => 'Keberagaman suku dan budaya merupakan kekayaan ...', 'options' => ['A' => 'Indonesia', 'B' => 'perusahaan', 'C' => 'pasar', 'D' => 'sekolah'], 'correct' => 'A'],
            ['question' => 'Hari kemerdekaan Indonesia diperingati setiap tanggal ...', 'options' => ['A' => '17 Agustus', 'B' => '1 Juni', 'C' => '28 Oktober', 'D' => '10 November'], 'correct' => 'A'],
            ['question' => 'Sumpah Pemuda diikrarkan pada tanggal ...', 'options' => ['A' => '28 Oktober 1928', 'B' => '17 Agustus 1945', 'C' => '1 Juni 1945', 'D' => '10 November 1945'], 'correct' => 'A'],
            ['question' => 'Proklamasi kemerdekaan Indonesia dibacakan oleh ...', 'options' => ['A' => 'Ir. Soekarno', 'B' => 'Mohammad Hatta seorang diri', 'C' => 'Jenderal Sudirman', 'D' => 'Pangeran Diponegoro'], 'correct' => 'A'],
            ['question' => 'Lagu kebangsaan Indonesia berjudul ...', 'options' => ['A' => 'Indonesia Raya', 'B' => 'Garuda Pancasila', 'C' => 'Bagimu Negeri', 'D' => 'Halo-Halo Bandung'], 'correct' => 'A'],
            ['question' => 'Dasar negara Indonesia adalah ...', 'options' => ['A' => 'Pancasila', 'B' => 'UUD 1945 saja', 'C' => 'Sumpah Pemuda', 'D' => 'GBHN'], 'correct' => 'A'],
            ['question' => 'Rumah adat "Joglo" berasal dari daerah ...', 'options' => ['A' => 'Jawa Tengah', 'B' => 'Sumatra Barat', 'C' => 'Papua', 'D' => 'Kalimantan'], 'correct' => 'A'],
            ['question' => 'Tari Kecak berasal dari daerah ...', 'options' => ['A' => 'Bali', 'B' => 'Jawa Barat', 'C' => 'Sumatra Utara', 'D' => 'Sulawesi'], 'correct' => 'A'],
            ['question' => 'Alat musik Angklung berasal dari daerah ...', 'options' => ['A' => 'Jawa Barat', 'B' => 'Bali', 'C' => 'Papua', 'D' => 'Kalimantan'], 'correct' => 'A'],

            // --- Kerja sama & organisasi ---
            ['question' => 'Kerja sama antarnegara dilakukan untuk mencapai ...', 'options' => ['A' => 'kepentingan bersama', 'B' => 'perselisihan', 'C' => 'isolasi', 'D' => 'persaingan saja'], 'correct' => 'A'],
            ['question' => 'ASEAN merupakan organisasi kerja sama negara-negara di kawasan ...', 'options' => ['A' => 'Asia Tenggara', 'B' => 'Asia Timur', 'C' => 'Eropa', 'D' => 'Afrika'], 'correct' => 'A'],
            ['question' => 'PBB merupakan organisasi ...', 'options' => ['A' => 'perdamaian dunia', 'B' => 'olahraga dunia', 'C' => 'perdagangan lokal', 'D' => 'pendidikan satu negara'], 'correct' => 'A'],
            ['question' => 'Kerja sama antartetangga dalam membersihkan lingkungan disebut ...', 'options' => ['A' => 'gotong royong', 'B' => 'kompetisi', 'C' => 'perselisihan', 'D' => 'monopoli'], 'correct' => 'A'],
            ['question' => 'Musyawarah dilakukan untuk mencapai ...', 'options' => ['A' => 'mufakat', 'B' => 'perpecahan', 'C' => 'kekalahan sepihak', 'D' => 'keributan'], 'correct' => 'A'],
        ];
    }

    /**
     * =========================================================
     * BANK SOAL PJOK (unik)
     * =========================================================
     */
    protected function pjokBank(): array
    {
        return [
            // --- Gerak dasar ---
            ['question' => 'Gerakan berpindah tempat disebut gerak ...', 'options' => ['A' => 'lokomotor', 'B' => 'nonlokomotor', 'C' => 'manipulatif', 'D' => 'statis'], 'correct' => 'A'],
            ['question' => 'Gerakan membungkuk tanpa berpindah tempat termasuk gerak ...', 'options' => ['A' => 'nonlokomotor', 'B' => 'lokomotor', 'C' => 'manipulatif', 'D' => 'berlari'], 'correct' => 'A'],
            ['question' => 'Menendang bola termasuk gerak ...', 'options' => ['A' => 'manipulatif', 'B' => 'nonlokomotor', 'C' => 'lokomotor', 'D' => 'diam'], 'correct' => 'A'],
            ['question' => 'Contoh gerak lokomotor adalah ...', 'options' => ['A' => 'berlari', 'B' => 'membungkuk', 'C' => 'memutar badan', 'D' => 'menekuk siku'], 'correct' => 'A'],
            ['question' => 'Contoh gerak nonlokomotor adalah ...', 'options' => ['A' => 'memutar badan', 'B' => 'berlari', 'C' => 'berjalan', 'D' => 'melompat jauh'], 'correct' => 'A'],
            ['question' => 'Contoh gerak manipulatif adalah ...', 'options' => ['A' => 'melempar bola', 'B' => 'berjalan', 'C' => 'berlari', 'D' => 'melompat'], 'correct' => 'A'],
            ['question' => 'Melompat dengan satu kaki secara berulang disebut ...', 'options' => ['A' => 'engklek/hop', 'B' => 'lari cepat', 'C' => 'berjalan mundur', 'D' => 'berguling'], 'correct' => 'A'],
            ['question' => 'Gerakan berguling ke depan dalam senam lantai disebut ...', 'options' => ['A' => 'roll depan', 'B' => 'roll belakang', 'C' => 'kayang', 'D' => 'sikap lilin'], 'correct' => 'A'],
            ['question' => 'Gerakan mengangkat kaki lurus ke atas dengan bertumpu pada bahu disebut ...', 'options' => ['A' => 'sikap lilin', 'B' => 'roll depan', 'C' => 'kayang', 'D' => 'meroda'], 'correct' => 'A'],

            // --- Permainan bola besar ---
            ['question' => 'Olahraga yang menggunakan bola dan ring adalah ...', 'options' => ['A' => 'bola basket', 'B' => 'bulu tangkis', 'C' => 'renang', 'D' => 'lari'], 'correct' => 'A'],
            ['question' => 'Jumlah pemain dalam satu regu sepak bola di lapangan adalah ...', 'options' => ['A' => '11', 'B' => '5', 'C' => '6', 'D' => '7'], 'correct' => 'A'],
            ['question' => 'Permainan bola voli dimainkan oleh dua regu yang masing-masing terdiri atas ... pemain.', 'options' => ['A' => '6', 'B' => '5', 'C' => '7', 'D' => '11'], 'correct' => 'A'],
            ['question' => 'Dalam sepak bola, bagian tubuh yang paling umum digunakan untuk menendang bola adalah ...', 'options' => ['A' => 'kaki', 'B' => 'tangan', 'C' => 'kepala saja', 'D' => 'bahu'], 'correct' => 'A'],
            ['question' => 'Dalam permainan bola basket, menggiring bola disebut ...', 'options' => ['A' => 'dribbling', 'B' => 'servis', 'C' => 'smash', 'D' => 'passing bawah'], 'correct' => 'A'],
            ['question' => 'Dalam bola voli, pukulan awal untuk memulai permainan disebut ...', 'options' => ['A' => 'servis', 'B' => 'dribbling', 'C' => 'shooting', 'D' => 'heading'], 'correct' => 'A'],
            ['question' => 'Dalam sepak bola, penjaga gawang boleh menggunakan tangan di dalam area ...', 'options' => ['A' => 'kotak penalti', 'B' => 'seluruh lapangan', 'C' => 'garis tengah', 'D' => 'luar lapangan'], 'correct' => 'A'],
            ['question' => 'Memasukkan bola ke ring dalam bola basket disebut ...', 'options' => ['A' => 'shooting', 'B' => 'dribbling', 'C' => 'servis', 'D' => 'passing'], 'correct' => 'A'],
            ['question' => 'Gerakan memukul bola voli dengan keras ke daerah lawan disebut ...', 'options' => ['A' => 'smash', 'B' => 'servis', 'C' => 'dribbling', 'D' => 'passing atas'], 'correct' => 'A'],

            // --- Permainan bola kecil & atletik ---
            ['question' => 'Olahraga yang menggunakan raket dan kok adalah ...', 'options' => ['A' => 'bulu tangkis', 'B' => 'sepak bola', 'C' => 'bola voli', 'D' => 'renang'], 'correct' => 'A'],
            ['question' => 'Dalam bulu tangkis, pukulan untuk memulai permainan disebut ...', 'options' => ['A' => 'servis', 'B' => 'dribbling', 'C' => 'shooting', 'D' => 'tendangan'], 'correct' => 'A'],
            ['question' => 'Olahraga yang menggunakan bola kecil dan pemukul kayu disebut ...', 'options' => ['A' => 'kasti', 'B' => 'bola basket', 'C' => 'bola voli', 'D' => 'renang'], 'correct' => 'A'],
            ['question' => 'Cabang atletik yang menempuh jarak sangat pendek dengan kecepatan maksimal disebut ...', 'options' => ['A' => 'lari sprint', 'B' => 'lari maraton', 'C' => 'jalan santai', 'D' => 'jalan kaki jarak jauh'], 'correct' => 'A'],
            ['question' => 'Nomor atletik yang mengukur seberapa jauh seseorang dapat melompat disebut ...', 'options' => ['A' => 'lompat jauh', 'B' => 'lompat tinggi', 'C' => 'lari gawang', 'D' => 'tolak peluru'], 'correct' => 'A'],
            ['question' => 'Nomor atletik yang mengukur seberapa tinggi seseorang dapat melompati mistar disebut ...', 'options' => ['A' => 'lompat tinggi', 'B' => 'lompat jauh', 'C' => 'lari gawang', 'D' => 'lempar cakram'], 'correct' => 'A'],
            ['question' => 'Cabang olahraga yang dilakukan di dalam air disebut ...', 'options' => ['A' => 'renang', 'B' => 'senam lantai', 'C' => 'bulu tangkis', 'D' => 'atletik'], 'correct' => 'A'],
            ['question' => 'Gaya renang yang gerakan lengannya mirip baling-baling dan wajah menghadap air disebut gaya ...', 'options' => ['A' => 'bebas', 'B' => 'dada', 'C' => 'punggung', 'D' => 'kupu-kupu'], 'correct' => 'A'],
            ['question' => 'Gaya renang yang posisi tubuhnya terlentang menghadap ke atas disebut gaya ...', 'options' => ['A' => 'punggung', 'B' => 'bebas', 'C' => 'dada', 'D' => 'kupu-kupu'], 'correct' => 'A'],

            // --- Kebugaran & kesehatan ---
            ['question' => 'Tujuan pemanasan sebelum olahraga adalah ...', 'options' => ['A' => 'mempersiapkan tubuh', 'B' => 'membuat tubuh lelah', 'C' => 'mengurangi gerak', 'D' => 'menghentikan olahraga'], 'correct' => 'A'],
            ['question' => 'Pendinginan dilakukan setelah olahraga untuk ...', 'options' => ['A' => 'mengembalikan kondisi tubuh secara bertahap', 'B' => 'menambah beban latihan', 'C' => 'memulai pertandingan', 'D' => 'mengurangi minum'], 'correct' => 'A'],
            ['question' => 'Makanan bergizi seimbang membantu menjaga ...', 'options' => ['A' => 'kesehatan tubuh', 'B' => 'rasa malas', 'C' => 'kelelahan', 'D' => 'cedera'], 'correct' => 'A'],
            ['question' => 'Air putih diperlukan tubuh untuk mencegah ...', 'options' => ['A' => 'dehidrasi', 'B' => 'kekuatan', 'C' => 'kelaparan', 'D' => 'kebisingan'], 'correct' => 'A'],
            ['question' => 'Tidur yang cukup membantu tubuh melakukan ...', 'options' => ['A' => 'pemulihan', 'B' => 'dehidrasi', 'C' => 'cedera', 'D' => 'kelelahan permanen'], 'correct' => 'A'],
            ['question' => 'Latihan berlari secara rutin dapat meningkatkan ...', 'options' => ['A' => 'daya tahan jantung dan paru-paru', 'B' => 'kemampuan membaca', 'C' => 'pendengaran', 'D' => 'penglihatan'], 'correct' => 'A'],
            ['question' => 'Push-up terutama melatih kekuatan otot ...', 'options' => ['A' => 'lengan dan dada', 'B' => 'telinga', 'C' => 'mata', 'D' => 'jari kaki'], 'correct' => 'A'],
            ['question' => 'Sit-up terutama melatih otot ...', 'options' => ['A' => 'perut', 'B' => 'telinga', 'C' => 'leher saja', 'D' => 'jari'], 'correct' => 'A'],
            ['question' => 'Kelenturan tubuh dapat dilatih dengan latihan ...', 'options' => ['A' => 'peregangan', 'B' => 'lari cepat saja', 'C' => 'angkat beban saja', 'D' => 'duduk diam'], 'correct' => 'A'],
            ['question' => 'Kebersihan tubuh setelah berolahraga dapat dijaga dengan ...', 'options' => ['A' => 'mandi dan mengganti pakaian', 'B' => 'tidak minum', 'C' => 'tidak membersihkan diri', 'D' => 'tidur tanpa membersihkan diri'], 'correct' => 'A'],
            ['question' => 'Aktivitas fisik dan pola makan seimbang merupakan bagian dari ...', 'options' => ['A' => 'gaya hidup sehat', 'B' => 'gaya hidup sedentari', 'C' => 'kebiasaan buruk', 'D' => 'pola hidup tidak teratur'], 'correct' => 'A'],
            ['question' => 'Sikap tubuh yang benar saat berdiri adalah ...', 'options' => ['A' => 'tegak', 'B' => 'membungkuk terus', 'C' => 'miring berlebihan', 'D' => 'duduk'], 'correct' => 'A'],
            ['question' => 'Cedera ringan seperti keseleo pada awalnya dapat ditangani dengan metode RICE, singkatan dari ...', 'options' => ['A' => 'Rest, Ice, Compression, Elevation', 'B' => 'Run, Ice, Cardio, Endurance', 'C' => 'Rest, Injury, Cardio, Exercise', 'D' => 'Run, Injury, Compression, Exercise'], 'correct' => 'A'],
            ['question' => 'Denyut nadi normal orang dewasa saat istirahat berkisar antara ...', 'options' => ['A' => '60-100 kali per menit', 'B' => '150-200 kali per menit', 'C' => '10-20 kali per menit', 'D' => '300-400 kali per menit'], 'correct' => 'A'],

            // --- Peraturan & sportivitas ---
            ['question' => 'Sikap menghormati lawan dan wasit dalam pertandingan disebut ...', 'options' => ['A' => 'sportivitas', 'B' => 'kecurangan', 'C' => 'egoisme', 'D' => 'individualisme'], 'correct' => 'A'],
            ['question' => 'Pelanggaran dalam sepak bola yang dilakukan di dalam kotak penalti dapat berakibat ...', 'options' => ['A' => 'tendangan penalti', 'B' => 'lemparan ke dalam', 'C' => 'sepak pojok otomatis', 'D' => 'pertandingan dihentikan'], 'correct' => 'A'],
            ['question' => 'Wasit dalam pertandingan bertugas untuk ...', 'options' => ['A' => 'memimpin dan mengawasi jalannya pertandingan', 'B' => 'menjadi pemain cadangan', 'C' => 'menjual tiket', 'D' => 'melatih salah satu tim'], 'correct' => 'A'],
        ];
    }
}