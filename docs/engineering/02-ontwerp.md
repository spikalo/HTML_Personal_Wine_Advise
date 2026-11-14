# Ontwerp

Browser → index.php → vaste app-functies en read-only JSON → HTML-resultaat.
PHP-sessie bevat uitsluitend CSRF-token, geen antwoorden. Sessielock wordt direct
na tokenverwerking gesloten. Browser bewaart alleen themakeuze. Geen gedeelde
CSV-mutatie, database, externe runtime-request of e-mail. Onvertrouwde POST-data
gaat via allowlists/shape/rangecontrole en alle uitvoer via htmlspecialchars.

14 dimensies, 2 antwoorden per dimensie; gemiddelde op 0–10. Overslaan sluit de
dimensie uit, vult geen nul in. Minimaal twee dimensies. Originele druivenprofielen
worden lineair verdubbeld. Score = 100 × (1 − RMSE/10) over ingevulde dimensies;
top vijf. Die vergelijking is heuristisch: waardering ≠ gewenste intensiteit.

Wijncollectie gebruikt alleen zuur/volheid met eigen categorische bronmapping,
maximaal twaalf; filter op zes geldige types. Geen aromascores uit grapenamen
afgeleid. Bij gelijke score aantal ratings aflopend, daarna ID. Gemiddelde publieke
waardering staat los van persoonlijke overeenkomst. Bronratings lokaal vooraf
geaggregeerd; UserIDs/individuele reviews niet gedeployed. Zie DATA-SOURCES.

Documentroot is projectroot op expliciet verzoek. app/data/docs/tests/tools/.git
zijn met IIS Request Filtering verborgen; geen secrets in project. Web.config
regelt defaultdocument, HTTPS en requestlimiet, PHP geeft CSP/securityheaders.
PHP-ontwikkelserver negeert web.config en is alleen lokaal toegestaan.

Volledige dataset wordt per verzoek ingelezen (circa enkele honderden kB); geen
extra infrastructuur voor 1.007 rijen. Geen gelijktijdige datamutaties; transactionele
tests niet van toepassing. Bestandsback-up en mapherstel zijn voldoende rollback.
