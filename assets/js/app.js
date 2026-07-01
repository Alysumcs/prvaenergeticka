/* ===== Prvá Energetická – interakcie a GSAP animácie ===== */
(function () {
  'use strict';

  /* ---------- Prepínač tmavý/svetlý režim ---------- */
  var themeBtn = document.getElementById('theme-toggle');
  if (themeBtn) {
    themeBtn.addEventListener('click', function () {
      var isLight = document.documentElement.getAttribute('data-theme') === 'light';
      if (isLight) {
        document.documentElement.removeAttribute('data-theme');
        try { localStorage.setItem('pe-theme', 'dark'); } catch (e) {}
      } else {
        document.documentElement.setAttribute('data-theme', 'light');
        try { localStorage.setItem('pe-theme', 'light'); } catch (e) {}
      }
      document.dispatchEvent(new CustomEvent('pe:theme'));
    });
  }

  /* ---------- Logo: zobraz text len ak chýba obrázok pre aktuálnu tému ---------- */
  document.querySelectorAll('.logo').forEach(function (logo) {
    var txt = logo.querySelector('.logo-text');
    function check() {
      var light = document.documentElement.getAttribute('data-theme') === 'light';
      var img = logo.querySelector(light ? '.logo-on-light' : '.logo-on-dark');
      var ok = img && img.complete && img.naturalWidth > 0;
      if (txt) { txt.style.display = ok ? 'none' : 'inline'; }
    }
    logo.querySelectorAll('img').forEach(function (im) {
      im.addEventListener('load', check);
      im.addEventListener('error', check);
    });
    document.addEventListener('pe:theme', check);
    check();
  });

  /* ---------- Plávajúca priehľadná hlavička nad hero (domov) ---------- */
  (function () {
    var hdr = document.querySelector('.site-header');
    if (hdr && document.body.getAttribute('data-page') === 'home') {
      hdr.classList.add('hdr-float');
      var onScr = function () { hdr.classList.toggle('hdr-solid', window.scrollY > 40); };
      onScr();
      window.addEventListener('scroll', onScr, { passive: true });
    }
  })();

  /* ---------- Mobilné menu ---------- */
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.querySelector('.main-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      toggle.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    nav.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        nav.classList.remove('open');
        toggle.classList.remove('open');
      });
    });
  }

  /* ---------- FAQ accordion ---------- */
  document.querySelectorAll('.faq-q').forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.closest('.faq-item');
      var ans = item.querySelector('.faq-a');
      var isOpen = item.classList.toggle('open');
      ans.style.maxHeight = isOpen ? (ans.scrollHeight + 'px') : '0';
    });
  });

  /* ---------- Plynulé skoky na kotvy (natívne) ---------- */
  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href^="#"]');
    if (!a) return;
    var id = a.getAttribute('href');
    if (!id || id === '#') return;
    var target = document.querySelector(id);
    if (!target) return;
    e.preventDefault();
    var y = target.getBoundingClientRect().top + window.pageYOffset - 90;
    window.scrollTo({ top: y, behavior: 'smooth' });
  });

  /* ---------- GSAP ---------- */
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (typeof window.gsap === 'undefined' || reduce) {
    // Bez GSAP (alebo redukovaný pohyb) – zobraz všetok obsah natvrdo.
    document.documentElement.classList.remove('pe-anim-on');
    return;
  }
  var gsap = window.gsap;
  if (window.ScrollTrigger) { gsap.registerPlugin(window.ScrollTrigger); }
  window.__peReady = true;
  document.documentElement.classList.add('pe-anim-on');

  /* ---------- HERO intro ---------- */
  var heroReveals = [];
  try {
    var hero = document.querySelector('.hero');
    if (hero) {
      heroReveals = Array.prototype.slice.call(hero.querySelectorAll('.reveal'));

      var cv = hero.querySelector('.energy-canvas');
      if (cv) { initEnergyField(cv); }

      var h1 = hero.querySelector('h1');
      var lines = [];
      if (h1 && !h1.dataset.split) {
        if (h1.querySelector('.ln')) {
          h1.dataset.split = '1'; // už predrozdelené v HTML (s rotátorom)
        } else {
          var spanEl = h1.querySelector('span');
          var accentText = spanEl ? spanEl.textContent : '';
          var mainText = (h1.textContent || '').replace(accentText, '').trim();
          var html = '<span class="ln"><span class="ln-i">' + mainText + '</span></span>';
          if (accentText) { html += '<span class="ln"><span class="ln-i ln-accent">' + accentText + '</span></span>'; }
          h1.innerHTML = html;
          h1.dataset.split = '1';
        }
      }
      if (h1) { lines = Array.prototype.slice.call(h1.querySelectorAll('.ln-i')); }

      var rot = hero.querySelector('.rotator');
      if (rot) { initRotator(rot); }

      var kicker = hero.querySelector('.kicker');
      var hsub   = hero.querySelector('.hero-sub');
      var hcta   = hero.querySelector('.hero-cta');
      var hstats = hero.querySelector('.hero-stats');
      var hintEl = hero.querySelector('.scroll-hint');

      gsap.set(heroReveals, { opacity: 1, y: 0 });
      if (h1) gsap.set(h1, { opacity: 1 });
      if (kicker) gsap.set(kicker, { opacity: 0, y: 18 });
      if (lines.length) gsap.set(lines, { yPercent: 120 });
      if (hsub) gsap.set(hsub, { opacity: 0, y: 24 });
      if (hcta) gsap.set(hcta, { opacity: 0, y: 24 });
      if (hstats) gsap.set(hstats, { opacity: 0, y: 24 });
      if (hintEl) gsap.set(hintEl, { opacity: 0 });

      var tl = gsap.timeline({ delay: 0.15 });
      if (kicker) tl.to(kicker, { opacity: 1, y: 0, duration: 0.55, ease: 'power3.out' });
      if (lines.length) tl.to(lines, { yPercent: 0, duration: 1.1, ease: 'power4.out', stagger: 0.13 }, '-=0.2');
      if (hsub) tl.to(hsub, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }, '-=0.6');
      if (hcta) tl.to(hcta, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }, '-=0.45');
      if (hstats) tl.to(hstats, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out',
        onStart: function () { hero.querySelectorAll('.hero-stats [data-count]').forEach(animateCount); } }, '-=0.35');
      if (hintEl) tl.to(hintEl, { opacity: 1, duration: 0.6 }, '-=0.1');
    }
  } catch (err) {
    if (heroReveals.length) { gsap.set(heroReveals, { opacity: 1, y: 0, clearProps: 'transform' }); }
  }

  /* ---------- Hero zoom pri scrollovaní (prechod do ďalšej sekcie) ---------- */
  try {
    var heroEl = document.querySelector('.hero');
    var desktop = window.matchMedia && window.matchMedia('(min-width: 760px)').matches;
    if (heroEl && window.ScrollTrigger && desktop) {
      var inner = heroEl.querySelector('.hero-inner');
      var cnv = heroEl.querySelector('.energy-canvas');
      var shint = heroEl.querySelector('.scroll-hint');
      gsap.set([inner, cnv], { transformOrigin: '50% 50%' });
      var ztl = gsap.timeline({ scrollTrigger: {
        trigger: heroEl, start: 'top top', end: '+=115%', pin: true, scrub: 0.6, anticipatePin: 1
      } });
      if (inner) ztl.to(inner, { scale: 1.7, opacity: 0, ease: 'power1.in' }, 0);
      if (cnv) ztl.to(cnv, { scale: 2.0, opacity: 0, ease: 'power1.in' }, 0);
      if (shint) ztl.to(shint, { opacity: 0, ease: 'power1.in' }, 0);
    }
  } catch (e) {}

  /* rotujúci text (slide zľava doprava) */
  function initRotator(el) {
    var words = (el.getAttribute('data-words') || '').split('|').filter(Boolean);
    if (words.length < 2) return;
    var i = 0;
    el.textContent = words[0];
    setInterval(function () {
      i = (i + 1) % words.length;
      gsap.to(el, { xPercent: 65, opacity: 0, duration: 0.45, ease: 'power2.in',
        onComplete: function () {
          el.textContent = words[i];
          gsap.fromTo(el, { xPercent: -65, opacity: 0 }, { xPercent: 0, opacity: 1, duration: 0.55, ease: 'power3.out' });
        } });
    }, 2600);
  }

  /* počítadlo */
  function animateCount(el) {
    var target = parseFloat(el.getAttribute('data-count')) || 0;
    var suffix = el.getAttribute('data-suffix') || '';
    var o = { v: 0 };
    gsap.to(o, { v: target, duration: 1.6, ease: 'power2.out',
      onUpdate: function () { el.textContent = Math.round(o.v) + suffix; } });
  }

  /* energetické pozadie: elektrické oblúky + stúpajúce iskry (plyn), reaguje na myš */
  function initEnergyField(canvas) {
    var ctx = canvas.getContext('2d');
    var w = 0, h = 0, dpr = 1, t = 0;
    var mouse = { x: -9999, y: -9999, active: false };

    function move(e) {
      var r = canvas.getBoundingClientRect();
      var cx = e.touches ? e.touches[0].clientX : e.clientX;
      var cy = e.touches ? e.touches[0].clientY : e.clientY;
      mouse.x = cx - r.left; mouse.y = cy - r.top; mouse.active = true;
    }
    window.addEventListener('mousemove', move, { passive: true });
    window.addEventListener('touchmove', move, { passive: true });
    window.addEventListener('mouseout', function () { mouse.active = false; mouse.x = -9999; mouse.y = -9999; });

    var nodes = [], embers = [];
    function newEmber(rand) {
      return { x: Math.random() * w, y: rand ? Math.random() * h : h + 8,
        r: 0.8 + Math.random() * 2.2, spd: 0.3 + Math.random() * 0.9,
        sway: 0.5 + Math.random() * 1.3, ph: Math.random() * 6.28, a: 0.3 + Math.random() * 0.5 };
    }
    function build() {
      var N = Math.max(24, Math.min(60, Math.round(w * h / 24000)));
      nodes = [];
      for (var i = 0; i < N; i++) {
        nodes.push({ x: Math.random() * w, y: Math.random() * h,
          vx: (Math.random() - 0.5) * 0.3, vy: (Math.random() - 0.5) * 0.3, r: 1 + Math.random() * 1.6 });
      }
      var E = Math.max(16, Math.min(48, Math.round(w / 26)));
      embers = [];
      for (var k = 0; k < E; k++) embers.push(newEmber(true));
    }
    function resize() {
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = canvas.clientWidth; h = canvas.clientHeight;
      canvas.width = Math.max(1, w * dpr); canvas.height = Math.max(1, h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      build();
    }
    resize();
    window.addEventListener('resize', resize);

    /* kľukatý elektrický oblúk */
    function arc(x1, y1, x2, y2, amp) {
      var seg = 5; ctx.beginPath(); ctx.moveTo(x1, y1);
      for (var s = 1; s < seg; s++) {
        var tt = s / seg, x = x1 + (x2 - x1) * tt, y = y1 + (y2 - y1) * tt;
        var nx = -(y2 - y1), ny = (x2 - x1), L = Math.sqrt(nx * nx + ny * ny) || 1;
        var jj = (Math.random() - 0.5) * amp;
        ctx.lineTo(x + nx / L * jj, y + ny / L * jj);
      }
      ctx.lineTo(x2, y2); ctx.stroke();
    }

    var LINK = 140, MR = 200;
    function frame() {
      t += 0.016;
      ctx.clearRect(0, 0, w, h);

      /* ELEKTRINA – uzly + oblúky */
      for (var i = 0; i < nodes.length; i++) {
        var p = nodes[i];
        if (mouse.active) {
          var dx = p.x - mouse.x, dy = p.y - mouse.y, d = Math.sqrt(dx * dx + dy * dy);
          if (d < 130 && d > 0.01) { var f = (130 - d) / 130 * 1.5; p.x += dx / d * f; p.y += dy / d * f; }
        }
        p.x += p.vx; p.y += p.vy;
        if (p.x < 0 || p.x > w) p.vx *= -1;
        if (p.y < 0 || p.y > h) p.vy *= -1;
        p.x = Math.max(0, Math.min(w, p.x)); p.y = Math.max(0, Math.min(h, p.y));
      }
      ctx.lineWidth = 1;
      for (i = 0; i < nodes.length; i++) {
        for (var j = i + 1; j < nodes.length; j++) {
          var a = nodes[i], b = nodes[j], ax = a.x - b.x, ay = a.y - b.y, dist = Math.sqrt(ax * ax + ay * ay);
          if (dist < LINK) { ctx.globalAlpha = (1 - dist / LINK) * 0.32; ctx.strokeStyle = '#d12911'; arc(a.x, a.y, b.x, b.y, 5); }
        }
        if (mouse.active) {
          var mx = nodes[i].x - mouse.x, my = nodes[i].y - mouse.y, mdd = Math.sqrt(mx * mx + my * my);
          if (mdd < MR) { ctx.globalAlpha = (1 - mdd / MR) * 0.85; ctx.strokeStyle = '#ff3b2b'; ctx.lineWidth = 1.3; arc(nodes[i].x, nodes[i].y, mouse.x, mouse.y, 10); ctx.lineWidth = 1; }
        }
      }
      ctx.globalAlpha = 1;
      for (i = 0; i < nodes.length; i++) {
        ctx.beginPath(); ctx.arc(nodes[i].x, nodes[i].y, nodes[i].r, 0, 6.2832);
        ctx.fillStyle = '#ff4a3a'; ctx.fill();
      }
      if (mouse.active) {
        ctx.beginPath(); ctx.arc(mouse.x, mouse.y, 3.5, 0, 6.2832); ctx.fillStyle = '#ff3322'; ctx.fill();
      }

      /* PLYN – stúpajúce iskry/plamienky */
      for (var e = 0; e < embers.length; e++) {
        var em = embers[e];
        em.y -= em.spd; em.x += Math.sin(t * em.sway + em.ph) * 0.4;
        if (em.y < -10) { embers[e] = newEmber(false); continue; }
        var flick = 0.6 + 0.4 * Math.sin(t * 6 + em.ph);
        ctx.globalAlpha = em.a * flick * Math.max(0, Math.min(1, em.y / h));
        ctx.beginPath(); ctx.arc(em.x, em.y, em.r, 0, 6.2832);
        ctx.fillStyle = '#ff4a3a'; ctx.fill();
      }
      ctx.globalAlpha = 1;
      requestAnimationFrame(frame);
    }
    frame();
  }

  /* ---------- Reveal + stagger (zvyšok stránky) ---------- */
  try {
    var els = gsap.utils.toArray('.reveal').filter(function (el) {
      return heroReveals.indexOf(el) === -1;
    });
    if (window.ScrollTrigger && els.length) {
      ScrollTrigger.batch(els, {
        start: 'top 90%',
        once: true,
        onEnter: function (batch) {
          gsap.to(batch, { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out', stagger: 0.1, overwrite: true });
        }
      });
      // Poistka: čokoľvek, čo je už vo viewporte a nezachytilo sa, zobraz.
      ScrollTrigger.refresh();
    } else {
      gsap.set(els, { opacity: 1, y: 0 });
    }
  } catch (err2) {
    document.documentElement.classList.remove('pe-anim-on');
  }

  /* ---------- Jemný parallax na obrázkoch ---------- */
  try {
    if (window.ScrollTrigger) {
      gsap.utils.toArray('.parallax').forEach(function (el) {
        gsap.fromTo(el, { yPercent: -5 }, {
          yPercent: 5, ease: 'none',
          scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true }
        });
      });
    }
  } catch (e) {}

  /* ---------- Progress bar ---------- */
  try {
    var bar = document.getElementById('pe-progress');
    if (bar && window.ScrollTrigger) {
      gsap.to(bar, { scaleX: 1, ease: 'none', scrollTrigger: { start: 0, end: 'max', scrub: 0.3 } });
    }
  } catch (e) {}

  window.addEventListener('load', function () {
    if (window.ScrollTrigger) { ScrollTrigger.refresh(); }
  });
})();
