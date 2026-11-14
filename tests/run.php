<?php
declare(strict_types=1);
require __DIR__ . '/../app/recommend.php';
$questions = require __DIR__ . '/../app/questions.php';
$passed = 0;
function check(bool $condition, string $message): void
{
    global $passed;
    if (!$condition) {
        throw new RuntimeException($message);
    }
    $passed++;
    echo 'PASS ' . $message . PHP_EOL;
}
$input = ['answers' => []];
foreach ($questions as $key => $dimension) {
    $input['answers'][$key] = ['0', '10'];
    foreach ($dimension['examples'] as $example) {
        check(str_starts_with(questionText($example), 'Vind je ') && str_ends_with(questionText($example), 'lekker? Hoe lekker vind je het op schaal van 0 tot 10?'), 'Question format: ' . $key);
    }
}
[$profile, $errors] = validateAnswers($input, $questions);
check(!$errors && count($profile) === 14 && count(array_unique($profile)) === 1 && $profile['body'] === 5, '14 dimensions, valid endpoints and averaging');
foreach (['', '-1', '11', '1.5', 'NaN', '1e1', '<script>', ['5']] as $value) {
    $bad = $input;
    $bad['answers']['body'][0] = $value;
    check((bool) validateAnswers($bad, $questions)[1], 'Reject malformed/out-of-range answer ' . json_encode($value));
}
check((bool) validateAnswers(['answers' => 'bad'], $questions)[1], 'Reject scalar answer collection');
$skip = $input;
$skip['skip']['body'] = '1';
unset($skip['answers']['body']);
[$partial, $errors] = validateAnswers($skip, $questions);
check(!$errors && !isset($partial['body']), 'Skip excludes missing dimension without assigning zero');
check(similarity(['body' => 0], ['body' => 0]) === 100.0, 'All-zero identical profile matches fully');
check(similarity(['body' => 10], ['body' => 0]) === 0.0, 'Opposite endpoints do not match');
check(similarity(['body' => 10], ['acidity' => 10]) === null, 'Missing attribute is unknown');
$grapes = loadData('grapes');
$wines = loadData('wines');
check(count($wines) === 1007 && count($grapes) === 20, 'Expected catalogue cardinality');
check(array_sum(array_column($wines, 'ratings')) === 150000, 'All historical ratings accounted for');
check(count(array_unique(array_column($wines, 'id'))) === 1007, 'Unique wine IDs');
check(recommendGrapes($grapes[0]['profile'], $grapes)[0]['name'] === $grapes[0]['name'], 'Exact taste profile ranks its own grape first');
check(recommendGrapes($grapes[0]['profile'], $grapes)[0]['score'] === 100.0, 'Exact grape match is 100');
check(recommendWines(['honey' => 10], $wines) === [], 'No invented wine aroma scores');
foreach (['Red', 'White', 'Rosé', 'Sparkling', 'Dessert', 'Dessert/Port'] as $type) {
    $results = recommendWines($profile, $wines, $type);
    check(count($results) > 0 && count(array_unique(array_column($results, 'type'))) === 1 && $results[0]['type'] === $type, 'Wine type filter: ' . $type);
}
foreach ($wines as $wine) {
    if ($wine['body'] < 0 || $wine['body'] > 10 || $wine['acidity'] < 0 || $wine['acidity'] > 10 || $wine['rating'] < 1 || $wine['rating'] > 5) {
        throw new RuntimeException('Invalid source value: ' . $wine['id']);
    }
}
check(true, 'All wine values within documented ranges');
check(!str_contains(e('<script>"&'), '<script>'), 'HTML output escaping');
echo $passed . ' checks passed.' . PHP_EOL;
