# Personal Wine Advise — PHP / HTML

Wijnadvies voor IIS/SolidCP, zonder database, Composer, Node-server, Streamlit
of externe API. PHP 8.2+ (getest met 8.5.12), HTML, vanilla JavaScript en lokaal
gehoste Bootstrap 5.3.8. Geen installatieprogramma nodig.

## Installatie op SolidCP

1. Kies de website in SolidCP, schakel PHP/FastCGI in en regel domein en geldig
   HTTPS-certificaat. IIS URL Rewrite en Request Filtering zijn vereist.
2. Maak een reservekopie van de bestaande website buiten de webroot. Verwijder
   een oude `index.html` of `default.aspx` pas na controle van de reservekopie.
3. Kopieer de **inhoud van deze projectmap** naar de ingestelde documentroot
   (bijvoorbeeld `wwwroot`). `index.php`, `web.config`, `app`, `data` en `assets`
   staan direct daarin. Er is geen aparte `public`-submap.
4. `docs`, `tests`, `tools`, `.github`, README en `.gitignore` zijn alleen voor
   ontwikkeling en hoeven niet mee. Kopieer `.git` niet naar de webroot. De map
   `company policy` naast het project is geen onderdeel van deze website.
5. Geef de PHP-identiteit alleen leesrechten op de website. Projectmappen hoeven
   niet schrijfbaar te zijn. PHP heeft wel een werkende eigen tijdelijke sessiemap
   nodig, via de bestaande PHP-configuratie.
6. Open `https://<jouw-domein>/`, vul de test in en controleer de resultaten.
   `/data/wines.json`, `/app/questions.php`, `/docs/engineering/00-project.md`,
   `/.git/config` en `/tools/build-data.py` moeten 404 of 403 geven.
   Controleer HTTPS-redirect en mobiele weergave.

Er is bewust geen `install.php`: er wordt geen database geïnstalleerd. De bestanden
kunnen rechtstreeks worden geplakt. De daadwerkelijke IIS-omgeving is niet live
getest vanuit deze opdracht.

Bij HTTP 500.19: controleer IIS-log, URL Rewrite en ontgrendeling van de genoemde
configuratiesectie. Laat zo nodig de beheerder de site-instellingen toepassen;
verwijder niet zomaar de afscherming uit `web.config`. Bij HTTP 503: controleer
de drie JSON-bestanden in `data`, leesrechten en het PHP-errorlog. Publiceer geen
`phpinfo()`-bestand.

## Gebruik en beperkingen

- Alle 14 dimensies hebben twee vragen met herkenbare ingrediënten:
  “Vind je … lekker? Hoe lekker vind je het op schaal van 0 tot 10?”
- 0 = helemaal niet lekker, 5 = neutraal, 10 = heel lekker. Hele cijfers.
- Onbekende dimensies kun je overslaan. Beantwoord minstens twee dimensies.
- De top vijf druiven komt uit de oorspronkelijke 20 profielen, met alle
  ingevulde dimensies even zwaar. Dit betreft voornamelijk witte druiven.
- De grotere collectie bevat **1.007 wijnen**, **188 druivennamen** en
  **150.000 beoordelingen**. Die geeft apart maximaal twaalf matches op
  zuur/volheid. Vul daarvoor minstens een van die dimensies in. Het wijntypefilter
  geldt alleen voor deze collectie.
- Een overeenkomst van 80/100 betekent geen 80% kans op succes. De berekening is
  `100 * (1 - RMSE / 10)`. Waarderingen van andere drinkers staan daar los van.
  Bij gelijke wijnscore komt het grootste aantal beoordelingen eerst, daarna
  WineID. Bij gelijke druifscore wordt op naam gesorteerd.
- De vragen zijn een benadering, geen gevalideerde sensorische test. Een smaak
  lekker vinden is niet hetzelfde als er veel van willen. Streek, vinificatie en
  jaargang kunnen het daadwerkelijke smaakprofiel sterk veranderen.
- Bewaar het resultaat met de afdrukknop als PDF via je browser.

Antwoorden worden niet in een database, sessie, gedeeld bestand of localStorage
opgeslagen. Alleen de themakeuze wordt lokaal onthouden. Een noodzakelijke
sessiecookie beschermt het formulier. Browser en IIS kunnen hun gewone lokale
geschiedenis/toegangslogs bijhouden. Er zijn geen analytics of runtime-CDN's.

## Data

Zie [DATA-SOURCES.md](DATA-SOURCES.md) voor bronnen, licenties, schaalomrekening
en reproduceerbare data-opbouw. X-Wines bevat geen afzonderlijke numerieke
aroma-, tannine- of zoetheidsscores. Die worden niet verzonnen.

## Lokaal controleren

```powershell
php -l index.php
php tests/run.php
php -S 127.0.0.1:8080 -t .
```

Open `http://127.0.0.1:8080/`. De ontwikkelserver verwerkt `web.config` niet;
gebruik hem alleen lokaal. HTTP-regressies, met Node 18+ en draaiende PHP-server:

```powershell
node tests/http.mjs http://127.0.0.1:8080/
```

GitHub Actions bevat syntaxcontrole en PHP-tests. Een workflowresultaat is pas
bewezen na een werkelijk geslaagde run op GitHub.

## Beheer

Maak voor updates een bestandsback-up. Terugrollen: herstel de vorige mapinhoud.
Er zijn geen databasemigraties. Houd PHP en Bootstrap bij, vernieuw TLS volgens
je serverbeheer en controleer na updates formulier, resultaten, afscherming en
IIS/PHP-logs. Eigenaar en beheerder: Frank Jacobs.

De oude Python-app, requirements, afbeeldingen en gedeelde gebruikers-CSV zijn
uit deze versie verwijderd. De oude GitHub-repository blijft bestaan. De nieuwe
repository bevat geen oude gitgeschiedenis en gebruikt alleen de nieuwe origin.
E-mailverzending is vervangen door lokaal afdrukken/PDF, zonder SMTP-credentials.
Een eenduidige afstand vervangt KMeans en de inconsistente combinatie van
nearest-neighbour-afstand en cosinepercentages uit de oude app.
