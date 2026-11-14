<?php
declare(strict_types=1);

ini_set('display_errors', '0');
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: DENY');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; connect-src 'self'; object-src 'none'; base-uri 'none'; frame-ancestors 'none'; form-action 'self'");
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header('Cache-Control: no-store');
$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
if ($secure) {
    header('Strict-Transport-Security: max-age=31536000');
}
session_name('wine_session');
session_start(['use_strict_mode' => 1, 'cookie_httponly' => true, 'cookie_secure' => $secure, 'cookie_samesite' => 'Lax']);
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
$csrf = $_SESSION['csrf'];
session_write_close();
require __DIR__ . '/app/recommend.php';
require __DIR__ . '/app/radar.php';
$questions = require __DIR__ . '/app/questions.php';
$profile = $errors = $grapeResults = $wineResults = [];
$type = '';
$types = ['Red' => 'Rood', 'White' => 'Wit', 'Rosé' => 'Rosé', 'Sparkling' => 'Mousserend', 'Dessert' => 'Dessert', 'Dessert/Port' => 'Dessert / port'];
$hasResult = false;
try {
    $metadata = loadData('metadata');
    $wines = loadData('wines');
    $grapes = loadData('grapes');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!is_string($_POST['csrf'] ?? null) || !hash_equals($csrf, $_POST['csrf'])) {
            http_response_code(403);
            $errors[] = 'Je formulier is verlopen. Vernieuw de pagina en probeer het opnieuw.';
        } elseif ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 32768) {
            http_response_code(413);
            $errors[] = 'Het formulier is te groot. Vul het opnieuw in.';
        } else {
            [$profile, $errors] = validateAnswers($_POST, $questions);
            if (!is_string($_POST['type'] ?? '') || (($_POST['type'] ?? '') !== '' && !isset($types[$_POST['type']]))) {
                $errors[] = 'Kies een geldig wijntype.';
            } else {
                $type = $_POST['type'] ?? '';
            }
            if (!$errors) {
                $grapeResults = recommendGrapes($profile, $grapes);
                $wineResults = recommendWines($profile, $wines, $type);
                $hasResult = true;
            } else {
                http_response_code(422);
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'HEAD') {
        header('Allow: GET, HEAD, POST');
        http_response_code(405);
        $errors[] = 'Deze aanvraag wordt niet ondersteund.';
    }
} catch (Throwable $error) {
    error_log('Wine catalogue error: ' . $error->getMessage());
    http_response_code(503);
    $errors[] = 'Het wijnadvies is tijdelijk niet beschikbaar. Probeer het later opnieuw.';
    $metadata = ['wines' => 0, 'grape_varieties' => 0, 'ratings' => 0];
}
function oldAnswer(string $key, int $index): string
{
    $answers = $_POST['answers'] ?? [];
    $values = is_array($answers) ? ($answers[$key] ?? []) : [];
    $value = is_array($values) ? ($values[$index] ?? '') : '';
    return is_string($value) && preg_match('/^(?:[0-9]|10)$/D', $value) ? $value : '';
}
function skipped(string $key): bool
{
    return is_array($_POST['skip'] ?? null) && ($_POST['skip'][$key] ?? '') === '1';
}
?>
<!doctype html>
<html lang="nl" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Ontdek welke druiven en wijnen bij jouw smaak passen. Een persoonlijke smaaktest met herkenbare ingrediënten.">
  <title>Personal Wine Advise — ontdek jouw smaak</title>
  <link rel="icon" href="assets/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="assets/vendor/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/frank-jacobs.css">
  <link rel="stylesheet" href="assets/css/site.css">
  <script src="assets/js/site.js" defer></script>
</head>
<body>
<a class="skip-link" href="#main">Ga naar inhoud</a>
<header class="site-header border-bottom">
  <div class="container d-flex justify-content-between align-items-center gap-3 py-3">
    <a class="brand d-flex align-items-center gap-3" href="./"><img src="assets/img/logo.png" width="44" height="44" alt="Frank Jacobs"><span>Personal Wine Advise<small>Jouw smaak. Jouw ontdekking.</small></span></a>
    <button class="btn btn-outline-secondary btn-sm" type="button" id="theme-toggle" aria-label="Schakel naar licht thema">Licht thema</button>
  </div>
