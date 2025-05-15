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