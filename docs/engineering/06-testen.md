# Testbewijs — 14 november 2026

Omgeving: lokale Windows-werkplek; tijdelijke officiële PHP 8.5.12 NTS,
SHA-256 gecontroleerd; eigen PHP-server 127.0.0.1:8096. Chrome headless via
Playwright 1.65.1 in tijdelijke testmap. Geen project-runtime-afhankelijkheid.

| ID | Eis / scenario | Uitvoering / uitkomst |
| --- | --- | --- |
| TS-01 | PHP syntax | php -l op alle PHP-bestanden: geslaagd |
| TS-02 | Vraagformaat, 0/10, gemiddelden, overslaan, malformed, scores, data, types | php tests/run.php: 56 checks geslaagd |
| TS-03 | Formulier/resultaat, CSRF, fouten, sessiegrens, overslaan, bodylimiet, methode | node tests/http.mjs http://127.0.0.1:8096/: 10 checks geslaagd |
| TS-04 | Desktop 1440×1000, formulier submit | 28 inputs, 12 wijnkaarten, resultaatfocus, geen JS/consolefouten |
| TS-05 | Mobiel 390×844 | Geen horizontale overflow; screenshot bekeken |
| TS-06 | Thema/overslaan | Lichtthema werkt; twee inputs uit bij overslaan en weer aan bij uitvinken |
| TS-07 | Config/assets | XML geparsed, JS syntaxcheck, lokale CSS/logo geladen |
| TS-08 | Dataset | 1.007 unieke IDs, 150.000 ratings, 188 druivenstrings; geen UserIDs gedeployed |

Dezelfde agent deed implementatie en controles. Geen onafhankelijk acceptatie-
of pentestbewijs. Niet uitgevoerd: live IIS-filtering/HTTPS/rechten, echte
SolidCP-installatie, belastingtest, volledige screenreader- en Firefox/Safari-test.
Geen DB/migratie/concurrente mutatietest nodig: die functies ontbreken.

De sandbox-shell kon localhost niet benaderen; de HTTP-tests zijn daarom vanuit
de normale Node-testomgeving uitgevoerd. Die beperking is geen testresultaat.


Spiderchart, 15 november 2026: 61 PHP-checks en 10 HTTP-checks geslaagd; PHP-syntax geslaagd. Headless Chrome: 14 assen/twee reeksen, desktop en 390px mobiel visueel bekeken, geen horizontale overflow, zonder JavaScript en in print zichtbaar. Drie beantwoorde assen, nulwaarden, weggelaten assen, escaping en fallback bij twee dimensies getest.
