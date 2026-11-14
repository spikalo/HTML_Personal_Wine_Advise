<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function loadData(string $name): array
{
    $allowed = ['wines', 'grapes', 'metadata'];
    if (!in_array($name, $allowed, true)) {
        throw new InvalidArgumentException('Unknown dataset');
    }
    $text = file_get_contents(__DIR__ . '/../data/' . $name . '.json');
    if ($text === false) {
        throw new RuntimeException('Dataset unavailable');
    }
    return json_decode($text, true, 512, JSON_THROW_ON_ERROR);
}

function questionText(string $example): string
{
    return 'Vind je ' . $example . ' lekker? Hoe lekker vind je het op schaal van 0 tot 10?';
}

function validateAnswers(array $input, array $questions): array
{
    $profile = [];
    $errors = [];
    $answers = $input['answers'] ?? [];
    $skips = $input['skip'] ?? [];
    if (!is_array($answers) || !is_array($skips)) {
        return [[], ['Het formulier is ongeldig. Vul je voorkeuren opnieuw in.']];
    }
    foreach ($questions as $key => $dimension) {
        if (($skips[$key] ?? '') === '1') {
            continue;
        }
        $values = $answers[$key] ?? [];
        if (!is_array($values) || count($values) !== count($dimension['examples'])) {
            $errors[] = 'Beantwoord beide vragen bij ' . $dimension['label'] . ' of kies overslaan.';
            continue;
        }
        $valid = [];
        foreach (array_keys($dimension['examples']) as $index) {
            $value = $values[$index] ?? null;
            if (!is_string($value) || !preg_match('/^(?:[0-9]|10)$/D', $value)) {
                $errors[] = 'Kies een heel getal van 0 tot 10 bij ' . $dimension['label'] . '.';
                break;
            }
            $valid[] = (int) $value;
        }
        if (count($valid) === count($dimension['examples'])) {
            $profile[$key] = array_sum($valid) / count($valid);
        }
    }
    if (count($profile) < 2 && !$errors) {
        $errors[] = 'Beantwoord minstens twee smaakdimensies voor een bruikbaar advies.';
    }
    return [$profile, array_values(array_unique($errors))];
}

// Bounded distance: zero profiles remain meaningful (unlike cosine similarity).
// Missing attributes are excluded, never interpreted as absence of a flavour.
function similarity(array $preferences, array $profile): ?float
{
    $distances = [];
    foreach ($preferences as $key => $value) {
        if (isset($profile[$key]) && is_numeric($profile[$key])) {
            $distances[] = ((float) $value - (float) $profile[$key]) ** 2;
        }
    }
    return $distances ? round(100 * (1 - sqrt(array_sum($distances) / count($distances)) / 10), 1) : null;
}

function recommendGrapes(array $preferences, array $grapes): array
{
    foreach ($grapes as &$grape) {
        $grape['score'] = similarity($preferences, $grape['profile']);
    }
    unset($grape);
    usort($grapes, fn(array $a, array $b): int => ($b['score'] <=> $a['score']) ?: strcmp($a['name'], $b['name']));
    return array_slice($grapes, 0, 5);
}

function recommendWines(array $preferences, array $wines, string $type = ''): array
{
    // X-Wines has no quantitative aroma or sweetness ratings. Keep this ranking
    // separate from the 14-dimensional original grape profiles.
    $structure = array_intersect_key($preferences, array_flip(['acidity', 'body']));
    if (!$structure) {
        return [];
    }
    $result = [];
    foreach ($wines as $wine) {
        if ($type !== '' && $wine['type'] !== $type) {
            continue;
        }
        $wine['score'] = similarity($structure, $wine);
        $result[] = $wine;
    }
    usort($result, fn(array $a, array $b): int => ($b['score'] <=> $a['score'])
        ?: ($b['ratings'] <=> $a['ratings']) ?: ($a['id'] <=> $b['id']));
    return array_slice($result, 0, 12);
}
