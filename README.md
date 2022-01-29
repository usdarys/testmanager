# Test Manager

Prosty system do zarządzania testami. Aplikacja umozliwia:
- tworzenie bazy przypadków testowych
- tworzenie przebiegów zawierających wybrane przypadki
- wykonywanie przebiegów testów - dodawanie komentarzy, oznaczanie statusów poszczególnych testów
- podgląd statystyk z wykonywanych testów
- zarządzanie uytkownikami i dostepem do poszczególnych części aplikacji
## Instalacja
### Wymagania:
1. [Apache](https://httpd.apache.org/)
2. [php 7](https://www.php.net/downloads)
3. [Node.js + npm](https://nodejs.org/en/)
4. [Postgres](https://www.postgresql.org/)

### Instalacja (Bash / Powershell)
        git clone https://github.com/usdarys/testmanager.git
        cd testmanager/public
        npm install
### Konfiguracja
1. Utwórz nową bazę danych i wgraj skrypt z katalogu `./resource/sql/db.sql`
2. Skopiuj plik `./resource/config/config.php` do katalogu głównego aplikacji i uzupełnij dane połączeniowe do bazy
