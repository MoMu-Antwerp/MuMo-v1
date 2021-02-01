# Todo:
## long done
- [x] Pictures history
- [x] Fix icon alignments (mainly on the battery icons)
- [x] Iframe page
- [x] Hash date into url to check if recent. Also protection method.
- [x] Battery level into some levels
- [x] unregistered device krijgt geen naam op sensor pagina. Hier unregistered of de UID geven
- [x] Er is slechts 1 foto zichtbaar bij de bug detectie momenteel
- [x] Tijdsrange limiteren (momenteel bij node 7 een tijdswaarde van -20000)
- [x] group op main level niveau slaat NULL op als 0 en werkt dus niet
- [x] Lege device naam mag momenteel, maar ziet er vreemd uit in weergaven. Dus of null houden of naam verplichten.
- [x] external url is altijd getoont na de naam. Hoort enkel indien er iets ingegeven is
- [x] Een dag toevoegen aan de grafieken. Of de code neemt de data tot 00:00 in de ochtend van de dag
- [x] Thresholds nakijken en alerts sturen
- [x] Bij alert nakijken of dezelfde alert al niet actief is.
- [x] notifications lijst weergeven
- [x] per sensor een geschiedenis van notifications weergeven
- [x] Group deleten zorgt ervoor dat subgroups verdwijnen. Subgroups aanpassen naar main group!
## recently done
- [x] Dust and VOC was sometimes mixed up
- [x] Global settings zijn lokaal aanpasbaar.
- [x] batterijalarm niet gewenst bij de gateways
- [x] Users privileges are not updated to database correctly
- [x] Frame counter terug activeren
- [x] Admin privileges
- [x] Indien alert gedaan maar er nog een open staat einde mailen...
- [x] Signaalsterkte verwerken tot iets nuttigs
- [x] Show endpoint url
- [x] Time alerts werken nog niet denk ik?
- [x] Gap in grafiek door Null data in te geven bij een gap
- [x] Data export van data
- [x] endpoint url checken of cleanen
- [x] Keuze of metingen met of zonder 0 punt in grafiek staan
- [x] endpoint -> Url met sluitende / of niet. en http://www.site.be of http://mumo.site.be
## open
- [ ] Tijdszone instelling (server tijd tov pc tijd tov sql)
- [ ] Update calibration nulls if code version changes
- [?] sensor deleten -> Niet deleten maar offline maken zodat nieuwe data er terug bij kan komen (3 standen, actief, tijdelijk uit, permanent weg)
- [x] Allow time selection to be disabled on an iframe page
- [x] Iframe opties uitbreiden (welke weergaven, tijdsframe, ...)
- [x] Wat als gebruiker verwijderd is. Slaat het mailsysteem tilt? -> Nee, die skipped em.
- [x] Gebruiker delete optie


## extra
- [-] Uplink van threshold instellingen of enkel tijdinstelling
- [-] Plotse pieken detecteren


## Hardware
- [ ] Alerts instellingen in de uitleg bij assemblage van de node
- [ ] Hoekje van de support blok => en afschuining op gaten
- [ ] The Things Network Payload format los van de uitleg over die Payload
- [ ] L led knipperen in error