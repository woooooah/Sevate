/* Sevate Automation Pyramid – pyramid-data.js
   Single source of truth for all pyramid content and geometry constants.
   Must be loaded before pyramid.js.
   ============================================================= */

window.SAP_CONFIG = {
  VB_W: 860,
  VB_H: 690,
  BAND_HEIGHTS: [270, 180, 240], // vidik | logika | fizika  (top → bottom)
};

window.SAP_LEVELS = [
  {
    id: "vidik",
    label: "Vidik & Inteligenca",
    sublabel: "Vrh",
    description:
      "Človeški vmesnik sistema. Podatke iz spodnjih nivojev spremenimo v uporabno informacijo — vizualizacija, nadzor in povezava z višjimi poslovnimi sistemi.",
    services: [
      {
        name: "SCADA sistemi",
        description:
          "Razvoj nadzornih centrov (WinCC, Ignition, ipd.) za spremljanje celotne proizvodnje v realnem času.",
        url: "/storitve/scada-sistemi/",
      },
      {
        name: "Operaterski paneli (HMI)",
        description:
          "Izdelava intuitivnih lokalnih zaslonov za upravljanje strojev neposredno na terenu.",
        url: "/storitve/operaterski-paneli/",
      },
      {
        name: "MES / ERP",
        description:
          "Razvoj orodij za zajem podatkov in povezavo z MES ali ERP sistemi — vrh CIM piramide.",
        url: "/storitve/mes-erp/",
      },
    ],
  },
  {
    id: "logika",
    label: "Logika & Nadzor",
    sublabel: "Jedro",
    description:
      "Možgani avtomatiziranega sistema. Fizični svet postane inteligenten — avtomatika, krmilna logika in medsebojna integracija naprav.",
    services: [
      {
        name: "PLC programiranje",
        description:
          "Razvoj krmilne logike (Siemens, Rockwell, Beckhoff, ipd.) za upravljanje procesov in strojev.",
        url: "/storitve/plc-programiranje/",
      },
      {
        name: "Sistemska integracija",
        description:
          "Povezovanje različnih naprav (pogoni, frekvenčni regulatorji, senzorji) v enotno delujoč sistem.",
        url: "/storitve/sistemska-integracija/",
      },
      {
        name: "Testiranje & zagon",
        description:
          "Štartanje linij, parametrizacija in optimizacija delovanja sistema v realnem času.",
        url: "/storitve/testiranje-zagon/",
      },
    ],
  },
  {
    id: "fizika",
    label: "Fizika & Energetika",
    sublabel: "Osnova",
    description:
      "Trdni temelji brez katerih višji nivoji ne delujejo. Strojna oprema, elektrika, senzorji in aktuatorji — elektrotehniko in strojništvo združimo v celoto.",
    services: [
      {
        name: "E-plan",
        description:
          "Projektiranje in izdelava popolne tehnične dokumentacije v skladu z industrijskimi standardi.",
        url: "/storitve/e-plan/",
      },
      {
        name: "Strojna izvedba",
        description:
          "Izdelava elektro omaric, kabelskih poti, nabava ustrezne opreme od preverjenih dobaviteljev.",
        url: "/storitve/strojna-izvedba/",
      },
      {
        name: "Montaža",
        description:
          "Montaža senzorjev, aktuatorjev in ostale terenske opreme po projektni dokumentaciji.",
        url: "/storitve/montaza/",
      },
      {
        name: "IO testiranje",
        description:
          'Preverjanje integritete signalov, testiranje senzorjev in izvršnih elementov – zagotovilo, da "žica drži".',
        url: "/storitve/io-testiranje/",
      },
    ],
  },
];
