## De Dienstrooster App

Met dit systeem kun je gemakkelijk de diensten en aanwezig- en afwezigheden van het personeel registreren, aanpassen, en/of (periodiek) inplannen.
Verder vallen alle gebeurtenissen gemakkelijk in één oogopslag te zien binnen het ingebouwde kalenderoverzicht.

## Stapsgewijze Gebruiksaanwijzing

- 1: Clone dit project binnen het gewenste folder (`git clone https://github.com/chang-wen-jie/de-dienstrooster-app.git`)
- 2: Installeer alle externe pakketten (`npm i` & `composer i`)
- 3: Hernoem het `.env.example` bestand naar `.env` en pas het toe naar de desbetreffende databasegegevens
- 4: Start de databaseserver op
- 5: Stel alle tabellen en kolommen in de database op en voer de migraties uit (`php artisan migrate --seed`)
- 6: Start de ontwikkelingsomgeving op en draai de applicatie lokaal (`npm run dev` & `php artisan serve`)
