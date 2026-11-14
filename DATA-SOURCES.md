# Data en licenties

## Oorspronkelijke druivenprofielen

Bron: https://github.com/spikalo/NN_Personal_Wineadvise, commit
`6ea6b03438e6d89157b425b354c82c0d423445e4`, `csv/data.csv`.
De 20 profielen met 14 dimensies zijn bewaard als `data/source-grapes.csv` en
omgezet naar `data/grapes.json`. Alle scores maal twee: 0–5 wordt 0–10.
Herkomst van de oorspronkelijke sensorische getallen is onbekend; ze blijven
indicatief. De oorspronkelijke README noemt MIT zonder LICENSE-bestand. Dit is
een omzetting in opdracht van de eigenaar; geen nieuwe projectbrede licentie
wordt verondersteld.

## X-Wines Slim

- Officiële bron: https://github.com/rogerioxavier/X-Wines
- Overzicht: https://github.com/rogerioxavier/X-Wines/blob/main/Dataset/README.MD
- Officiële downloadmap: https://drive.google.com/drive/folders/1vfkfa5deOSsNf3dN2R7xrWWgVXUqD1-k
- Wijnen: https://drive.google.com/uc?export=download&id=1KJgeogB7vyagpEyv1ih4844TSRfZ0d9D
- Ratings: https://drive.google.com/uc?export=download&id=1mPD2IBBztT5lqHNgS8qgo_XMyZpuZjcx
- Download: 14 november 2026. Bron-SHA-256 staat in `data/metadata.json`.
- Gecontroleerd: 1.007 unieke WineIDs, 188 druivenstrings, 150.000 beoordelingen.
  Synoniemen in druivennamen zijn niet automatisch samengevoegd.
- Licentie: CC0 1.0, officiële tekst in `data/LICENSE-XWINES.txt`.
- Verplichte bronvermelding: de Azambuja, R. X.; Morais, A. J.; Filipe, V. (2023).
  **X-Wines: A Wine Dataset for Recommender Systems and Machine Learning**.
  Big Data and Cognitive Computing 7(1), 20. https://doi.org/10.3390/bdcc7010020

Het volledige onderzoek omvat 100.646 wijnen en ruim 21 miljoen ratings. Hier
wordt de officiële Slim-versie gebruikt voor eenvoudige hosting. Verzameld in
2022 met beoordelingen uit 2012–2021; geen actuele winkelvoorraad of prijzen.

| Bronveld | Website |
| --- | --- |
| Acidity: Low / Medium / High | 0 / 5 / 10, relatieve ordinale schaal |
| Body: Very light / Light / Medium / Full / Very full-bodied | 0 / 2,5 / 5 / 7,5 / 10 |
| Rating | Rekenkundig gemiddelde per WineID, oorspronkelijke schaal 1–5 |
| Aantal beoordelingen | Aantal ratingregels per WineID |
| ABV | Alcoholpercentage, informatief, geen smaakmatching |

Categorieën zijn geen laboratoriummetingen. UserID, RatingID, individuele ratings,
datums en jaargangen worden niet meegeleverd. Gemiddelden zijn over beschikbare
jaargangen samen. Ontbrekende kenmerken worden niet als nul geïnterpreteerd.
Er zijn geen numerieke aroma-, zoetheids- of tanninescores in deze bron. Daarom
blijven de 14-dimensie-druifscore en de 2-dimensie-wijnscore gescheiden. Een
onderzochte Hugging Face-verrijking bevat algemene teksten; die wordt niet
gebruikt als proefbeoordeling.

Opnieuw bouwen (alleen ontwikkeling): download beide officiële CSV's buiten de
webroot en voer met Python 3 (standaardbibliotheek) uit:

```powershell
python tools/build-data.py C:\temp\wines.csv C:\temp\ratings.csv data/source-grapes.csv
php tests/run.php
```

Controleer wijzigingen en herhaal de HTTP-test voor publicatie. De server heeft
Python en ruwe ratingbestanden niet nodig.

## Website-assets

- Bootstrap 5.3.8: https://github.com/twbs/bootstrap/tree/v5.3.8 — MIT,
  tekst in `assets/vendor/bootstrap/LICENSE`. CSS lokaal gehost.
- Logo/favicon en design tokens uit aangeleverde Frank Jacobs-assets. Logo
  ongewijzigd. Het tokenbestand is een aangeleverd voorstel; hiermee wordt geen
  nieuw bedrijfsbeleid geactiveerd. Systeemfont bij ontbrekende Inter-font.
