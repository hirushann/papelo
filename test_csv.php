<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$file = fopen('/Users/hirushanperera/Downloads/examtestpaper.csv', 'r');
$header = fgetcsv($file);
$importedCount = 0;
while ($row = fgetcsv($file)) {
    if (count($row) < 7) continue;

    $questionText = trim($row[0]);
    $topicTag = trim($row[1]);
    $option1 = trim($row[2]);
    $option2 = trim($row[3]);
    $option3 = trim($row[4]);
    $option4 = trim($row[5]);
    $correctNum = (int) trim($row[6]);

    if (!$questionText || !$option1 || !$option2 || !$option3 || !$option4 || !in_array($correctNum, [1, 2, 3, 4])) {
        echo "Skipped row: " . json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
        continue;
    }
    $importedCount++;
}
echo "Imported: $importedCount\n";
