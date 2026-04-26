(() => {
  const revealEls = document.querySelectorAll('[data-reveal]');

  const heroReveals = document.querySelectorAll('.hero [data-reveal]');
  requestAnimationFrame(() => {
    heroReveals.forEach(el => el.classList.add('is-visible'));
  });

  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    revealEls.forEach(el => {
      if (!el.closest('.hero')) io.observe(el);
    });

    document.querySelectorAll('.section-title, .section-lead, .product-card, .pillar, .testimonial, .polaroid, .peek-visual, .stats')
      .forEach(el => {
        el.classList.add('auto-reveal');
        io.observe(el);
      });
  } else {
    revealEls.forEach(el => el.classList.add('is-visible'));
  }

  const style = document.createElement('style');
  style.textContent = `
    .auto-reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s cubic-bezier(.22,.61,.36,1), transform .7s cubic-bezier(.22,.61,.36,1); }
    .auto-reveal.is-visible { opacity: 1; transform: translateY(0); }
    .product-card.auto-reveal { transition-delay: .05s; }
    .product-card.auto-reveal:nth-of-type(2) { transition-delay: .12s; }
    .product-card.auto-reveal:nth-of-type(3) { transition-delay: .19s; }
    .product-card.auto-reveal:nth-of-type(4) { transition-delay: .26s; }
    .product-card.auto-reveal:nth-of-type(5) { transition-delay: .33s; }
    .product-card.auto-reveal:nth-of-type(6) { transition-delay: .40s; }
    .pillar.auto-reveal:nth-of-type(1) { transition-delay: .05s; }
    .pillar.auto-reveal:nth-of-type(2) { transition-delay: .15s; }
    .pillar.auto-reveal:nth-of-type(3) { transition-delay: .25s; }
    .testimonial.auto-reveal:nth-of-type(1) { transition-delay: .05s; }
    .testimonial.auto-reveal:nth-of-type(2) { transition-delay: .15s; }
    .testimonial.auto-reveal:nth-of-type(3) { transition-delay: .25s; }
  `;
  document.head.appendChild(style);

  // ==========================================================================
  // WooCommerce cart integration
  // ==========================================================================

  const countEls = document.querySelectorAll('[data-cart-count]');

  function setCartCount(n) {
    countEls.forEach(el => {
      el.textContent = String(n);
      el.animate(
        [{ transform: 'scale(1)' }, { transform: 'scale(1.35)' }, { transform: 'scale(1)' }],
        { duration: 380, easing: 'cubic-bezier(.22,.61,.36,1)' }
      );
    });
  }

  async function fetchCart() {
    try {
      const r = await fetch('/wp-json/wc/store/v1/cart?_=' + Date.now(), {
        credentials: 'include',
        cache: 'no-store',
        headers: { 'Accept': 'application/json', 'Cache-Control': 'no-cache' },
      });
      if (!r.ok) throw new Error('cart fetch ' + r.status);
      const data = await r.json();
      const count = data.items_count || (data.items || []).reduce((a, i) => a + (i.quantity || 0), 0);
      setCartCount(count);
      return data;
    } catch (e) {
      console.warn('[rigolettres] cart fetch failed', e);
      return null;
    }
  }

  let storeNonce = null;
  async function getStoreNonce() {
    if (storeNonce) return storeNonce;
    const r = await fetch('/wp-json/wc/store/v1/cart?_=' + Date.now(), {
      credentials: 'include',
      cache: 'no-store',
      headers: { 'Accept': 'application/json', 'Cache-Control': 'no-cache' },
    });
    storeNonce = r.headers.get('Nonce') || r.headers.get('nonce') || r.headers.get('X-WC-Store-API-Nonce') || null;
    return storeNonce;
  }

  async function addToCart(productId, quantity = 1) {
    const nonce = await getStoreNonce();
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    if (nonce) headers['Nonce'] = nonce;
    const r = await fetch('/wp-json/wc/store/v1/cart/add-item', {
      method: 'POST',
      credentials: 'include',
      headers,
      body: JSON.stringify({ id: Number(productId), quantity: Number(quantity) }),
    });
    if (!r.ok) {
      const txt = await r.text();
      throw new Error('add failed ' + r.status + ': ' + txt.slice(0, 200));
    }
    return r.json();
  }

  // Toast helpers
  function toast({ message, link, linkText, error = false }) {
    let el = document.querySelector('.cart-toast');
    if (!el) {
      el = document.createElement('div');
      el.className = 'cart-toast';
      el.setAttribute('role', 'status');
      el.setAttribute('aria-live', 'polite');
      document.body.appendChild(el);
    }
    el.classList.toggle('error', !!error);
    const icon = error
      ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><circle cx="12" cy="16" r=".6" fill="currentColor"/></svg>'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>';
    el.innerHTML = `${icon}<span>${message}${link ? ` <a href="${link}">${linkText || 'Voir'}</a>` : ''}</span>`;
    el.classList.add('show');
    clearTimeout(toast._t);
    toast._t = setTimeout(() => el.classList.remove('show'), 4200);
  }

  // Add-to-cart click handler (event delegation)
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-add-to-cart]');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    const pid = btn.getAttribute('data-pid');
    if (!pid) return;

    if (btn.getAttribute('aria-busy') === 'true') return;
    btn.setAttribute('aria-busy', 'true');
    const originalLabel = btn.textContent;

    try {
      const res = await addToCart(pid, 1);
      btn.classList.add('is-added');
      btn.textContent = 'Ajouté ✓';
      const name = (res && res.name) || 'Article';
      const count = res && typeof res.quantity_limits === 'object' ? null : null;
      // Refresh cart to get total count
      const cart = await fetchCart();
      toast({
        message: `Ajouté au panier.`,
        link: '/cart/',
        linkText: 'Voir le panier →',
      });
      setTimeout(() => {
        btn.classList.remove('is-added');
        btn.textContent = originalLabel;
      }, 1800);
    } catch (err) {
      console.error('[rigolettres] add-to-cart failed', err);
      toast({
        message: 'Impossible d\'ajouter ce produit. Réessayez.',
        error: true,
      });
      btn.textContent = originalLabel;
    } finally {
      btn.removeAttribute('aria-busy');
    }
  });

  // Prevent product-link click when a nested interactive element handles it
  document.querySelectorAll('.product-link').forEach(link => {
    link.addEventListener('click', (e) => {
      if (e.target.closest('[data-add-to-cart]')) {
        e.preventDefault();
      }
    });
  });

  // Initial cart count sync
  fetchCart();

  // Parallax sky
  const sky = document.querySelector('.sky');
  if (sky) {
    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          const y = window.scrollY;
          sky.style.transform = `translateY(${Math.min(y * 0.06, 40)}px)`;
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }
})();
