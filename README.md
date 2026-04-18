# UDP Programimi me Sockets

## Përshkrimi i Projektit
Ky projekt implementon një sistem komunikimi nëpërmjet soketave UDP në PHP. Serveri menaxhon kërkesat e klientëve për operacione me skedarë, duke diferencuar midis klientëve Admin dhe klientëve Standard.  
Përveç serverit kryesor UDP, është implementuar edhe një HTTP Server i thjeshtë që funksionon paralelisht për monitorimin e statistikave të serverit.

## Veçoritë Kryesore

### Server
- UDP Server në portin `9090`
- Limit i lidhjeve (maksimum 4 klientë njëkohësisht)
- Timeout automatik për klientët joaktivë (60 sekonda)
- Regjistrim i mesazheve në `logs.txt`
- Menaxhim i privilegjeve (Admin vs Standard)
- Operacione me skedarë: list, read, upload, delete, info

### HTTP Monitor
- Funksionon paralelisht në portin `8081`
- Endpoint: `GET /stats`
- Kthen statistika në format JSON (lidhje aktive, mesazhe, klientë, etj.)

### Klientët
- `admin_client.php` – Klient me privilegje të plota (Admin)
- `udp_client.php` – Klient standard (vetëm operacione leximi)

## Struktura e Projektit
```
PROGRAMMING-WITH-SOCKETS/
├── client/
│   ├── admin_client.php
│   └── udp_client.php
├── server/
│   ├── client_manager.php
│   ├── config.php
│   ├── file_manager.php
│   ├── http_server.php
│   ├── request_handler.php
│   ├── udp_server.php
│   └── logs.txt
├── shared/
│   └── files/
│       └── .gitkeep
└── README.md
```

---
## Si të ekzekutohet programi

**Shënim i rëndësishëm:** Sepse `sockets` extension nuk është aktivizuar në PHP-në tuaj, duhet të përdorni `-d extension=sockets` çdo herë.

Hapni **3 terminale** të ndryshme dhe përdorni një nga dy mënyrat më poshtë:

### Mënyra 1: Duke qenë brenda folderit

```bash
# Terminal 1 – UDP Server
cd server
php -d extension=sockets udp_server.php

# Terminal 2 – HTTP Monitor Server
cd server
php -d extension=sockets http_server.php

# Terminal 3 – Admin Client
cd client
php -d extension=sockets admin_client.php
```

### Mënyra 2: Nga rrënja e projektit

```bash
# Terminal 1 – UDP Server
php -d extension=sockets server/udp_server.php

# Terminal 2 – HTTP Monitor Server
php -d extension=sockets server/http_server.php

# Terminal 3 – Admin Client
php -d extension=sockets client/admin_client.php
```

**Opsional – Standard Client**
```bash
php -d extension=sockets client/udp_client.php
```

Varësisht nga numri i klientëve që dëshironi të testoni, hapni aq terminale sa nevojiten.

## Komandat

### Admin Client
- `/list`
- `/read <filename>`
- `/upload <filename>`
- `/delete <filename>`
- `/info <filename>`

### Standard Client
- `/list`
- `/read <filename>`
- `/search <keyword>`
- `/info <filename>`
