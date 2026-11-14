# Projectcontext — 14 november 2026

Profiel: frank-jacobs, revisie 2. Eigenaar: Frank Jacobs. Gelezen uit de door de
gebruiker aangeleverde map `../company policy/company-policy.md` en de zeven
onderliggende documenten plus CSS/assets. Profielstatus: concept. Het profiel
verwijst naar een andere serverbron; die is hier niet geverifieerd. Geen globale
profielactivering of wijziging van die bron uitgevoerd. De actuele opdracht en
bevestigde regels zijn toegepast; conceptwaarden zijn als voorstel behouden.

| Domein | Toepassing |
| --- | --- |
| Eigenaar/beheer | Frank Jacobs, eigen project; geen tweede bedrijf |
| Hosting | IIS/SolidCP/PHP volgens opdracht; profiel noemt PHP 8.5.12, niet live hergemeten |
| Stack | DEV-01/PR-01: PHP/HTML/vanilla JS/Bootstrap; MySQL niet van toepassing |
| Git | DEV-02/PR-20: GitHub spikalo, nieuwe private repository, main |
| Assets | UI-03: aangeleverd logo/favicon ongewijzigd; PR-03: lokale Bootstrap/CSS |
| Security | PR-12: escaping en CSRF; SEC-01/PR-14: geen secrets in git |
| Documentatie | DEV-04/PR-25: docs/engineering |

Projectafwijkingen, expliciet door gebruiker gevraagd: geen database en dus geen
database-install.php (DEV-03/PR-22); direct naar documentroot kopieerbaar, interne
mappen via IIS afgeschermd in plaats van aparte public-root (ARCH-01). Er zijn
geen secrets/configbestanden of beheerschermen. TOTP/login-regels voor beheertools
zijn daarom niet van toepassing. Alleen tijdelijke CSRF-sessie, geen gedeelde
gebruikersbestanden. Zie 04-besluiten.md. Interne profiel-/netwerkdetails worden
niet meegepubliceerd. Tokens zijn geen bevestiging van nieuw bedrijfsbeleid.
