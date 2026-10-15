<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$exams = App\Models\Exam::all(['id','title','exact_time','started_at','expired_at','duration','is_available']);
foreach ($exams as $e) {
    echo $e->id . ' | ' . $e->title . ' | exact=' . $e->exact_time . ' | started=' . $e->started_at . ' | expired=' . $e->expired_at . ' | avail=' . $e->is_available . PHP_EOL;
}