</header>
<main id="main" class="container pb-5">
  <section class="hero row align-items-center g-4">
    <div class="col-lg-8">
      <p class="eyebrow">VAN BEKENDE SMAKEN NAAR NIEUWE FAVORIETEN</p>
      <h1>Goede wijn begint<br>bij wat jij lekker vindt.</h1>
      <p class="lead text-body-secondary">Je hoeft niets van wijn te weten. Vertel wat je vindt van herkenbare smaken en ontdek welke druiven en wijnen daarbij passen.</p>
      <a class="btn btn-primary" href="<?= $hasResult ? '#resultaten' : '#smaaktest' ?>"><?= $hasResult ? 'Bekijk jouw advies' : 'Ontdek mijn smaak' ?> <span aria-hidden="true">↗</span></a>
      <span class="hero-note">28 korte vragen · geen account</span>
    </div>
    <div class="col-lg-4"><aside class="catalogue-summary card p-4" aria-label="Over de wijncollectie"><span class="eyebrow">JE HEBT IETS TE ONTDEKKEN</span><strong><?= number_format($metadata['wines'], 0, ',', '.') ?></strong><span>wijnen in de collectie</span><hr><div class="d-flex justify-content-between"><span><?= e($metadata['grape_varieties']) ?> druivennamen</span><span><?= number_format($metadata['ratings'], 0, ',', '.') ?> beoordelingen</span></div><small class="text-body-secondary mt-3">X-Wines Slim · historische gegevens, geen actuele winkelvoorraad.</small></aside></div>
  </section>

  <?php if ($errors): ?><div class="alert alert-danger" role="alert" id="errors" tabindex="-1"><h2 class="h5">Controleer je antwoorden</h2><ul class="mb-0"><?php foreach ($errors as $message): ?><li><?= e($message) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

  <?php if ($hasResult): ?>
  <section id="resultaten" class="results section-space" tabindex="-1">
    <p class="eyebrow">DIT PAST BIJ JOU</p><h2>Jouw smaak, vertaald naar wijn.</h2>
    <p class="text-body-secondary">De overeenkomstscore vergelijkt je voorkeuren met een smaakprofiel. Het is een startpunt om te proeven, geen kansberekening of garantie.</p>
    <div class="row g-4 mt-1">
      <div class="col-lg-5"><article class="card h-100 p-4"><span class="eyebrow">JOUW BESTE DRUIFMATCH</span><h3 class="display-6 mt-3"><?= e($grapeResults[0]['name']) ?></h3><p class="match-score"><?= e($grapeResults[0]['score']) ?><small>/ 100 overeenkomst</small></p><p class="text-body-secondary">Vergelijking met de 20 oorspronkelijke druivenprofielen op <?= count($profile) ?> door jou beoordeelde dimensies.</p><ol class="grape-list"><?php foreach ($grapeResults as $grape): ?><li><span><?= e($grape['name']) ?></span><strong><?= e($grape['score']) ?></strong></li><?php endforeach; ?></ol></article></div>
      <div class="col-lg-7"><article class="card h-100 p-4"><h3 class="h5">Je voorkeuren naast je beste match</h3><p class="small text-body-secondary">Voorkeur is omgerekend naar een indicatief intensiteitsprofiel. Een smaak lekker vinden is niet hetzelfde als er veel van willen.</p><?= renderTasteRadar($profile, $grapeResults[0], $questions) ?><table class="table table-sm profile-table"><caption>Scores op een schaal van 0 tot 10</caption><thead><tr><th scope="col">Smaak</th><th scope="col">Jij</th><th scope="col">Druif</th></tr></thead><tbody><?php foreach ($profile as $key => $value): ?><tr><th scope="row"><?= e($questions[$key]['label']) ?></th><td><meter min="0" max="10" value="<?= e($value) ?>" aria-label="Jouw voorkeur voor <?= e($questions[$key]['label']) ?>"><?= e($value) ?></meter> <?= e($value) ?></td><td><?= e($grapeResults[0]['profile'][$key]) ?></td></tr><?php endforeach; ?></tbody></table></article></div>
    </div>
    <div class="section-space"><div class="d-flex flex-wrap justify-content-between gap-2 align-items-end"><div><p class="eyebrow">DE GROTERE WIJNCOLLECTIE</p><h3>Wijnen om te ontdekken</h3></div><span class="badge text-bg-secondary"><?= e($type === '' ? 'Alle wijntypen' : $types[$type]) ?></span></div>
    <p class="text-body-secondary">Deze aparte rangschikking gebruikt alleen frisheid/zuur en volheid, voor zover je die invulde. X-Wines bevat geen scores voor zoetheid of afzonderlijke aroma’s. Alle overige antwoorden tellen mee in het druivenadvies hierboven. Bij gelijke overeenkomst komt de wijn met de meeste beoordelingen eerst.</p>
    <?php if (!$wineResults): ?><div class="alert alert-info"><?= isset($profile['body']) || isset($profile['acidity']) ? 'Geen wijnen gevonden voor dit wijntype. Kies een ander type.' : 'Vul frisheid/zuur of volheid in om ook wijnen uit de grotere collectie te vergelijken.' ?></div><?php else: ?>
    <div class="row g-3"><?php foreach ($wineResults as $wine): ?><div class="col-md-6 col-xl-4"><article class="card wine-card h-100 p-4"><div class="d-flex justify-content-between gap-2 mb-3"><span class="wine-type"><?= e($types[$wine['type']] ?? $wine['type']) ?></span><strong class="text-accent"><?= e($wine['score']) ?> / 100</strong></div><h4 class="h5"><?= e($wine['name']) ?></h4><p class="text-body-secondary small mb-2"><?= e($wine['winery']) ?> · <?= e($wine['country']) ?></p><p class="small"><?= e(implode(', ', $wine['grapes'])) ?></p><dl class="wine-facts mt-auto"><div><dt>Zuur / volheid</dt><dd><?= e($wine['acidity']) ?> / <?= e($wine['body']) ?> <small>(0–10)</small></dd></div><div><dt>Alcohol</dt><dd><?= e($wine['abv']) ?>%</dd></div><div><dt>Waardering</dt><dd><?= $wine['rating'] === null ? 'Niet beschikbaar' : e(number_format($wine['rating'], 2, ',', '.')) . ' / 5' ?><small> · <?= e($wine['ratings']) ?> beoordelingen</small></dd></div></dl></article></div><?php endforeach; ?></div>
    <?php endif; ?></div>
    <div class="d-flex flex-wrap gap-3 mt-4"><a href="#smaaktest" class="btn btn-primary">Mijn antwoorden aanpassen</a><button type="button" class="btn btn-outline-secondary" id="print-result">Bewaren als PDF / afdrukken</button></div>
  </section>
  <?php endif; ?>

  <section id="smaaktest" class="section-space">
    <div class="section-heading"><p class="eyebrow">LEER JE SMAAK KENNEN</p><h2>Wat vind jij lekker?</h2><p class="text-body-secondary">0 = helemaal niet lekker · 5 = neutraal · 10 = heel lekker. Denk aan het genoemde ingrediënt, niet aan wijn. Ken je iets niet? Sla die smaakdimensie over.</p></div>
    <p class="small text-body-secondary">De voorbeelden benaderen een smaak of mondgevoel. Het zijn geen exacte equivalenten; bijvoorbeeld honing is zowel zoet als aromatisch en thee kan ook bitter zijn.</p>
    <form method="post" action="index.php" id="taste-form" novalidate>
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
      <div class="form-toolbar card p-3 mb-4"><div class="d-flex justify-content-between gap-3"><strong>Jouw smaakprofiel</strong><span id="progress-label" aria-live="polite">14 dimensies · 28 vragen</span></div><progress id="taste-progress" class="mt-2 w-100" max="14" value="0" aria-label="Ingevulde smaakdimensies"></progress></div>
      <div class="row g-3">
      <?php foreach ($questions as $key => $dimension): ?>
        <div class="col-lg-6"><fieldset class="card question-card h-100 p-4" data-dimension="<?= e($key) ?>"><legend><small><?= e($dimension['group']) ?></small><?= e($dimension['label']) ?></legend>
        <?php foreach ($dimension['examples'] as $index => $example): ?><div class="question-row"><label for="q-<?= e($key) ?>-<?= $index ?>"><?= e(questionText($example)) ?></label><div class="rating-input"><input type="number" class="form-control" id="q-<?= e($key) ?>-<?= $index ?>" name="answers[<?= e($key) ?>][<?= $index ?>]" min="0" max="10" step="1" inputmode="numeric" value="<?= e(oldAnswer($key, $index)) ?>" aria-describedby="scale-help"><span aria-hidden="true">/ 10</span></div></div><?php endforeach; ?>
        <label class="form-check mt-3"><input class="form-check-input skip-dimension" type="checkbox" name="skip[<?= e($key) ?>]" value="1" <?= skipped($key) ? 'checked' : '' ?>><span class="form-check-label">Dit ken ik niet / deze dimensie overslaan</span></label></fieldset></div>
      <?php endforeach; ?>
      </div>
      <p id="scale-help" class="small text-body-secondary mt-3">Geef hele cijfers van 0 tot 10. De twee antwoorden per smaak worden gemiddeld; overgeslagen smaken worden niet meegenomen.</p>
      <div class="card p-4 mt-4"><label class="form-label fw-bold" for="wine-type">Welke wijnen wil je ontdekken?</label><select id="wine-type" name="type" class="form-select mb-3"><option value="">Alle wijntypen</option><?php foreach ($types as $key => $label): ?><option value="<?= e($key) ?>" <?= $key === $type ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select><p class="small text-body-secondary">Het filter geldt voor de grotere wijncollectie. De oorspronkelijke 20 druivenprofielen zijn hoofdzakelijk witte druiven en blijven apart zichtbaar.</p><div class="d-flex flex-wrap gap-3"><button type="submit" class="btn btn-primary">Vind mijn wijnmatch <span aria-hidden="true">↗</span></button><a href="./" class="btn btn-outline-secondary">Opnieuw beginnen</a></div></div>
    </form>
  </section>
  <section class="section-space" id="over-de-data"><details class="card p-4"><summary class="fw-bold">Waar komt het advies vandaan?</summary><div class="mt-3 text-body-secondary"><p>De oorspronkelijke 20 druivenprofielen komen uit je NN_Personal_Wineadvise-project. Hun 14 scores zijn van 0–5 omgerekend naar 0–10. De herkomst van die oorspronkelijke proefbeoordelingen is niet vastgelegd; behandel ze als indicatieve profielen.</p><p>De uitgebreide collectie bevat 1.007 wijnen, 188 verschillende druivennamen en gemiddelden van 150.000 historische beoordelingen uit X-Wines Slim. Druiven kunnen synoniemen bevatten. De dataset is in 2022 verzameld, met beoordelingen uit 2012–2021. Er worden geen proefnotities of extra smaakintensiteiten bij verzonnen.</p><p>Voor de afstandsberekening zijn zuur (laag/midden/hoog) en volheid (vijf categorieën) lineair omgerekend naar 0–10. De waardering van andere drinkers is geen persoonlijke smaakscore. De website gebruikt geen database, analytics of externe diensten tijdens het gebruik. Alleen een tijdelijke, noodzakelijke sessiecookie beschermt het formulier; antwoorden worden niet in je sessie of een gedeeld bestand opgeslagen. Je browser kan de ingevulde pagina lokaal bewaren; IIS kan normale toegangslogs bijhouden.</p><p class="mb-0">Bron: de Azambuja, R. X.; Morais, A. J.; Filipe, V. (2023). <a href="https://doi.org/10.3390/bdcc7010020" rel="noreferrer">X-Wines: A Wine Dataset for Recommender Systems and Machine Learning</a>. <a href="https://github.com/rogerioxavier/X-Wines" rel="noreferrer">Dataset (CC0 1.0)</a>. Gedownload 14 november 2026.</p></div></details></section>
</main>
<footer class="container border-top py-4 small text-body-secondary d-flex flex-wrap justify-content-between gap-2"><span>Personal Wine Advise · Frank Jacobs</span><span>Voor volwassenen van 18 jaar en ouder · drink bewust</span></footer>
</body>
</html>
