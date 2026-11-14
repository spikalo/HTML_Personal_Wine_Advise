# Eisen en acceptatie

| ID | Bron / eis | Acceptatie / bewijs |
| --- | --- | --- |
| FR-01 | Gebruiker: PHP/HTML op SolidCP, direct kopiëren | index.php/app/data/assets/web.config; README; lokale PHP-smoke |
| FR-02 | Geen database | Geen PDO/SQL/installer, geen gedeelde schrijfacties |
| FR-03 | Alle dimensies herkenbare vragen 0–10 | 14 dimensies × 2 vragen; vaste zin; PHP-tests |
| FR-04 | Wijnadvies | Exacte profielmatch bovenaan, 0/10 grenzen geldig; top 5/12 |
| FR-05 | Grotere dataset en beoordelingen | 1.007 unieke wijnen, 188 druivennamen, 150.000 ratings; datatests |
| FR-06 | Extra smaken alleen als bron beoordeelt | Geen verzonnen scores; X-Wines mist extra numerieke aromascores |
| FR-07 | Private nieuwe GitHub-repo, oude ontkoppeld, opruimen | Alleen nieuwe origin, nieuw init zonder oude user_data-geschiedenis |
| NFR-01 | Veilige invoer en bezoekersisolatie | Servervalidatie, CSRF, escaping, geen gedeelde antwoorden; HTTP-tests |
| NFR-02 | Mobiel/toegankelijk | Labels, toetsenbord, focus, 390px zonder overflow, donker/licht |
| NFR-03 | Hosting zonder runtime-downloads | Bootstrap lokaal, alleen PHP plus browserscripts |
| NFR-04 | Betrouwbare herkomst | Bronlinks/licentie/hash/mapping en beperkingen in DATA-SOURCES |

Doel is kleinschalig persoonlijk gebruik. Geen aantoonbare SLA of piekbelasting
opgegeven; geen onbegrensde schaalclaim. Lokale responstijd is alleen een indicatie,
geen capaciteitsgarantie voor IIS. Live acceptatie: Frank vult persoonlijke smaken
in, vergelijkt uitkomsten, test een mobiel en controleert afgeschermde paden.
