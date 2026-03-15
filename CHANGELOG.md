# Changelog

## 0.11.0

### Added
- Cap CAPTCHA integration on account registration to reduce spam
- Extended admin command-line tools (rename, merge, delete users; delete news comments)

### Changed
- Upgraded to PHP 8
- Updated Parsedown to 1.8.0 (fixes PHP 8 deprecation warnings)
- Updated normalize.css from 4.1.1 to 8.0.1
- Overhauled Docker setup and simplified volumes
- Reworked UI: header, logo, sidebar, timestamps, text sizes, and box heading styles
- Reworked table structure

### Fixed
- Grand final reset match not getting players assigned
- Grand final reset match showing before it is needed
- Stale screenshot thumbnails after cropping
- PHP notice for `array_pop` called with a non-variable
- Table bracket visibility before the tournament started
- Favicon

### Removed
- reCAPTCHA support (replaced by Cap)
- Bans
- Comment counter
- Unused graphics and DHTML assets

---

## 0.10.0 — 2020-11-15

### Added
- Admin command-line tools for user management

### Changed
- Cleaned up Docker entrypoint

---

## 0.9.5 — 2020-10-18

### Changed
- Reworked mail handling

---

## 0.9.4 — 2020-10-18

### Changed
- Updated to PHP 7
- Removed IRC channel field from user profiles
- Minor Docker configuration changes

### Fixed
- Editing user profiles as a headadmin

---

## 0.9.3 — 2020-02-26

### Changed
- Switched to a different Docker registry

### Added
- `.dockerignore`

---

## 0.9.2 — 2020-02-22

### Added
- GitLab CI pipeline for automated Docker image builds

---

## 0.9.1 — 2020-02-22

### Changed
- Finalized configuration via environment variables
- Show latest season by default

### Fixed
- Editing of user profile
- Admin comments in match report
- PHP and SMTP configuration

---

## 0.9.0 — 2018-05-05

### Added
- Dockerized the application
- reCAPTCHA support on registration
- Normalize.css
- Flags in the table bracket
- BCC functionality for outgoing mail
- Dedicated database user on first run

### Changed
- Switched to MIT license
- Switched to MySQLi API
- Replaced magic quotes with proper manual escaping of all request data
- Proper conversion of text from the database (HTML entities, `nl2br`, Markdown)
- Overhauled season management (removed sections and network)
- Reworked player invitations and rejection handling
- Reworked how CSS is handled
- Changed HTTP and database charsets to UTF-8
- Hide email addresses from the public; show opponent's address only to the next opponent
- Players can no longer crop screenshots
- Mail is always enabled; removed signup poll
- Put PHP and template files alongside each other
- Added a space to long words after 80 characters instead of 50

### Fixed
- Bug in tournament system when evaluating next matches for winner and loser brackets
- Season selector
- Wrong title in the users overview
- Overflow of comments

### Removed
- IRC bot
- Servers module
- Sections and network
- Table prefix
- Template file config
- Version file
- Vignette from the site

---

## 0.8.9 — 2012-01-18

### Changed
- Removed execution time and copyright notice from the page
- Notification emails are now only sent when both players are known for the next match
- Revised email greetings and links
- Turned off caching
- Correct `Content-Type` header is now written
- Fixed cookie behaviour so phpTourney can be installed in paths containing a dot

### Fixed
- Missing maps in a match report showed wrong screenshots
- Links containing `javascript:` (did not work in Chrome)
- Admins view (flags were not working properly)
- Bug in notification email when the tournament starts (only half of the players received a correct email)

---

## 0.8.8 — 2010-01-16

### Added
- Script execution time integrated in the template

### Fixed
- Qualification (previous-match-check did not work)

---

## 0.8.7

### Fixed
- Signup button pointed to the wrong season
- Serverlist issue when adding the very first serverlist
- Rule issue when adding the very first rules
- Removed test section from the SQL file

---

## 0.8.6

### Added
- `mail_reply_to_address` config option

### Fixed
- Writing the very first news entry did not work
- Reporting the very first match did not work
- Mail function in safe mode (no 5th argument)

---

## 0.8.5

- Initial release
