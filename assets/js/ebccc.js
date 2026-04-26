/**
 * EBCCC Theme — ebccc.js
 * Handles: mobile drawer, FAQ accordion, tour form AJAX submission,
 * back-to-top, scroll-based active nav state, header shadow.
 *
 * No external dependencies. Deferred load.
 *
 * @package EBCCC
 */

'use strict';

/* ── Mobile Drawer ──────────────────────────────────────── */
(function initDrawer() {
  const hamburger  = document.getElementById('hamburger');
  const drawer     = document.getElementById('mobile-drawer');
  const overlay    = document.getElementById('drawer-overlay');
  const closeBtn   = document.getElementById('drawer-close');
  const drawerNav  = drawer?.querySelector('.drawer-nav');
  const drawerLinks = drawer?.querySelectorAll('.drawer-link, .btn-cta-full, .btn-ghost-full');

  if (!hamburger || !drawer) return;

  const getFocusable = () =>
    Array.from(drawerNav.querySelectorAll('button,a,input,select,textarea,[tabindex]:not([tabindex="-1"])'))
         .filter(el => !el.disabled && el.offsetParent !== null);

  function openDrawer() {
    drawer.classList.add('is-open');
    drawer.removeAttribute('aria-hidden');
    hamburger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    getFocusable()[0]?.focus();
  }

  function closeDrawer() {
    drawer.classList.remove('is-open');
    drawer.setAttribute('aria-hidden', 'true');
    hamburger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    hamburger.focus();
  }

  hamburger.addEventListener('click', openDrawer);
  closeBtn?.addEventListener('click', closeDrawer);
  overlay?.addEventListener('click', closeDrawer);
  drawerLinks?.forEach(l => l.addEventListener('click', closeDrawer));

  drawerNav?.addEventListener('keydown', e => {
    if (!drawer.classList.contains('is-open')) return;
    if (e.key === 'Escape') { closeDrawer(); return; }
    if (e.key === 'Tab') {
      const f = getFocusable();
      const first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });
})();


/* ── FAQ Accordion ──────────────────────────────────────── */
(function initFAQ() {
  document.querySelectorAll('.faq-trigger').forEach(trigger => {
    const panelId = trigger.getAttribute('aria-controls');
    const panel   = document.getElementById(panelId);
    if (!panel) return;

    trigger.addEventListener('click', () => {
      const isOpen = trigger.getAttribute('aria-expanded') === 'true';
      // Close siblings in same list
      trigger.closest('.faq-list')?.querySelectorAll('.faq-trigger').forEach(t => {
        if (t !== trigger) {
          t.setAttribute('aria-expanded', 'false');
          const p = document.getElementById(t.getAttribute('aria-controls'));
          if (p) p.hidden = true;
        }
      });
      trigger.setAttribute('aria-expanded', String(!isOpen));
      panel.hidden = isOpen;
    });

    // Arrow key navigation between items
    trigger.addEventListener('keydown', e => {
      const all = Array.from(trigger.closest('.faq-list')?.querySelectorAll('.faq-trigger') || []);
      const idx = all.indexOf(trigger);
      if (e.key === 'ArrowDown') { e.preventDefault(); all[(idx + 1) % all.length]?.focus(); }
      if (e.key === 'ArrowUp')   { e.preventDefault(); all[(idx - 1 + all.length) % all.length]?.focus(); }
    });
  });
})();


/* ── Tour Form (AJAX) ───────────────────────────────────── */
(function initForm() {
  const form    = document.getElementById('tour-form');
  const success = document.getElementById('form-success');
  if (!form) return;

  // Check if this is a CF7 form (WP has taken over) — if so, bail
  if (form.classList.contains('wpcf7-form')) return;

  const validators = {
    name:  v => v.trim().length >= 2 ? null : 'Please enter your full name.',
    phone: v => /^(\+61|0)[2-9]\d{8}$/.test(v.replace(/[\s\-().]/g, '')) ? null : 'Please enter a valid Australian phone number.',
    email: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()) ? null : 'Please enter a valid email address.',
  };

  function validateField(name) {
    const field   = form.querySelector(`[name="${name}"]`);
    const errorEl = document.getElementById(`field-${name}-error`);
    if (!field || !validators[name]) return true;
    const error = validators[name](field.value);
    field.classList.toggle('is-error', !!error);
    if (error) field.setAttribute('aria-describedby', `field-${name}-error`);
    else        field.removeAttribute('aria-describedby');
    if (errorEl) errorEl.textContent = error || '';
    return !error;
  }

  ['name', 'phone', 'email'].forEach(name => {
    const f = form.querySelector(`[name="${name}"]`);
    f?.addEventListener('blur', () => validateField(name));
    f?.addEventListener('input', () => { if (f.classList.contains('is-error')) validateField(name); });
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const valid = ['name', 'phone', 'email'].map(validateField).every(Boolean);
    if (!valid) { form.querySelector('.is-error')?.focus(); return; }

    const btn = form.querySelector('.btn-submit');
    const origText = btn.textContent;
    btn.textContent = 'Sending…';
    btn.disabled = true;

    try {
      const body = new FormData(form);
      // ebcccData is localised from wp_localize_script in functions.php
      const ajaxUrl = (typeof ebcccData !== 'undefined') ? ebcccData.ajaxUrl : '/wp-admin/admin-ajax.php';

      const resp = await fetch(ajaxUrl, { method: 'POST', body });
      const data = await resp.json();

      if (data.success) {
        form.hidden = true;
        if (success) { success.hidden = false; success.focus(); }
      } else {
        const msgs = data.data?.errors || ['There was a problem — please try again or call us directly.'];
        // Surface first error near the submit button
        let errEl = form.querySelector('.form-ajax-error');
        if (!errEl) {
          errEl = document.createElement('p');
          errEl.className = 'form-ajax-error';
          errEl.setAttribute('role', 'alert');
          errEl.style.cssText = 'color:#c0392b;font-size:var(--fs-sm);font-weight:600;margin-bottom:var(--space-md);';
          form.insertBefore(errEl, btn);
        }
        errEl.textContent = msgs[0];
        btn.textContent = origText;
        btn.disabled = false;
      }
    } catch {
      btn.textContent = origText;
      btn.disabled = false;
    }
  });
})();


