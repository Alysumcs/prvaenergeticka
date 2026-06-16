/* ===== Prvá Energetická – interakcie a GSAP animácie ===== */
(function () {
  'use strict';

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

  /* ---------- GSAP ---------- */
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (typeof gsap === 'undefined' || reduce) {
    document.documentElement.classList.remove('pe-anim-on');
    return;
  }
  gsap.registerPlugin(ScrollTrigger);
  window.__peReady = true;

  /* Smooth scroll (Lenis) */
  var lenis = null;
  if (typeof Lenis !== 'undefined') {
    lenis = new Lenis({ duration: 1.1, smoothWheel: true,
      easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); } });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
    gsap.ticker.lagSmoothing(0);
  }

  /* Plynulé skoky na kotvy v rámci stránky */
  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href^="#"]');
    if (!a) return;
    var hash = a.getAttribute('href');
    if (!hash || hash === '#') return;
    var target = document.querySelector(hash);
    if (!target) return;
    e.preventDefault();
    if (lenis) { lenis.scrollTo(target, { offset: -90, duration: 1.2 }); }
    else { window.scrollTo({ top: target.getBoundingClientRect().top + window.pageYOffset - 90, behavior: 'smooth' }); }
  });

  /* Reveal + stagger */
  var els = gsap.utils.toArray('.reveal');
  ScrollTrigger.batch(els, {
    start: 'top 88%',
    once: true,
    onEnter: function (batch) {
      gsap.to(batch, {
        opacity: 1, y: 0, duration: 0.85, ease: 'power3.out',
        stagger: 0.12, overwrite: true
      });
    }
  });

  /* Jemný parallax */
  gsap.utils.toArray('.parallax').forEach(function (el) {
    gsap.fromTo(el, { yPercent: -6 }, {
      yPercent: 6, ease: 'none',
      scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true }
    });
  });

  /* Pinned (opt-in: .pe-pin) */
  document.querySelectorAll('.pe-pin').forEach(function (el) {
    ScrollTrigger.create({ trigger: el, start: 'top top',
      end: '+=' + (el.offsetHeight || window.innerHeight), pin: true, pinSpacing: true });
  });

  /* Progress bar */
  var bar = document.getElementById('pe-progress');
  if (bar) {
    gsap.to(bar, { scaleX: 1, ease: 'none',
      scrollTrigger: { start: 0, end: 'max', scrub: 0.3 } });
  }

  window.addEventListener('load', function () { ScrollTrigger.refresh(); });
})();
