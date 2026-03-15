# phpTourney

phpTourney is a PHP/MariaDB web application for running 1v1 online gaming tournaments. It supports user management, news, match reports with screenshots, and tournament logic for single- and double-elimination brackets.

Released under the terms of the MIT license — see [LICENSE](LICENSE) for details.

---

## Requirements

- [Docker](https://docs.docker.com/get-docker/) with Compose

---

## Getting Started

### Start the stack

```bash
docker compose up -d --build
```

### Shut down

```bash
docker compose down
```

---

## Services

| Service      | Description                       | Local URL                 |
|--------------|-----------------------------------|---------------------------|
| `app`        | phpTourney (Apache + PHP)         | http://localhost:8080     |
| `mariadb`    | MariaDB 11 database               | *(internal only)*         |
| `phpmyadmin` | phpMyAdmin database UI            | http://localhost:8081     |
| `maildev`    | MailDev — catches outgoing email  | http://localhost:8082     |
| `smtp`       | Local SMTP relay (→ MailDev)      | *(internal only)*         |
| `cap`        | Cap CAPTCHA server                | http://localhost:8083     |

> **Cap CAPTCHA is disabled by default.** To enable it, set `PHPTOURNEY_CAP_FRONTEND_URL`, `PHPTOURNEY_CAP_BACKEND_URL`, `PHPTOURNEY_CAP_SITE_KEY`, and `PHPTOURNEY_CAP_SECRET_KEY` in your environment.

---

## Default Credentials

| Account | Username | Password   |
|---------|----------|------------|
| Admin   | `admin`  | `password` |

**Change the admin password after first login.**

---

## First-Time Setup

1. Register a new regular account, e.g. `alice`
2. Log in as `admin` and change the default password
3. Create a new section and season, choosing `alice` as head admin
4. From then on, manage that season with the `alice` account
