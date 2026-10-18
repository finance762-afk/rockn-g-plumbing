/* ============================================
   Page One Insights — Main JavaScript
   Auto-generated from build-plan.json
   ============================================ */

document.documentElement.classList.add('js-anim'); /* v7: reveals hide ONLY once JS runs (fail-open) */

document.addEventListener('DOMContentLoaded', function() {

  /* === v7 reveals: .reveal / .reveal-* / [data-animate] — IntersectionObserver + safety net === */
  (function () {
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var items = document.querySelectorAll('.reveal, .reveal-up, .reveal-down, .reveal-left, .reveal-right, .reveal-scale');
    var show = function (el) { el.classList.add('in'); };
    if (items.length && 'IntersectionObserver' in window && !reduce) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) { show(e.target); io.unobserve(e.target); } });
      }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
      items.forEach(function (el) { io.observe(el); });
      setTimeout(function () { items.forEach(show); }, 2500);
    } else { items.forEach(show); }
  })();

  /* === v7 estimate dialog: any [data-open-estimate] opens #estimate-dialog === */
  (function () {
    var dlg = document.getElementById('estimate-dialog');
    if (!dlg || typeof dlg.showModal !== 'function') return;
    document.querySelectorAll('[data-open-estimate]').forEach(function (btn) {
      btn.addEventListener('click', function (ev) { ev.preventDefault(); dlg.showModal(); var f = dlg.querySelector('input:not([type=hidden])'); if (f) setTimeout(function () { f.focus(); }, 50); });
    });
    dlg.querySelectorAll('[data-close-estimate]').forEach(function (b) { b.addEventListener('click', function () { dlg.close(); }); });
    dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });
  })();

  /* === v7 cookie bar: one line, after the first scroll, remembered === */
  (function () {
    var bar = document.getElementById('cookie-bar') || document.getElementById('cookie-banner');
    if (!bar) return;
    var key = 'cookieBannerDismissed_v1', seen = false;
    try { seen = localStorage.getItem(key) === 'true'; } catch (e) {}
    if (seen) { bar.remove(); return; }
    var reveal = function () { bar.classList.add('is-visible'); window.removeEventListener('scroll', reveal); };
    window.addEventListener('scroll', reveal, { passive: true });
    setTimeout(reveal, 8000);
    var btn = bar.querySelector('button');
    if (btn) btn.addEventListener('click', function () { bar.classList.remove('is-visible'); try { localStorage.setItem(key, 'true'); } catch (e) {} setTimeout(function () { bar.remove(); }, 400); });
  })();

  /* === v7 mobile sticky bar: appears once the hero has scrolled away === */
  (function () {
    var sticky = document.querySelector('.mobile-cta, .mobile-cta-bar');
    var hero = document.querySelector('.hero, .hero-v7');
    if (!sticky) return;
    if (hero && 'IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) { sticky.classList.toggle('is-visible', !entries[0].isIntersecting); }, { threshold: 0.05 }).observe(hero);
    } else { sticky.classList.add('is-visible'); }
  })();

  /* === Sticky Header / Scroll Class Toggle === */
  const header = document.querySelector('.site-header');
  if (header) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 60) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
  }

  /* === Mobile Hamburger Nav Toggle ===
     Phase 2 builds a .mobile-menu overlay (spec) — older scaffolds used .nav-links.
     Target whichever exists (salt-river-steel shipped with a dead menu, 2026-08-29). */
  const hamburger = document.querySelector('.hamburger');
  const navLinks = document.querySelector('.mobile-menu') || document.querySelector('.nav-links');
  if (hamburger && navLinks) {
    const setOpen = function(open) {
      navLinks.classList.toggle('active', open);
      hamburger.classList.toggle('active', open);
      hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
      navLinks.setAttribute('aria-hidden', open ? 'false' : 'true');
      document.body.classList.toggle('menu-open', open);
      document.body.style.overflow = open ? 'hidden' : '';
    };
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && navLinks.classList.contains('active')) setOpen(false); });
    hamburger.addEventListener('click', function() {
      const isOpen = !navLinks.classList.contains('active');
      setOpen(isOpen);
    });
    // Close nav when clicking a link
    navLinks.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        navLinks.classList.remove('active');
        hamburger.classList.remove('active');
        hamburger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });
  }

  /* === Smooth Scroll for Anchor Links === */
  document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      var targetId = this.getAttribute('href').substring(1);
      var target = document.getElementById(targetId);
      if (target) {
        var headerHeight = header ? header.offsetHeight : 0;
        var top = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  /* === IntersectionObserver for data-animate fade-in === */
  var animateElements = document.querySelectorAll('[data-animate]');
  if (animateElements.length > 0 && 'IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    animateElements.forEach(function(el) { observer.observe(el); });
  }

  /* === Counter Animation for data-counter elements === */
  var counters = document.querySelectorAll('[data-counter]');
  if (counters.length > 0 && 'IntersectionObserver' in window) {
    var counterObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var el = entry.target;
          var target = parseInt(el.getAttribute('data-counter'), 10);
          var suffix = el.getAttribute('data-suffix') || '';
          var prefix = el.getAttribute('data-prefix') || '';
          var duration = 2000;
          var start = 0;
          var startTime = null;

          function animate(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
            var current = Math.floor(eased * target);
            el.textContent = prefix + current.toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(animate);
            else el.textContent = prefix + target.toLocaleString() + suffix;
          }
          requestAnimationFrame(animate);
          counterObserver.unobserve(el);
        }
      });
    }, { threshold: 0.3 });
    counters.forEach(function(el) { counterObserver.observe(el); });
  }

  /* === Back to Top Button === */
  var backToTop = document.querySelector('.back-to-top');
  if (backToTop) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 600) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    });
    backToTop.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* === Reviews carousel === */
  // v6.2: carousels are CSS scroll-snap by default (no Swiper CDN). If a build
  // genuinely needs Swiper features, load the CDN in that page's head and this
  // guard will initialize it; otherwise it's a harmless no-op.
  if (typeof Swiper !== 'undefined') {
    var reviewsSwiper = document.querySelector('.reviews-swiper');
    if (reviewsSwiper) {
      new Swiper('.reviews-swiper', {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        pagination: { el: '.swiper-pagination', clickable: true },
        breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
      });
    }
  }

});