/* ── Scroll-based active nav (front page only) ─────────── */
(function initScrollNav() {
  if (typeof ebcccData === 'undefined' || ebcccData.isFront !== 'true') return;

  const sections = document.querySelectorAll('section[id], div[id="book-tour"]');
  const navLinks = document.querySelectorAll('.nav-link');
  if (!sections.length || !navLinks.length) return;

  const sectionMap = {};
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href?.startsWith('#')) sectionMap[href.slice(1)] = link;
  });

  function updateActive() {
    let currentId = null;
    const scrollY = window.scrollY + 120;
    sections.forEach(s => { if (scrollY >= s.offsetTop) currentId = s.id; });
    navLinks.forEach(l => l.removeAttribute('aria-current'));
    if (currentId && sectionMap[currentId]) sectionMap[currentId].setAttribute('aria-current', 'page');
  }

  window.addEventListener('scroll', updateActive, { passive: true });
  updateActive();
})();


/* ── Header scroll shadow ───────────────────────────────── */
(function initHeaderScroll() {
  const header = document.getElementById('site-header');
  if (!header) return;
  window.addEventListener('scroll', () => {
    header.style.boxShadow = window.scrollY > 10 ? '0 2px 16px rgba(0,0,0,0.10)' : '';
  }, { passive: true });
})();


/* ── Back to Top ────────────────────────────────────────── */
(function initBackToTop() {
  const btn = document.getElementById('back-to-top');
  if (!btn) return;
  window.addEventListener('scroll', () => { btn.hidden = window.scrollY <= 500; }, { passive: true });
  btn.addEventListener('click', () => { window.scrollTo({ top: 0, behavior: 'smooth' }); });
})();


