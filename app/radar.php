<?php
declare(strict_types=1);

/** Responsive, server-rendered SVG: no JavaScript, CDN or chart dependency. */
function renderTasteRadar(array $preferences, array $grape, array $questions): string
{
    $keys = array_keys($preferences);
    if (count($keys) < 3) {
        return '<p class="small text-body-secondary">Beantwoord minstens drie smaakdimensies voor de spiderchart. Je vergelijking staat hieronder in de tabel.</p>';
    }
    $labels = ['acidity' => 'Zuur', 'body' => 'Volheid', 'sweetness' => 'Zoet',
        'citrus' => 'Citrusfruit', 'apple' => 'Appel', 'tropical' => 'Tropisch fruit',
        'stone' => 'Steenfruit', 'melon' => 'Meloen', 'pear' => 'Peer',
        'redfruit' => 'Rood fruit', 'nuts' => 'Noten', 'flowers' => 'Bloemen',
        'honey' => 'Honing', 'herbs' => 'Kruiden'];
    $point = static function (int $index, float $value) use ($keys): array {
        $angle = -M_PI / 2 + 2 * M_PI * $index / count($keys);
        $radius = 210 * $value / 10;
        return [400 + cos($angle) * $radius, 310 + sin($angle) * $radius];
    };
    $points = static function (array $values) use ($keys, $point): string {
        $result = [];
        foreach ($keys as $index => $key) {
            [$x, $y] = $point($index, (float) $values[$key]);
            $result[] = number_format($x, 2, '.', '') . ',' . number_format($y, 2, '.', '');
        }
        return implode(' ', $result);
    };
    ob_start();
    ?>
    <figure class="taste-radar" aria-label="Vergelijking van je smaakprofiel">
      <svg viewBox="0 0 800 620" role="img" aria-labelledby="radar-title radar-description">
        <title id="radar-title">Jouw smaakprofiel en <?= e($grape['name']) ?></title>
        <desc id="radar-description">Spiderchart op schaal 0 tot 10. De doorgetrokken lijn is jouw voorkeur; de stippellijn is de best passende druif. Alleen beantwoorde dimensies zijn opgenomen. De exacte waarden staan in de tabel onder de grafiek.</desc>
        <?php foreach ([2, 4, 6, 8, 10] as $tick): ?>
          <polygon class="radar-grid" points="<?= e($points(array_fill_keys($keys, $tick))) ?>" />
          <text class="radar-tick" x="408" y="<?= e(310 - 21 * $tick - 4) ?>"><?= $tick ?></text>
        <?php endforeach; ?>
        <?php foreach ($keys as $index => $key):
            [$x, $y] = $point($index, 10);
            [$labelX, $labelY] = $point($index, 12);
            $anchor = abs($labelX - 400) < 5 ? 'middle' : ($labelX > 400 ? 'start' : 'end'); ?>
          <line class="radar-axis" x1="400" y1="310" x2="<?= e($x) ?>" y2="<?= e($y) ?>" />
          <text class="radar-label" x="<?= e($labelX) ?>" y="<?= e($labelY) ?>" text-anchor="<?= $anchor ?>" dominant-baseline="middle"><?= e($labels[$key] ?? $questions[$key]['label']) ?></text>
        <?php endforeach; ?>
        <polygon class="radar-user" points="<?= e($points($preferences)) ?>" />
        <polygon class="radar-grape" points="<?= e($points($grape['profile'])) ?>" />
        <?php foreach ($keys as $index => $key):
            [$userX, $userY] = $point($index, (float) $preferences[$key]);
            [$grapeX, $grapeY] = $point($index, (float) $grape['profile'][$key]); ?>
          <circle class="radar-user-point" cx="<?= e($userX) ?>" cy="<?= e($userY) ?>" r="4"><title><?= e($questions[$key]['label']) ?> — jij: <?= e($preferences[$key]) ?></title></circle>
          <circle class="radar-grape-point" cx="<?= e($grapeX) ?>" cy="<?= e($grapeY) ?>" r="4"><title><?= e($questions[$key]['label']) ?> — <?= e($grape['name']) ?>: <?= e($grape['profile'][$key]) ?></title></circle>
        <?php endforeach; ?>
      </svg>
      <figcaption><span><span class="radar-key radar-key-user" aria-hidden="true"></span>Jouw voorkeur</span><span><span class="radar-key radar-key-grape" aria-hidden="true"></span><?= e($grape['name']) ?></span><small class="text-body-secondary">Schaal 0–10 · overgeslagen smaken staan niet in de grafiek.</small></figcaption>
    </figure>
    <?php
    return (string) ob_get_clean();
}
