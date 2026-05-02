/* global jQuery, SAP_CONFIG, SAP_LEVELS */
(function ($) {
  "use strict";

  // ── Geometry computation ──────────────────────────────────────
  // Returns one entry per level with all values needed for SVG and CSS.

  function computeGeometry(levels, cfg) {
    var geo = [];
    var yPos = 0;
    var vbW = cfg.VB_W;
    var vbH = cfg.VB_H;

    levels.forEach(function (level, i) {
      var bandH = cfg.BAND_HEIGHTS[i];
      var y0 = yPos;
      var y1 = yPos + bandH;
      yPos = y1;

      var xl0 = Math.round((vbW / 2) * (1 - y0 / vbH));
      var xr0 = Math.round((vbW / 2) * (1 + y0 / vbH));
      var xl1 = Math.round((vbW / 2) * (1 - y1 / vbH));
      var xr1 = Math.round((vbW / 2) * (1 + y1 / vbH));

      // SVG polygon points
      var pts;
      if (i === 0) {
        pts =
          Math.round(vbW / 2) + ",0 " + xr1 + "," + y1 + " " + xl1 + "," + y1;
      } else {
        pts =
          xl0 +
          "," +
          y0 +
          " " +
          xr0 +
          "," +
          y0 +
          " " +
          xr1 +
          "," +
          y1 +
          " " +
          xl1 +
          "," +
          y1;
      }

      // Clip-path: trapezoidal shape matching the exact SVG band
      var xl1p = parseFloat(((xl1 / vbW) * 100).toFixed(1));
      var xr1p = parseFloat(((xr1 / vbW) * 100).toFixed(1));
      var clipPath;
      var apexPaddingTopPct = 0;
      if (i === 0) {
        // Apex: clip below the empty triangle tip (services start below it).
        // perSvcH ≈ row_h (60 SVG units); guard against empty logika (div/0).
        var svcCount1 = levels[1] ? levels[1].services.length : 0;
        var perSvcH = svcCount1 > 0 ? cfg.BAND_HEIGHTS[1] / svcCount1 : 60;
        var apexH = bandH - level.services.length * perSvcH;
        // padding-top % is relative to container WIDTH in CSS, so convert via vbW
        apexPaddingTopPct = parseFloat(((apexH / vbW) * 100).toFixed(2));
        var xl_sv = parseFloat(
          ((((vbW / 2) * (1 - apexH / vbH)) / vbW) * 100).toFixed(1),
        );
        var xr_sv = parseFloat(
          ((((vbW / 2) * (1 + apexH / vbH)) / vbW) * 100).toFixed(1),
        );
        var apexPct = parseFloat(((apexH / bandH) * 100).toFixed(2));
        clipPath =
          "polygon(" +
          xl_sv +
          "% " +
          apexPct +
          "%," +
          xr_sv +
          "% " +
          apexPct +
          "%," +
          xr1p +
          "% 100%," +
          xl1p +
          "% 100%)";
      } else {
        var xl0p = parseFloat(((xl0 / vbW) * 100).toFixed(1));
        var xr0p = parseFloat(((xr0 / vbW) * 100).toFixed(1));
        clipPath =
          "polygon(" +
          xl0p +
          "% 0%," +
          xr0p +
          "% 0%," +
          xr1p +
          "% 100%," +
          xl1p +
          "% 100%)";
      }

      geo.push({
        points: pts,
        xl1: xl1,
        xr1: xr1,
        y1: y1,
        topPct: parseFloat(((y0 / vbH) * 100).toFixed(4)),
        hPct: parseFloat(((bandH / vbH) * 100).toFixed(4)),
        padPct: Math.max(1.5, parseFloat(((xl1 / vbW) * 100).toFixed(1))),
        clipPath: clipPath,
        apexPaddingTopPct: apexPaddingTopPct,
      });
    });

    return geo;
  }

  // ── HTML escape helper ────────────────────────────────────────

  function esc(str) {
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  // ── Build left: annotation boxes ─────────────────────────────

  function buildAnnotations(levels, geo) {
    var h = '<aside class="sap-annotations">';

    levels.forEach(function (level, i) {
      var g = geo[i];
      h +=
        '<div class="sap-annotation sap-annotation--' +
        esc(level.id) +
        '"' +
        ' style="--ann-top:' +
        g.topPct +
        "%;--ann-h:" +
        g.hPct +
        '%">';
      h += '<div class="sap-annotation__hline"></div>';
      h += '<div class="sap-annotation__label">';
      h +=
        '<span class="sap-annotation__sub">' + esc(level.sublabel) + "</span>";
      h += '<span class="sap-annotation__name">' + esc(level.label) + "</span>";
      h += "</div>";
      h += "</div>";
    });

    return h + "</aside>";
  }

  // ── Build center: SVG pyramid + content overlays ─────────────

  function buildPyramid(levels, geo, cfg) {
    var h =
      '<div class="sap-pyramid-wrap"><div class="sap-pyramid"' +
      ' style="aspect-ratio:' +
      cfg.VB_W +
      "/" +
      cfg.VB_H +
      '"' +
      ' role="list" aria-label="Piramida celostne avtomatizacije">';

    // SVG colored bands
    h +=
      '<svg class="sap-pyramid__bg" viewBox="0 0 ' +
      cfg.VB_W +
      " " +
      cfg.VB_H +
      '"' +
      ' xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">';

    levels.forEach(function (level, i) {
      h +=
        '<polygon class="sap-bg-band sap-bg-band--' +
        esc(level.id) +
        '"' +
        ' points="' +
        esc(geo[i].points) +
        '"/>';
    });

    for (var d = 0; d < levels.length - 1; d++) {
      var g = geo[d];
      h +=
        '<line class="sap-divider"' +
        ' x1="' +
        g.xl1 +
        '" y1="' +
        g.y1 +
        '"' +
        ' x2="' +
        g.xr1 +
        '" y2="' +
        g.y1 +
        '"/>';
    }

    h += "</svg>";

    // Content overlays
    levels.forEach(function (level, i) {
      var g = geo[i];
      var levelStyle =
        "--level-top:" +
        g.topPct +
        "%;--level-h:" +
        g.hPct +
        "%;--level-pad:" +
        g.padPct +
        "%;clip-path:" +
        g.clipPath +
        (g.apexPaddingTopPct
          ? ";padding-top:" + g.apexPaddingTopPct + "%"
          : "");
      h +=
        '<div class="sap-level sap-level--' +
        esc(level.id) +
        '" role="listitem"' +
        ' style="' +
        levelStyle +
        '">';
      h += '<div class="sap-level__services">';

      level.services.forEach(function (svc) {
        h +=
          '<button class="sap-service-btn" type="button"' +
          ' data-name="' +
          esc(svc.name) +
          '"' +
          ' data-description="' +
          esc(svc.description) +
          '"' +
          ' data-url="' +
          esc(svc.url) +
          '">' +
          esc(svc.name) +
          "</button>";
      });

      h += "</div></div>";
    });

    return h + "</div></div>";
  }

  // ── Build right: detail panel + level description annotations ─────

  function buildDetail(levels, geo) {
    var h = '<aside class="sap-detail" id="sap-detail">';

    // Right-side level descriptions (visible by default, hidden when card opens)
    h += '<div class="sap-right-annotations" id="sap-right-annotations">';
    levels.forEach(function (level, i) {
      var g = geo[i];
      h +=
        '<div class="sap-ann-right sap-ann-right--' +
        esc(level.id) +
        '"' +
        ' style="--ann-top:' +
        g.topPct +
        "%;--ann-h:" +
        g.hPct +
        '%">';
      h += '<div class="sap-ann-right__hline"></div>';
      h += '<div class="sap-ann-right__inner">';
      h += '<p class="sap-ann-right__desc">' + esc(level.description) + "</p>";
      h += "</div>";
      h += "</div>";
    });
    h += "</div>";

    h += '<div class="sap-detail__card" id="sap-detail-card" hidden>';
    h +=
      '<button class="sap-detail__close" id="sap-detail-close" type="button" aria-label="Zapri">&#x2715;</button>';
    h += '<h3 class="sap-detail__name" id="sap-detail-name"></h3>';
    h += '<p class="sap-detail__desc" id="sap-detail-desc"></p>';
    var learnMoreText = cfg.LEARN_MORE
      ? esc(cfg.LEARN_MORE)
      : "Izvedite ve&#269;";
    h +=
      '<a class="sap-detail__link" id="sap-detail-link" href="#">' +
      learnMoreText +
      " &rarr;</a>";
    h += "</div></aside>";
    return h;
  }

  // ── jQuery ready ──────────────────────────────────────────────

  $(document).ready(function () {
    var $wrapper = $(".sap-wrapper");
    if (!$wrapper.length) {
      return;
    }

    if (
      typeof SAP_CONFIG === "undefined" ||
      typeof SAP_LEVELS === "undefined"
    ) {
      // eslint-disable-next-line no-console
      console.error(
        "Sevate Pyramid: SAP_CONFIG or SAP_LEVELS not defined (wp_localize_script failed?).",
      );
      return;
    }

    var cfg = window.SAP_CONFIG;
    var levels = window.SAP_LEVELS;
    var geo = computeGeometry(levels, cfg);

    $wrapper.html(
      '<div class="sap-layout">' +
        buildAnnotations(levels, geo) +
        buildPyramid(levels, geo, cfg) +
        buildDetail(levels, geo) +
        "</div>",
    );

    // ── Detail panel refs (resolved after DOM injection) ──────

    var $card = $("#sap-detail-card");
    var $rightAnnotations = $("#sap-right-annotations");
    var $activeBtn = null;

    function showCard(name, desc, url) {
      $("#sap-detail-name").text(name);
      $("#sap-detail-desc").text(desc);
      $("#sap-detail-link").attr("href", url);
      $rightAnnotations.attr("hidden", "");
      $card.removeAttr("hidden");
      $("#sap-detail-close").trigger("focus");
    }

    function hideCard() {
      $card.attr("hidden", "");
      $rightAnnotations.removeAttr("hidden");
      if ($activeBtn) {
        $activeBtn.removeClass("is-active");
        $activeBtn = null;
      }
    }

    // ── Open / toggle on service button click ─────────────────
    $(document).on("click", ".sap-service-btn", function (e) {
      e.stopPropagation();
      var $btn = $(this);

      if ($btn.hasClass("is-active")) {
        hideCard();
        return;
      }

      if ($activeBtn) {
        $activeBtn.removeClass("is-active");
      }

      $activeBtn = $btn.addClass("is-active");
      showCard($btn.data("name"), $btn.data("description"), $btn.data("url"));
    });

    // ── Close via × button ────────────────────────────────────
    $(document).on("click", "#sap-detail-close", function () {
      hideCard();
    });

    // ── Close via Escape key ──────────────────────────────────
    $(document).on("keydown", function (e) {
      if (e.key === "Escape" && !$card.attr("hidden")) {
        hideCard();
      }
    });

    // ── SVG band hover sync ───────────────────────────────────
    // Mouseenter/leave on the services list brightens the matching
    // SVG polygon.  A 60 ms debounce prevents flicker when the
    // pointer moves between sibling elements in the same band.
    var hoverTimers = {};

    $(document)
      .on("mouseenter", ".sap-level__services", function () {
        var m = $(this)
          .closest(".sap-level")[0]
          .className.match(/sap-level--(\w+)/);
        if (!m) {
          return;
        }
        var id = m[1];
        clearTimeout(hoverTimers[id]);
        $(".sap-bg-band--" + id).addClass("is-hovered");
      })
      .on("mouseleave", ".sap-level__services", function () {
        var m = $(this)
          .closest(".sap-level")[0]
          .className.match(/sap-level--(\w+)/);
        if (!m) {
          return;
        }
        var id = m[1];
        hoverTimers[id] = setTimeout(function () {
          $(".sap-bg-band--" + id).removeClass("is-hovered");
        }, 60);
      });
  });
})(jQuery);
