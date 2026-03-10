# Virgo API - Aplikacja do wyświetlania ofert nieruchomości

Aplikacja PHP wyświetlająca oferty nieruchomości z systemu GALACTICA VIRGO.

## Wymagania

- **PHP 5.6+** (zalecane PHP 7.x lub 8.x)
- **MySQL / MariaDB**
- Rozszerzenia PHP: **SOAP**, **ZIP**, **MySQLi**, **XML**
- Serwer WWW: **Apache** (z mod_rewrite) lub **nginx**

## Szybka instalacja (krok po kroku)

### 1. Sklonuj repozytorium

```bash
git clone https://github.com/Angus1994PL/test4you.git
cd test4you
```

### 2. Utwórz plik konfiguracji `.env`

Skopiuj przykładowy plik konfiguracji i uzupełnij dane:

```bash
cp .env.example .env
```

Edytuj plik `.env` i ustaw swoje dane:

```ini
# Konfiguracja bazy danych
VIRGO_DB_HOST=localhost
VIRGO_DB_PORT=3306
VIRGO_DB_NAME=host475709_virgo_api
VIRGO_DB_USER=host475709_root
VIRGO_DB_PASS=TWOJE_HASLO_DO_BAZY

# Konfiguracja Virgo API
VIRGO_WEBSERVICE_URL=https://ex.galapp.net
VIRGO_WEB_KEY=5dea1029-c346-4f4c-93ae-84be44bb3f8b
VIRGO_GAL_APP_DOMAIN=https://sl.galapp.net
VIRGO_SYNC_INTERVAL=3600
```

### 3. Utwórz bazę danych

Zaloguj się do MySQL i utwórz bazę danych:

```sql
CREATE DATABASE host475709_virgo_api CHARACTER SET utf8 COLLATE utf8_general_ci;
```

### 4. Uprawnienia do katalogów

Nadaj uprawnienia do zapisu dla katalogów cache i zdjęć:

```bash
chmod -R 777 templates_c/
chmod -R 777 photos/
chmod -R 777 virgo_api/logs/
```

### 5. Zainstaluj bazę danych

Otwórz w przeglądarce:

```
http://twoja-domena.pl/install_db.php
```

Ta strona utworzy wszystkie wymagane tabele w bazie danych.

### 6. Uruchom synchronizację danych

Otwórz w przeglądarce:

```
http://twoja-domena.pl/updatesite.php
```

To pobierze oferty z serwera Virgo API i zapisze je w lokalnej bazie danych.

### 7. Otwórz aplikację

Strona z ofertami:
```
http://twoja-domena.pl/index_o.php
```

Strona z inwestycjami:
```
http://twoja-domena.pl/index_i.php
```

## Uruchomienie lokalne (PHP built-in server)

Jeśli nie masz serwera Apache/nginx, możesz uruchomić aplikację lokalnie za pomocą wbudowanego serwera PHP:

```bash
cd test4you
php -S localhost:8080
```

Następnie otwórz w przeglądarce:
- Instalacja bazy: `http://localhost:8080/install_db.php`
- Synchronizacja: `http://localhost:8080/updatesite.php`
- Oferty: `http://localhost:8080/index_o.php`
- Inwestycje: `http://localhost:8080/index_i.php`

## Struktura projektu

```
test4you/
├── .env.example          # Przykładowa konfiguracja (skopiuj do .env)
├── index_o.php           # Strona główna z ofertami
├── index_i.php           # Strona z inwestycjami
├── install_db.php        # Instalator bazy danych
├── updatesite.php        # Synchronizacja danych z Virgo API
├── api.php               # Główny punkt wejścia API
├── web_api/
│   └── config.php        # Konfiguracja (ładuje z .env)
├── virgo_api/
│   └── lib/              # Klasy API (oferty, inwestycje, zdjęcia)
├── templates/            # Szablony Smarty (HTML)
├── css/                  # Style CSS
├── js/                   # Skrypty JavaScript
├── photos/               # Zdjęcia ofert (generowane automatycznie)
└── docs/                 # Dokumentacja
```

## Automatyczna synchronizacja (CRON)

Aby oferty były automatycznie aktualizowane, dodaj zadanie CRON:

```bash
# Synchronizacja co godzinę
0 * * * * curl -s http://twoja-domena.pl/updatesite.php > /dev/null 2>&1
```

Lub za pomocą PHP CLI:

```bash
0 * * * * php /sciezka/do/test4you/updatesite.php > /dev/null 2>&1
```

## Konfiguracja Apache (.htaccess)

Plik `.htaccess` jest już dołączony i obsługuje wirtualne trasy (np. wirtualne spacery).

Upewnij się, że w konfiguracji Apache masz włączony `mod_rewrite`:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## Rozwiązywanie problemów

### Brak rozszerzeń PHP

Jeśli `install_db.php` zgłasza brak rozszerzeń:

```bash
# Ubuntu/Debian
sudo apt-get install php-soap php-zip php-mysql php-xml

# CentOS/RHEL
sudo yum install php-soap php-zip php-mysql php-xml
```

### Błąd połączenia z bazą danych

Sprawdź dane w pliku `.env` - host, nazwa bazy, użytkownik i hasło.

### Brak ofert po instalacji

Uruchom synchronizację: `http://twoja-domena.pl/updatesite.php`

### Błędy uprawnień

```bash
chmod -R 777 templates_c/ photos/ virgo_api/logs/
```

## Licencja

GALACTICA VIRGO API - biblioteka PHP do wyświetlania ofert nieruchomości.
