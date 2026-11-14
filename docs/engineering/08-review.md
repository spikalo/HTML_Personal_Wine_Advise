# Zelfreview — implementatie 1.0.0

Reviewer: dezelfde agent als implementator; expliciet niet onafhankelijk.
Scope: index.php, vragen/logica, data-opbouw, browsercode, IIS-config, tests,
documentatie, assets en uiteindelijke nieuwe-repo-bestanden.

| ID | Bevinding / trigger | Ernst | Herstel / hertest |
| --- | --- | --- | --- |
| REV-01 | Legacy gedeelde user_data voor alle bezoekers | Middel | Geen gebruikersopslag, geen oude CSV; HTTP-sessiegrens getest |
| REV-02 | Malformed answers-collection kon renderer laten falen | Middel | oldAnswer controleert arrayvorm; HTTP 422 zonder fatal error |
| REV-03 | Ontbrekende smaken dreigden nulscore te worden | Middel | Alleen gemeenschappelijke kenmerken; aparte rankings en dekkingsuitleg |
| REV-04 | Zonder JS overgeslagen velden na POST niet invulbaar | Laag | Geen server-side disabled; JS beheert disabled; no-JS-regressie |
| REV-05 | Oude gitgeschiedenis bevat gebruikers-CSV | Middel | Nieuw git-init, alleen nieuwe bestanden pushen; broncommit alleen als documentverwijzing |

Ruwe controles: PHP-tests, HTTP-testscript, browser met console/pageerror,
datawaarden/IDs, XML en JS syntax. Productie-IIS kan lokaal niet worden bewezen;
README eist verborgen-paden-smoketest. Geen blokkerende codebevinding binnen
deze scope na herstel. Gehele veiligheid of foutloosheid wordt niet gegarandeerd.
