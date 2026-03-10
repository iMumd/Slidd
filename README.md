<div align="center">
  <img src="https://raw.githubusercontent.com/iMumd/Slidd/refs/heads/main/public/icon-512.png" width="72" alt="Slidd">
  <h1>Slidd</h1>
  <p>Presentation builder for developers.</p>
  <a href="https://slidd.app">slidd.app</a>
</div>

---

Slidd is an open-source presentation tool built around two modes: a block-based slide editor for linear content, and Galaxy Space — an infinite canvas for visual thinking. Code blocks are first-class citizens with syntax highlighting built in.

## Features

- **Block editor** — text, code, and image blocks, keyboard-first workflow
- **Galaxy Space** — infinite canvas with nodes, edges, pan and zoom
- **Public sharing** — read-only shareable link for any project
- **Export / import** — portable `.slidd` JSON format

## Stack

- Laravel 12, PHP 8.2+
- Alpine.js, Tailwind CSS, Vite
- SQLite (default)

## Self-hosting

```bash
git clone https://github.com/iMumd/Slidd.git
cd Slidd

cp .env.example .env

composer install
npm install && npm run build

php artisan key:generate
touch database/database.sqlite
php artisan migrate

php artisan serve
```

**Mail** — by default, emails are written to `storage/logs` (nothing is sent). To deliver real emails, set `MAIL_MAILER=smtp` in `.env` and fill in your credentials. See `.env.example` for examples with Resend, Mailgun, Postmark, and SendGrid.

**Deployment** — set `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`, and `LOG_LEVEL=error` in your production `.env`.

## Contributing

Open to contributions — open an issue or pull request on [GitHub](https://github.com/iMumd/Slidd).

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
