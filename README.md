# Docker-Case-PHP-Template

Dockermiljö för att utveckla en fullstack applikation baserad på PHP och MySQL. I konfigurationen för PHP används <a href="https://httpd.apache.org/" target="_blank">Apache</a> som webbserver.


## Förutsättningar

Docker Desktop ska vara installerat och startat.

https://www.docker.com/


## Hello world

Gör följande steg:

1. Klona ner repot
2. Öppna terminalen och ange kommandot: `docker-compose up`
3. När applikationen startat (tar ngn minut första gången) öppna en webbläsare och navigera till 
http://localhost:8050


I webbläsaren bör du nu se "Hello world"


## MySQL via phpMyAdmin

- Följ steg 1 och steg 2 ovan
- Öppna en webbläsare och navigera till: 
http://localhost:8051

---

För att logga in anger du:

- Server: `mysql`
- Användarnamn: `db_user`
- Lösenord: `db_password`

I phpMyAdmin kan du nu se den databas som finns: `db_template`

---

Informationen ovan (portar, variabler) baseras på filen `docker-compose.yml`

---

## Miljövariabler

Instruktion om hur man använder filen `.env`

Gör en kopia på filern `.env-example` och namnge den till `.env`

Här lägger du in det uppgifter som gäller för din miljö, ex känslig data som namn på databas, rättigheter till databas etc 

Filen `.env` ska inte versionshanteras normalt. Det kan man lösa genom att skapa en fil med namnet `.gitignore`.

I filen `.gitignore` kan man peka ut både filer och mappar för att de inte ska finnas på GitHub.


## Installation av databas

För att applikationen ska kunna köras som det är tänkt så ska följande tabeller finnas.


Tabellen users

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- Lagrar hashat lösenord
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```


Tabellen posts

```sql
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,             -- Vem som skrev inlägget
    title VARCHAR(255) NOT NULL,      -- Inläggets titel
    body TEXT NOT NULL,               -- Innehållet i inlägget
    image_path VARCHAR(255) NULL,     -- Sökväg till uppladdad bild (valfritt)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- När inlägget senast ändrades
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Koppling till users. Om användaren raderas, raderas även hens inlägg.
);
```


Tips

Skapa en setup fil i applikationen som kan installera de tabeller som används. Med en setup fil behöver man då inte använda phpMyAdmin för att lägga till tabeller.

Filen kan ex namnges `setup.php`, `_setup.php`. Ett inledande understreck kan signalera ngt specifikt i vissa appar...


Navigera till http://localhost:8050/_setup.php