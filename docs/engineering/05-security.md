# Beveiligingscontrole

| ID | Risico / impact | Maatregel en verificatie | Restpunt / eigenaar |
| --- | --- | --- | --- |
| SEC-01 | Gedeelde CSV lekt/overschrijft bezoekersvoorkeuren | Geen schrijfacties/gebruikers-CSV; stateless POST; sessie alleen CSRF | Gesloten in code |
| SEC-02 | HTML/script-injectie | Shape/range/allowlist-validatie, htmlspecialchars, CSP; negatieve HTTP-tests | Gesloten binnen geteste routes |
| SEC-03 | Cross-site verzoeken | CSRF hash_equals, cookie HttpOnly/SameSite, Secure bij HTTPS; cross-session afwijzing | Gesloten lokaal |
| SEC-04 | Bron/docs bereikbaar in documentroot | Request Filtering, directory browse uit, geen secrets | Live IIS-paden controleren, Frank |
| SEC-05 | Resource-uitputting | IIS/PHP bodylimiet 32KB, vaste dataset, geen externe runtime requests | Geen loadtest/SLA; Frank monitort IIS |
| SEC-06 | Onterechte dataclaims | Bronmapping/dekking zichtbaar, geen ontbrekende smaken als nul | Heuristisch advies blijft beperking |

Geen accounts/auth-objecten, SQL, betalingen, upload, vrije paden, shell of
beheerdersfuncties in de app. SQL-/tenant-/concurrentieaanvallen op datamutaties
zijn daarom niet van toepassing. Alleen eigen lokale testomgeving getest, geen
ongevraagde scans van server/netwerk. PHP-download van officiële vendor en
SHA-256 vergeleken. Bootstrap lokaal met MIT-bron. Geen volledige pentest of
certificering geclaimd. Geen open kritiek/hoog codeprobleem in de zelfreview;
IIS-afscherming en TLS blijven ongeverifieerd op productie.
