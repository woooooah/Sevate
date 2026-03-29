# Sevate Automation Pyramid – Plugin dokumentacija

## Struktura datotek

```
sevate-automation-pyramid/
├── assets/
│   ├── css/
│   │   └── pyramid.css          Vsi stili: layout, barve, gumbi, detail panel, responsive
│   └── js/
│       ├── pyramid-data.js      Podatki in config (SAP_CONFIG, SAP_LEVELS) — mora biti pred pyramid.js
│       └── pyramid.js           jQuery logika: geometrija, HTML gradnja, interakcija
├── includes/
│   ├── class-pyramid-data.php   Podatki (PHP): nivoji, storitve, URL-ji
│   └── class-pyramid.php        WordPress hooks: enqueue, shortcode
├── templates/
│   └── pyramid-template.php     HTML wrapper — vrnjen iz shortcode-a
├── test.html                    Statični preview za razvoj (ni del WP deploya)
└── sevate-automation-pyramid.php  Bootstrap: konstante, require, inicializacija
```

## Podatkovni model

Piramida ima 3 nivoje (od vrha navzdol):

| ID       | Label               | Sublabel | Opis (annotation)                                                  | Število storitev |
| -------- | ------------------- | -------- | ------------------------------------------------------------------ | ---------------- |
| `vidik`  | Vidik & Inteligenca | Vrh      | Človeški vmesnik sistema – vizualizacija, nadzor, poslovni sistemi | 3                |
| `logika` | Logika & Nadzor     | Jedro    | Možgani sistema – krmilna logika, integracija naprav               | 3                |
| `fizika` | Fizika & Energetika | Osnova   | Strojna oprema, elektrika, senzorji in aktuatorji                  | 4                |

Vsak nivo ima štiri polja na ravni nivoja:

- `id` — CSS modifier in ključ geometrije
- `label` — polno ime (izpisano v anotacijskem okvirčku levo)
- `sublabel` — kratki podnaslov v anotaciji
- `description` — razlagalni odstavek v anotacijskem okvirčku

Vsaka storitev znotraj nivoja ima tri polja:

- `name` — prikazano na gumbu in kot naslov v detail panelu
- `description` — besedilo v detail panelu
- `url` — cilj gumba "Izvedite več" _(v JS: `url`, v PHP: `page_url`)_

## Shortcode

```
[automation_pyramid]
```

Piramido vstaviš na katerokoli stran ali objavo v WordPressu z zgornjo kodo.

## Vizualna logika — 3-kolumni layout

```
┌─────────────────┬──────────────────────────┬──────────────────────┐
│  ANNOTATIONS    │       PYRAMID            │   DETAIL PANEL       │
│  260px          │       1fr                │   420px              │
│                 │                          │                      │
│ ─── Vidik ────▶ │    ╱▲╲                   │  Opis nivoja vidik   │
│                 │   ╱   ╲                  │                      │
│ ─── Logika ───▶ │  ╱─────╲                 │  Opis nivoja logika  │
│                 │ ╱       ╲                │                      │
│ ─── Fizika ───▶ │╱─────────╲               │  Opis nivoja fizika  │
│                 │___________╲              │                      │
└─────────────────┴──────────────────────────┴──────────────────────┘

Ko uporabnik klikne gumb storitve, se opisi nivojev skrijejo in
prikaže se detail kartica z imenom, opisom in linkom storitve.
```

### SVG geometrija

ViewBox: `860 × 690`. Apex: `430, 0`. Base: `0, 690` in `860, 690`.

Band višine (SVG enote, vrh → dno): vidik=270, logika=180, fizika=240 (skupaj 690).

| Nivo   | SVG points                        | CSS top% | CSS h%   | CSS pad% |
| ------ | --------------------------------- | -------- | -------- | -------- |
| vidik  | `430,0 598,270 262,270`           | 0%       | 39.1304% | 30.5%    |
| logika | `262,270 598,270 710,450 150,450` | 39.1304% | 26.087%  | 17.4%    |
| fizika | `150,450 710,450 860,690 0,690`   | 65.2174% | 34.7826% | 1.5%     |

Delilne črte: `y=270` (x: 262→598) in `y=450` (x: 150→710).

### Barve band

| Nivo   | Fill      |
| ------ | --------- |
| vidik  | `#0D1B2A` |
| logika | `#415A77` |
| fizika | `#E0E1DD` |

## Interaktivnost

- Klik na `.sap-service-btn` → zapolni `#sap-detail-name`, `#sap-detail-desc`, `#sap-detail-link`, prikaže `.sap-detail__card`, skrije opise nivojev (`#sap-right-annotations`). Gumb dobi class `.is-active`, prejšnji aktiven gumb ga izgubi.
- Klik na isti gumb → zapremo panel (toggle off).
- Klik na `#sap-detail-close` ali tipka `Escape` → zapremo panel.
- Hover na `.sap-level__services` → SVG polygon `.sap-bg-band--{id}` dobi `.is-hovered` (svetlejši fill).

## Urejanje vsebine prek CMS

Trenutno so vsi podatki piramide hard-coded v `includes/class-pyramid-data.php`. Ko bo treba omogočiti urejanje vsebine prek WordPress administracije, bo dovolj dodati Settings stran pod `WP Admin → Nastavitve → Piramida` in shranjevati podatke z WordPress Options API (`update_option` / `get_option`). Metoda `Sevate_Pyramid_Data::get_levels()` bo tedaj namesto statičnega arraya prebrala shranjene opcije in jih vrnila v enakem formatu — preostala koda (geometrija, template, shortcode) se ne bo spremenila.
