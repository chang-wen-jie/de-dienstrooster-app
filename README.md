## De Dienstrooster App
Beheer personeel, dienstroosters, aan- en afwezigheid, en meer.

## Overzicht
- Weergeef personeelsaanwezigheid (inclusief kiosk vriendelijke weergavemodus)
- Weergeef maandoverzicht
- Stel vijf wekelijkse basisroosters (jaarlijks) op
- Meld ziekteverzuimen en vakantieverloven
- Beheer personeelsinformatie
- Laat personeel in- en uit checken met personeelspas

## Benodigdheden
- Laravel
- Composer

## Installatie
1. Clone dit project `git clone https://github.com/chang-wen-jie/de-dienstrooster-app.git`
2. Installeer alle projectafhankelijkheden `npm i` & `composer i`
3. Stel een databaseconnectie in `cp .env.example .env` & `php artisan key:generate`
4. Start de databaseserver op
5. Voer de databasemigraties uit `php artisan migrate --seed`
6. Draai de applicatie `npm run dev` & `php artisan serve`
