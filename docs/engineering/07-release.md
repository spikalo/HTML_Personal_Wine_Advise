# Oplevering

Versie: 1.0.0, 14 november 2026. Eigenaar/beheerder/acceptant: Frank Jacobs.
PHP-website lokaal geïmplementeerd en getest. Geen database, migratie of secrets.
Nieuwe remote bedoeld: https://github.com/spikalo/HTML_Personal_Wineadvise (private).
Git-publicatiestatus wordt na API- en pushverificatie vastgelegd.

Gereedheidsbewijs: 06-testen en 08-review. Bron/data/licenties: DATA-SOURCES.
Installatie: README. Zelfreview is geen onafhankelijke review. GitHub Actions-
configuratie aanwezig; runstatus apart controleren. Geen branch protection claim.

Live deployment: niet uitgevoerd. Frank kopieert de projectinhoud naar de
SolidCP-documentroot en voert README-smoketest uit. Certificaat, PHP-FastCGI,
Rewrite, Request Filtering en sessiemap vooraf controleren. Geen secrets of
bedrijfsprofiel meeleveren. Bij mislukking vorige bestandsback-up terugzetten.

Na upload: formulier/resultaat, mobiel, HTTP→HTTPS, verborgen paden en PHP/IIS-log.
Alleen daarna is de specifieke serverinstallatie geverifieerd. Een GitHub-push
betekent hier geen automatische IIS-uitrol.
