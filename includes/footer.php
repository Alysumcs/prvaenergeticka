</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col">
      <div class="footer-logo">
        <img src="<?= e(asset('img/logo.png')) ?>" alt="Prvá Energetická"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
        <strong class="logo-text" style="display:none;">PRVÁ ENERGETICKÁ</strong>
      </div>
      <p class="footer-muted">Spoľahlivý partner v energetike od roku 2011.</p>
    </div>

    <div class="footer-col">
      <h4>Navigácia</h4>
      <a href="<?= e(url('')) ?>">Domov</a>
      <a href="<?= e(url('sluzby')) ?>">Služby</a>
      <a href="<?= e(url('o-nas')) ?>">O nás</a>
      <a href="<?= e(url('blog')) ?>">Blog</a>
      <a href="<?= e(url('faq')) ?>">FAQ</a>
      <a href="<?= e(url('kontakt')) ?>">Kontakt</a>
    </div>

    <div class="footer-col">
      <h4>Služby</h4>
      <a href="<?= e(url('plyn')) ?>">Lacnejší plyn</a>
      <a href="<?= e(url('elektrina')) ?>">Lacnejšia elektrina</a>
      <a href="<?= e(url('zasady-ochrany-osobnych-udajov')) ?>">Ochrana osobných údajov</a>
      <a href="<?= e(url('cookies')) ?>">Cookies</a>
      <a href="#" onclick="peCookies.open();return false;">Nastavenia cookies</a>
    </div>

    <div class="footer-col">
      <h4>Kontakt</h4>
      <p class="footer-muted">
        <?= e(setting('contact_company','Prvá Energetická s.r.o.')) ?><br>
        <?= e(setting('contact_address','Hattalova 12A, 831 03 Bratislava')) ?><br>
        IČO: <?= e(setting('contact_ico','46 426 639')) ?><br>
        DIČ: <?= e(setting('contact_dic','2023402326')) ?><br>
        <a href="mailto:<?= e(setting('contact_email','info@prvaenergeticka.sk')) ?>"><?= e(setting('contact_email','info@prvaenergeticka.sk')) ?></a>
      </p>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      © <?= date('Y') ?> Prvá Energetická s.r.o. — Všetky práva vyhradené.
    </div>
  </div>
</footer>

<!-- GDPR cookie lišta -->
<div id="cookie-banner" class="cookie-banner" hidden>
  <div class="cookie-inner">
    <div class="cookie-text">
      <strong>Táto stránka používa cookies</strong>
      <p>Používame nevyhnutné cookies pre fungovanie webu a, s vaším súhlasom, aj cookies na štatistiky a marketing. Viac v <a href="<?= e(url('cookies')) ?>">zásadách cookies</a>.</p>
    </div>
    <div class="cookie-actions">
      <button class="btn btn-ghost" onclick="peCookies.reject()">Odmietnuť</button>
      <button class="btn btn-ghost" onclick="peCookies.open()">Nastavenia</button>
      <button class="btn btn-accent" onclick="peCookies.acceptAll()">Prijať všetko</button>
    </div>
  </div>
</div>

<!-- GDPR nastavenia (modal) -->
<div id="cookie-modal" class="cookie-modal" hidden>
  <div class="cookie-modal-box">
    <h3>Nastavenia súborov cookies</h3>
    <p class="footer-muted">Vyberte, ktoré kategórie cookies povolíte. Nevyhnutné cookies sú vždy aktívne.</p>
    <label class="cookie-row"><span><strong>Nevyhnutné</strong><br><small>Potrebné pre základné fungovanie webu.</small></span><input type="checkbox" checked disabled></label>
    <label class="cookie-row"><span><strong>Predvoľby</strong><br><small>Ukladajú vaše nastavenia.</small></span><input type="checkbox" id="c-pref"></label>
    <label class="cookie-row"><span><strong>Štatistiky</strong><br><small>Anonymné meranie návštevnosti.</small></span><input type="checkbox" id="c-stat"></label>
    <label class="cookie-row"><span><strong>Marketing</strong><br><small>Personalizovaná reklama.</small></span><input type="checkbox" id="c-mark"></label>
    <div class="cookie-modal-actions">
      <button class="btn btn-ghost" onclick="peCookies.close()">Zavrieť</button>
      <button class="btn btn-accent" onclick="peCookies.saveModal()">Uložiť voľby</button>
    </div>
  </div>
</div>

<script>
/* ---- GDPR súhlas (cookie pe_consent, platnosť 12 mesiacov) ---- */
window.peCookies = (function(){
  var KEY='pe_consent';
  function read(){ try{ var m=document.cookie.match(/(?:^|; )pe_consent=([^;]+)/); return m?JSON.parse(decodeURIComponent(m[1])):null; }catch(e){return null;} }
  function write(o){ o.ts=Date.now(); var d=new Date(); d.setFullYear(d.getFullYear()+1);
    document.cookie=KEY+'='+encodeURIComponent(JSON.stringify(o))+';expires='+d.toUTCString()+';path=/;SameSite=Lax'; }
  function banner(s){ var b=document.getElementById('cookie-banner'); if(b) b.hidden=s? true:false; }
  function modal(s){ var m=document.getElementById('cookie-modal'); if(m) m.hidden=!s; }
  function apply(o){ window.peConsent=o; document.dispatchEvent(new CustomEvent('pe:consent',{detail:o})); }
  var api={
    acceptAll:function(){ var o={necessary:true,preferences:true,statistics:true,marketing:true}; write(o); banner(true); modal(false); apply(o); },
    reject:function(){ var o={necessary:true,preferences:false,statistics:false,marketing:false}; write(o); banner(true); modal(false); apply(o); },
    open:function(){ var c=read()||{}; var p=document.getElementById('c-pref'),s=document.getElementById('c-stat'),m=document.getElementById('c-mark');
      if(p)p.checked=!!c.preferences; if(s)s.checked=!!c.statistics; if(m)m.checked=!!c.marketing; modal(true); },
    close:function(){ modal(false); },
    saveModal:function(){ var o={necessary:true,
        preferences:document.getElementById('c-pref').checked,
        statistics:document.getElementById('c-stat').checked,
        marketing:document.getElementById('c-mark').checked};
      write(o); banner(true); modal(false); apply(o); }
  };
  document.addEventListener('DOMContentLoaded',function(){
    var c=read(); if(!c){ banner(false); } else { apply(c); }
  });
  return api;
})();
</script>

<!-- GSAP + ScrollTrigger + Lenis -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lenis@1.1.13/dist/lenis.min.js"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
