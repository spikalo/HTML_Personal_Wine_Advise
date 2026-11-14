# HTML Personal Wineadvise

Opdracht: NN_Personal_Wineadvise omzetten naar direct kopieerbare PHP/HTML-site
voor SolidCP; alle vragen naar herkenbare smaken en 0–10; grotere dataset;
geen database; opruimen en nieuwe private GitHub-repository.

Niveau: Standaard vanwege internetgebruik en blijvend onderhoud. Geen accounts,
betalingen, uploads, persoonlijke opslag of beheerfuncties. Alle ontwerp-,
code-, security-, test-, review- en releaseperspectieven uitgevoerd door dezelfde
agent; zelfreview, geen onafhankelijke beoordeling. Company context: zie 00-company-context.

Baseline: broncommit 6ea6b03438e6d89157b425b354c82c0d423445e4. Oude app schreef
alle bezoekers naar dezelfde user_data.csv en gebruikte verschillende afstanden
voor rangschikking en percentages. Nieuwe versie verwerkt antwoorden per request
zonder opslag en gebruikt één verklaarbare afstand per adviesgroep.

Omvang: 14 dimensies / 28 vragen, top 5 oorspronkelijke druivenprofielen,
12 wijnmatches uit 1.007 wijnen, filter en afdrukken/PDF, lokale assets.
Buiten scope: live serverwijzigingen, SMTP, login, learning model trainen,
webscraping tijdens gebruik, database. Eigenaar/acceptant: Frank Jacobs.

Status: implementatie en lokale controles voltooid. Zie 06-testen voor bewijs,
07-release voor publicatiestatus. Live IIS-smoketest en gebruikersacceptatie zijn
nog uit te voeren na kopiëren. Herbeoordelen bij accounts, schrijfopslag, uploads
of externe API's. Hervatten: README installatie; php tests/run.php.
