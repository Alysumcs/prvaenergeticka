# Prvá Energetická – statická náhľadová verzia

Toto je **statická HTML verzia** webu (bez PHP a databázy) určená len na to,
aby si videl, **ako web vyzerá** – vrátane dizajnu a GSAP animácií. Funguje na
Vercel, GitHub Pages, Netlify alebo aj po dvojkliku na `index.html`.

> Pozor: toto NIE je ostrá verzia. Administrácia (`/admin`), ukladanie článkov
> a odosielanie formulárov fungujú len v plnej **PHP + MySQL** verzii
> (priečinok `prvaenergeticka-web`), ktorú nasadíš na **Hostinger**.
> Vercel ani GitHub PHP nespúšťajú – preto sa ti tam `.php` súbory len sťahovali.

## Ako to nasadiť na Vercel
1. Nahraj tento priečinok do GitHub repozitára (alebo ho v Verceli „Import").
2. Vo Verceli pri importe nech **Framework Preset = Other**, žiadny build command,
   **Output Directory** nechaj prázdne / koreň.
3. Deploy. Web sa zobrazí na tvojej Vercel adrese.

## Ako to nasadiť na GitHub Pages
1. Nahraj obsah do repozitára (vetva `main`).
2. Settings → Pages → Source: `Deploy from a branch`, branch `main`, folder `/root`.
3. Po chvíli bude web na `https://tvojmeno.github.io/repo/`.

## Logo
Nahraj logo ako `assets/img/logo.png`. Ak chýba, zobrazí sa textový nápis
„PRVÁ ENERGETICKÁ".

## Stránky
index.html, sluzby.html, o-nas.html, plyn.html, elektrina.html, blog.html,
clanok-vitajte.html, faq.html, kontakt.html, zasady-ochrany-osobnych-udajov.html,
cookies.html