/* ── Photo Carousel ─────────────────────────────────────── */
(function initCarousel() {
  const track = document.getElementById('photo-carousel-track');
  const prevBtn = document.getElementById('carousel-prev');
  const nextBtn = document.getElementById('carousel-next');
  const dotsContainer = document.getElementById('carousel-dots');
  if (!track || !prevBtn || !nextBtn) return;

  const items = Array.from(track.querySelectorAll('.photo-strip-item'));
  if (!items.length) return;

  // Build dots — one per item
  items.forEach((_, i) => {
    const dot = document.createElement('button');
    dot.className = 'carousel-dot' + (i === 0 ? ' is-active' : '');
    dot.setAttribute('role', 'tab');
    dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
    dot.addEventListener('click', () => scrollTo(i));
    dotsContainer?.appendChild(dot);
  });

  function getDots() { return Array.from(dotsContainer?.querySelectorAll('.carousel-dot') || []); }

  function getActiveIndex() {
    const trackLeft = track.getBoundingClientRect().left;
    let closest = 0, minDist = Infinity;
    items.forEach((item, i) => {
      const dist = Math.abs(item.getBoundingClientRect().left - trackLeft);
      if (dist < minDist) { minDist = dist; closest = i; }
    });
    return closest;
  }

  function scrollTo(index) {
    const item = items[index];
    if (!item) return;
    track.scrollTo({ left: item.offsetLeft - track.offsetLeft, behavior: 'smooth' });
  }

  function updateControls() {
    const idx = getActiveIndex();
    prevBtn.disabled = idx === 0;
    nextBtn.disabled = idx >= items.length - 1;
    getDots().forEach((d, i) => d.classList.toggle('is-active', i === idx));
  }

  prevBtn.addEventListener('click', () => scrollTo(Math.max(0, getActiveIndex() - 1)));
  nextBtn.addEventListener('click', () => scrollTo(Math.min(items.length - 1, getActiveIndex() + 1)));
  track.addEventListener('scroll', updateControls, { passive: true });
  updateControls();
})();


/* ── Dropdown Navigation ─────────────────────────────────── */
(function initDropdowns() {
  const navItems = document.querySelectorAll('.nav-item--has-children');
  if (!navItems.length) return;

  navItems.forEach(item => {
    const trigger  = item.querySelector('.nav-link--parent');
    const dropdown = item.querySelector('.nav-dropdown');
    if (!trigger || !dropdown) return;

    const links = Array.from(dropdown.querySelectorAll('.nav-dropdown-link'));
    let closeTimer = null;

    function open() {
      clearTimeout(closeTimer);
      trigger.setAttribute('aria-expanded', 'true');
      dropdown.style.display = 'block';
    }

    function scheduleClose() {
      closeTimer = setTimeout(close, 150);
    }

    function close() {
      clearTimeout(closeTimer);
      trigger.setAttribute('aria-expanded', 'false');
      dropdown.style.display = '';
    }

    function isOpen() {
      return trigger.getAttribute('aria-expanded') === 'true';
    }

    // Mouse: open on enter, delayed close on leave
    item.addEventListener('mouseenter', open);
    item.addEventListener('mouseleave', scheduleClose);

    // Keep open while hovering over dropdown itself
    dropdown.addEventListener('mouseenter', () => clearTimeout(closeTimer));
    dropdown.addEventListener('mouseleave', scheduleClose);

    // Click toggle (keyboard / touch users)
    trigger.addEventListener('click', e => {
      e.stopPropagation();
      isOpen() ? close() : open();
    });

    // Keyboard nav
    trigger.addEventListener('keydown', e => {
      if (e.key === 'ArrowDown') { e.preventDefault(); open(); links[0]?.focus(); }
      if (e.key === 'Escape')    { close(); trigger.focus(); }
    });

    links.forEach((link, i) => {
      link.addEventListener('keydown', e => {
        if (e.key === 'ArrowDown') { e.preventDefault(); (links[i + 1] || links[0]).focus(); }
        if (e.key === 'ArrowUp')   { e.preventDefault(); (links[i - 1] || links[links.length - 1]).focus(); }
        if (e.key === 'Escape')    { close(); trigger.focus(); }
        if (e.key === 'Tab' && !e.shiftKey && i === links.length - 1) { close(); }
        if (e.key === 'Tab' && e.shiftKey  && i === 0)                { close(); }
      });
    });

    // Close on outside click
    document.addEventListener('click', e => {
      if (!item.contains(e.target)) close();
    });
  });
})();


/* ── Drawer Accordion (mobile sub-menus) ─────────────────── */
(function initDrawerAccordion() {
  const triggers = document.querySelectorAll('.drawer-link--parent');
  if (!triggers.length) return;

  triggers.forEach(trigger => {
    const sub = trigger.closest('.drawer-item--has-children')?.querySelector('.drawer-sub');
    if (!sub) return;

    trigger.addEventListener('click', () => {
      const expanded = trigger.getAttribute('aria-expanded') === 'true';
      trigger.setAttribute('aria-expanded', String(!expanded));
      sub.hidden = expanded;
    });
  });
})();
