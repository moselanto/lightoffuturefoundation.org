/* Light of Future: WhatsApp chat card + Order on WhatsApp (0.44.0). No cookies; sessionStorage only. */
(function () {
	'use strict';
	var chat = document.querySelector('[data-lof-chat]');
	if (chat) {
		var number = chat.getAttribute('data-number');
		var fab = chat.querySelector('.lof-chat__fab');
		var panel = chat.querySelector('.lof-chat__panel');
		var teaser = chat.querySelector('.lof-chat__teaser');
		var dot = chat.querySelector('.lof-chat__dot');
		var typing = chat.querySelector('.lof-chat__typing');
		var bubble = chat.querySelector('.lof-chat__bubble');
		var text = chat.querySelector('#lof-chat-text');
		var form = chat.querySelector('.lof-chat__form');
		var share = chat.querySelector('.lof-chat__share input');
		var pageUrl = chat.getAttribute('data-page-url') || '';
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var store = { get: function (k) { try { return sessionStorage.getItem(k); } catch (e) { return null; } }, set: function (k, v) { try { sessionStorage.setItem(k, v); } catch (e) {} } };
		var open = function () {
			panel.hidden = false; teaser.hidden = true; dot.hidden = true; chat.classList.remove('is-tucked');
			fab.setAttribute('aria-expanded', 'true'); store.set('lofChatSeen', '1');
			if (!reduce && !store.get('lofChatTyped')) {
				bubble.hidden = true; typing.hidden = false;
				setTimeout(function () { typing.hidden = true; bubble.hidden = false; store.set('lofChatTyped', '1'); }, 900);
			} else { typing.hidden = true; }
			setTimeout(function () { var c = panel.querySelector('.lof-chat__chip'); if (c) { c.focus(); } }, 50);
		};
		var phone = window.matchMedia ? window.matchMedia('(max-width: 991px)') : null;
		var tuck = function () {
			var t = phone && phone.matches && window.scrollY < 160 && panel.hidden && teaser.hidden;
			chat.classList.toggle('is-tucked', !!t);
		};
		tuck();
		var scrollTimer = null;
		window.addEventListener('scroll', function () {
			tuck();
			if (!panel.hidden || chat.contains(document.activeElement)) { return; }
			chat.classList.add('is-scrolling');
			clearTimeout(scrollTimer);
			scrollTimer = setTimeout(function () { chat.classList.remove('is-scrolling'); }, (phone && phone.matches) ? 1600 : 700);
		}, { passive: true });
		window.addEventListener('resize', tuck);
		window.lofChat = { open: function (msg) { if (msg) { text.value = msg; } open(); if (msg) { setTimeout(function () { text.focus(); }, 60); } } };
		var close = function () { panel.hidden = true; fab.setAttribute('aria-expanded', 'false'); fab.focus(); };
		typing.hidden = true;
		fab.addEventListener('click', function (e) { e.preventDefault(); if (panel.hidden) { open(); } else { close(); } });
		chat.querySelector('.lof-chat__close').addEventListener('click', close);
		chat.querySelector('.lof-chat__teaser-open').addEventListener('click', open);
		chat.querySelector('.lof-chat__teaser-close').addEventListener('click', function () { teaser.hidden = true; dot.hidden = true; store.set('lofChatSeen', '1'); tuck(); });
		document.addEventListener('keydown', function (e) { if ('Escape' === e.key && !panel.hidden) { close(); } });
		chat.querySelectorAll('.lof-chat__chip').forEach(function (chip) {
			chip.addEventListener('click', function () {
				chat.querySelectorAll('.lof-chat__chip').forEach(function (c) { c.setAttribute('aria-pressed', 'false'); });
				chip.setAttribute('aria-pressed', 'true');
				text.value = chip.textContent.trim(); text.focus();
			});
		});
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var msg = (text.value || '').trim() || 'Hello Light of Future Foundation, I would like to know more about your work.';
			if (share && share.checked && pageUrl) { msg += '\n' + pageUrl; }
			window.open('https://wa.me/' + number + '?text=' + encodeURIComponent(msg), '_blank', 'noopener');
		});
		/* 0.63.3: the greeting bubble is shown on phones only (on larger screens it covered page content). */
		var small = window.matchMedia && window.matchMedia('(max-width: 767px)').matches;
		if ('1' !== chat.getAttribute('data-quiet') && !store.get('lofChatSeen') && small) {
			setTimeout(function () { if (panel.hidden) { teaser.hidden = false; dot.hidden = false; tuck(); store.set('lofChatSeen', '1'); /* 0.65.1: auto-hide so it never sits over content */ setTimeout(function () { teaser.hidden = true; }, 6000); } }, 4000);
		}
	}

	/* WhatsApp order form popup (0.45.0). */
	var modal = document.querySelector('[data-lof-order-modal]');
	var lastBtn = null;
	var cur = {};
	if (modal) {
		var dlg = modal.querySelector('.lof-order__dialog');
		var mForm = modal.querySelector('.lof-order__form');
		var title = modal.querySelector('.lof-order__title');
		var priceEl = modal.querySelector('.lof-order__price');
		var thumb = modal.querySelector('.lof-order__thumb img');
		var dRow = modal.querySelector('.lof-order__row--design');
		var dSel = modal.querySelector('#lof-order-design');
		var qty = modal.querySelector('#lof-order-qty');
		var nameIn = modal.querySelector('#lof-order-name');
		var place = modal.querySelector('#lof-order-place');
		var note = modal.querySelector('#lof-order-note');
		var err = modal.querySelector('.lof-order__error');
		var ss = { get: function (k) { try { return sessionStorage.getItem(k) || ''; } catch (e) { return ''; } }, set: function (k, v) { try { sessionStorage.setItem(k, v); } catch (e) {} } };
		var openOrder = function (btn) {
			lastBtn = btn;
			cur = { name: btn.getAttribute('data-name') || '', price: btn.getAttribute('data-price') || '', url: btn.getAttribute('data-url') || '' };
			var designs = [];
			try { designs = JSON.parse(btn.getAttribute('data-designs') || '[]'); } catch (e) { designs = []; }
			title.textContent = cur.name;
			priceEl.textContent = cur.price;
			var img = btn.getAttribute('data-image');
			if (img) { thumb.src = img; thumb.hidden = false; } else { thumb.hidden = true; }
			dSel.innerHTML = '';
			if (designs.length) {
				var o0 = document.createElement('option'); o0.value = ''; o0.textContent = 'Choose a design'; dSel.appendChild(o0);
				designs.forEach(function (d) { var o = document.createElement('option'); o.value = d; o.textContent = d; dSel.appendChild(o); });
				dRow.hidden = false;
			} else { dRow.hidden = true; }
			qty.value = 1;
			var pre = btn.getAttribute('data-design');
			if (pre) { for (var k = 0; k < dSel.options.length; k++) { if (dSel.options[k].value === pre) { dSel.selectedIndex = k; } } }
			if (btn.getAttribute('data-single')) {
				var f = document.querySelector('form.cart');
				if (f) {
					var s = f.querySelector('select[name^="attribute_"]');
					if (s && s.value) { var t = s.options[s.selectedIndex].text; for (var i = 0; i < dSel.options.length; i++) { if (dSel.options[i].text === t) { dSel.selectedIndex = i; } } }
					var q = f.querySelector('input.qty'); if (q && parseInt(q.value, 10) > 0) { qty.value = parseInt(q.value, 10); }
				}
			}
			nameIn.value = ss.get('lofOrderName'); place.value = ss.get('lofOrderPlace'); note.value = '';
			err.hidden = true;
			[nameIn, place].forEach(function (f) { f.removeAttribute('aria-invalid'); f.removeAttribute('aria-describedby'); });
			setInert(true);
			modal.hidden = false; document.body.classList.add('lof-order-open');
			setTimeout(function () { (dRow.hidden ? (nameIn.value ? place : nameIn) : dSel).focus(); }, 60);
		};
		var setInert = function (on) {
			Array.prototype.forEach.call(document.body.children, function (el) {
				if (el === modal || el.tagName === 'SCRIPT') { return; }
				if (on) { if (!el.hasAttribute('inert')) { el.setAttribute('inert', ''); el.setAttribute('data-lof-inert', '1'); } } else if (el.getAttribute('data-lof-inert')) { el.removeAttribute('inert'); el.removeAttribute('data-lof-inert'); }
			});
		};
		var closeOrder = function () { setInert(false); modal.hidden = true; document.body.classList.remove('lof-order-open'); if (lastBtn) { lastBtn.focus(); } };
		modal.querySelectorAll('[data-lof-order-close]').forEach(function (el) { el.addEventListener('click', closeOrder); });
		modal.querySelectorAll('.lof-order__qty button').forEach(function (b) {
			b.addEventListener('click', function () { var v = (parseInt(qty.value, 10) || 1) + parseInt(b.getAttribute('data-step'), 10); qty.value = Math.max(1, Math.min(500, v)); });
		});
		document.addEventListener('keydown', function (e) {
			if (modal.hidden) { return; }
			if ('Escape' === e.key) { closeOrder(); }
			if ('Tab' === e.key) {
				var f = dlg.querySelectorAll('button, input, select, textarea, a[href]');
				var vis = Array.prototype.filter.call(f, function (el) { return el.offsetParent !== null; });
				if (!vis.length) { return; }
				if (e.shiftKey && document.activeElement === vis[0]) { e.preventDefault(); vis[vis.length - 1].focus(); } else if (!e.shiftKey && document.activeElement === vis[vis.length - 1]) { e.preventDefault(); vis[0].focus(); }
			}
		});
		mForm.addEventListener('submit', function (e) {
			e.preventDefault();
			var n = nameIn.value.trim(), p = place.value.trim();
			[[nameIn, n], [place, p]].forEach(function (x) { if (!x[1]) { x[0].setAttribute('aria-invalid', 'true'); x[0].setAttribute('aria-describedby', 'lof-order-error'); } else { x[0].removeAttribute('aria-invalid'); x[0].removeAttribute('aria-describedby'); } });
			if (!n || !p) { err.hidden = false; (n ? place : nameIn).focus(); return; }
			ss.set('lofOrderName', n); ss.set('lofOrderPlace', p);
			var lines = ['Hello Light of Future Foundation, I would like to order:', 'Product: ' + cur.name];
			if (!dRow.hidden && dSel.value) { lines.push('Design: ' + dSel.value); }
			lines.push('Quantity: ' + (parseInt(qty.value, 10) || 1));
			if (cur.price) { lines.push('Price shown: ' + cur.price); }
			lines.push('Name: ' + n, 'Delivery to: ' + p);
			if (note.value.trim()) { lines.push('Note: ' + note.value.trim()); }
			if (cur.url) { lines.push(cur.url); }
			window.open('https://wa.me/' + modal.getAttribute('data-number') + '?text=' + encodeURIComponent(lines.join('\n')), '_blank', 'noopener');
			closeOrder();
		});
	}

	/* Every WhatsApp link on the site opens a form first, as on the Hope Market. */
	document.addEventListener('click', function (e) {
		var a = e.target.closest ? e.target.closest('a') : null;
		if (!a) { return; }
		if (a.hasAttribute('data-lof-order') && modal) { e.preventDefault(); openOrder(a); return; }
		var href = a.getAttribute('href') || '';
		if (!/(wa\.me\/|api\.whatsapp\.com)/.test(href) || a.closest('.lof-chat') || a.closest('.lof-order')) { return; }
		if (window.lofChat) {
			e.preventDefault();
			var m = '';
			try { m = new URL(href, location.href).searchParams.get('text') || ''; } catch (x) { m = ''; }
			window.lofChat.open(m);
		}
	});
}());

/* 0.50.2 Leadership: in each row of cards, give the name/board/role block the
   same height so every description starts level (no text is shortened). */
(function () {
	'use strict';
	var lists = document.querySelectorAll('.lof-leaders--board, .lof-leaders--team');
	if (!lists.length) { return; }
	var level = function () {
		lists.forEach(function (sec) {
			var cards = [].slice.call(sec.querySelectorAll('.lof-person'));
			cards.forEach(function (c) { var r = c.querySelector('.lof-person__role'); if (r) { r.style.minHeight = ''; } });
			if (window.innerWidth < 641) { return; }
			var rows = {};
			cards.forEach(function (c) { var k = Math.round(c.getBoundingClientRect().top + window.scrollY); (rows[k] = rows[k] || []).push(c); });
			Object.keys(rows).forEach(function (k) {
				var row = rows[k];
				if (row.length < 2) { return; }
				var top = function (c) { var b = c.querySelector('.lof-person__bio'); return b ? b.getBoundingClientRect().top : 0; };
				var max = Math.max.apply(null, row.map(top));
				row.forEach(function (c) {
					var r = c.querySelector('.lof-person__role');
					if (r) { r.style.minHeight = (r.getBoundingClientRect().height + (max - top(c))) + 'px'; }
				});
			});
		});
	};
	var t;
	var run = function () { clearTimeout(t); t = setTimeout(level, 120); };
	if (document.readyState === 'complete') { level(); } else { window.addEventListener('load', level); }
	window.addEventListener('resize', run);
	if (document.fonts && document.fonts.ready) { document.fonts.ready.then(level); }
}());

/* 0.55.0: design chips, page jump links, leader bios */
(function () {
	'use strict';
	var ready = function (fn) { if (document.readyState !== 'loading') { fn(); } else { document.addEventListener('DOMContentLoaded', fn); } };
	ready(function () {
		/* Product design chips: select design, show its photo, pre-fill the order form. */
		var chips = document.querySelectorAll('.lof-pd__chip');
		Array.prototype.forEach.call(chips, function (chip) {
			chip.addEventListener('click', function () {
				Array.prototype.forEach.call(chips, function (c) { c.setAttribute('aria-pressed', 'false'); });
				chip.setAttribute('aria-pressed', 'true');
				var btn = document.querySelector('.lof-pd__order .lof-btn-order');
				if (btn) { btn.setAttribute('data-design', chip.getAttribute('data-design')); }
				var idx = chip.getAttribute('data-index');
				if (idx !== null) {
					var th = document.querySelectorAll('.woocommerce-product-gallery .flex-control-thumbs img');
					var n = parseInt(idx, 10);
					if (th[n]) { th[n].click(); } else if (window.jQuery) { var $g = window.jQuery('.woocommerce-product-gallery'); if ($g.data('flexslider')) { $g.flexslider(n); } }
				}
			});
		});
		/* 0.65.0: no generated "On this page" chip bar on About Us (it stuck under the header and covered content). */
		/* Leader bios: show four lines with Read more. */
		Array.prototype.forEach.call(document.querySelectorAll('.lof-person__bio'), function (bio, i) {
			if (bio.scrollHeight <= 130) { return; }
			bio.classList.add('is-clamped');
			if (!bio.id) { bio.id = 'lof-bio-' + i; }
			var more = document.createElement('button');
			more.type = 'button'; more.className = 'lof-person__more'; more.textContent = 'Read more';
			more.setAttribute('aria-expanded', 'false'); more.setAttribute('aria-controls', bio.id);
			more.addEventListener('click', function () {
				var open = bio.classList.toggle('is-clamped') === false;
				more.textContent = open ? 'Show less' : 'Read more';
				more.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
			bio.parentNode.insertBefore(more, bio.nextSibling);
		});
	});
}());

/* 0.55.1: Gallery section links stay at the top while scrolling */
(function () {
	'use strict';
	var nav = document.querySelector('.lofm-nav');
	if (!nav) { return; }
	var ph = document.createElement('div'); ph.className = 'lofm-nav-ph'; nav.parentNode.insertBefore(ph, nav);
	var head = function () { var h = document.querySelector('.lof-header'); if (!h || window.innerWidth < 992) { return 0; } var r = h.getBoundingClientRect(); return r.bottom > 0 ? r.bottom : 0; };
	var tick = false;
	var check = function () {
		tick = false;
		var top = head();
		var fixed = ph.getBoundingClientRect().top < top;
		if (fixed && !nav.classList.contains('is-fixed')) { ph.style.height = nav.offsetHeight + 'px'; nav.classList.add('is-fixed'); }
		if (!fixed && nav.classList.contains('is-fixed')) { nav.classList.remove('is-fixed'); ph.style.height = '0px'; }
		if (fixed) { nav.style.top = top + 'px'; }
	};
	window.addEventListener('scroll', function () { if (!tick) { tick = true; window.requestAnimationFrame(check); } }, { passive: true });
	window.addEventListener('resize', check);
	check();
}());

/* 0.58.0 Gallery: filters, show more, lightbox */
(function () {
	'use strict';
	var all = document.querySelector('.lof-page-gallery .lofm-all');
	if (!all) { return; }
	var secs = Array.prototype.slice.call(all.querySelectorAll('section.lofm'));
	if (!secs.length) { return; }
	/* Filters replace the long section links. */
	var nav = all.querySelector('.lofm-nav');
	var bar = document.createElement('div'); bar.className = 'lofm-filter'; bar.setAttribute('role', 'toolbar'); bar.setAttribute('aria-label', 'Filter the gallery');
	var count = function (s) { return s.querySelectorAll('.lofm__item').length + s.querySelectorAll('.lofm__video').length; };
	var mk = function (label, n, target) {
		var b = document.createElement('button'); b.type = 'button'; b.setAttribute('aria-pressed', target === 'all' ? 'true' : 'false'); b.setAttribute('data-target', target);
		b.appendChild(document.createTextNode(label)); var c = document.createElement('span'); c.className = 'lofm-filter__n'; c.textContent = n; b.appendChild(c); return b;
	};
	var total = 0; secs.forEach(function (s) { total += count(s); });
	bar.appendChild(mk('All', total, 'all'));
	secs.forEach(function (s) {
		var t = s.querySelector('.lofm__title'); var label = t ? t.textContent.split(':')[0].replace(/ and school materials| for girls| for boys and girls| in Ugenya|Community enterprise/g, function (m) { return m === 'Community enterprise' ? 'Enterprise' : ''; }).trim() : s.id;
		bar.appendChild(mk(label, count(s), s.id));
		if (t && !s.querySelector('.lofm__meta')) { var meta = document.createElement('p'); meta.className = 'lofm__meta'; var p = s.querySelectorAll('.lofm__item').length, v = s.querySelectorAll('.lofm__video').length; meta.textContent = [p ? p + (p === 1 ? ' photo' : ' photos') : '', v ? v + (v === 1 ? ' video' : ' videos') : ''].filter(Boolean).join(' \u00b7 '); t.parentNode.insertBefore(meta, t.nextSibling); }
	});
	if (nav) { var ul = nav.querySelector('ul'); if (ul) { nav.replaceChild(bar, ul); } else { nav.appendChild(bar); } } else { all.insertBefore(bar, all.firstChild); }
	bar.addEventListener('click', function (e) {
		var b = e.target.closest('button'); if (!b) { return; }
		var tgt = b.getAttribute('data-target');
		Array.prototype.forEach.call(bar.querySelectorAll('button'), function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
		secs.forEach(function (s) { s.classList.toggle('is-hidden', tgt !== 'all' && s.id !== tgt); });
		var top = all.getBoundingClientRect().top + window.pageYOffset - 90; if (window.pageYOffset > top) { window.scrollTo(0, top); }
	});
	/* Show more: eight photos per section at first. */
	secs.forEach(function (s) {
		var items = s.querySelectorAll('.lofm__item'); if (items.length <= 8) { return; }
		Array.prototype.forEach.call(items, function (it, i) { if (i >= 8) { it.classList.add('is-more'); } });
		var more = document.createElement('button'); more.type = 'button'; more.className = 'lofm__more'; more.textContent = 'Show all ' + items.length + ' photos';
		more.addEventListener('click', function () { var open = s.classList.toggle('is-expanded'); more.textContent = open ? 'Show fewer' : 'Show all ' + items.length + ' photos'; });
		var grid = s.querySelector('.lofm__grid'); grid.parentNode.insertBefore(more, grid.nextSibling);
	});
	/* Lightbox for photos. */
	var box = document.createElement('div'); box.className = 'lofx'; box.hidden = true; box.setAttribute('role', 'dialog'); box.setAttribute('aria-modal', 'true'); box.setAttribute('aria-label', 'Photo viewer');
	box.innerHTML = '<span class="lofx__count" aria-live="polite"></span><button type="button" class="lofx__close" aria-label="Close">\u00d7</button><button type="button" class="lofx__prev" aria-label="Previous photo">\u2039</button><img class="lofx__img" alt=""><p class="lofx__cap"></p><button type="button" class="lofx__next" aria-label="Next photo">\u203a</button>';
	document.body.appendChild(box);
	var img = box.querySelector('.lofx__img'), cap = box.querySelector('.lofx__cap'), cnt = box.querySelector('.lofx__count');
	var list = [], idx = 0, last = null;
	var usable = function (a) { var s = a.closest('.lofm__sens'); var sec = a.closest('section.lofm'); return (!s || s.classList.contains('is-open')) && sec && !sec.classList.contains('is-hidden') && a.offsetParent !== null; };
	var show = function (i) { idx = (i + list.length) % list.length; var a = list[idx]; img.src = a.href; var im = a.querySelector('img'); img.alt = im ? im.alt : ''; var fc = a.closest('figure') && a.closest('figure').querySelector('figcaption'); cap.textContent = fc ? fc.textContent : (im ? im.alt : ''); cnt.textContent = (idx + 1) + ' / ' + list.length; };
	var close = function () { box.hidden = true; document.body.classList.remove('lofx-open'); img.removeAttribute('src'); if (last) { last.focus(); } };
	all.addEventListener('click', function (e) {
		var a = e.target.closest('a.lofm__link'); if (!a || !usable(a)) { return; }
		e.preventDefault(); last = a;
		list = Array.prototype.filter.call(all.querySelectorAll('a.lofm__link'), usable);
		box.hidden = false; document.body.classList.add('lofx-open'); show(list.indexOf(a)); box.querySelector('.lofx__close').focus();
	});
	box.querySelector('.lofx__close').addEventListener('click', close);
	box.querySelector('.lofx__prev').addEventListener('click', function () { show(idx - 1); });
	box.querySelector('.lofx__next').addEventListener('click', function () { show(idx + 1); });
	box.addEventListener('click', function (e) { if (e.target === box) { close(); } });
	document.addEventListener('keydown', function (e) { if (box.hidden) { return; } if (e.key === 'Escape') { close(); } else if (e.key === 'ArrowLeft') { show(idx - 1); } else if (e.key === 'ArrowRight') { show(idx + 1); } else if (e.key === 'Tab') { var f = box.querySelectorAll('button'); var first = f[0], lastB = f[f.length - 1]; if (e.shiftKey && document.activeElement === first) { e.preventDefault(); lastB.focus(); } else if (!e.shiftKey && document.activeElement === lastB) { e.preventDefault(); first.focus(); } } });
	var sx = null; box.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; }, { passive: true });
	box.addEventListener('touchend', function (e) { if (sx === null) { return; } var dx = e.changedTouches[0].clientX - sx; if (Math.abs(dx) > 40) { show(idx + (dx < 0 ? 1 : -1)); } sx = null; });
}());

/* 0.59.0: Gallery video posters load only when near the screen */
(function () {
	var frames = document.querySelectorAll('[data-lof-poster]');
	if (!frames.length) { return; }
	function show(f) {
		var p = f.getAttribute('data-lof-poster'); if (!p) { return; }
		f.style.backgroundImage = 'url(' + p + ')';
		var v = f.querySelector('video[data-poster]'); if (v) { v.setAttribute('poster', v.getAttribute('data-poster')); v.removeAttribute('data-poster'); }
		f.removeAttribute('data-lof-poster');
	}
	if (!('IntersectionObserver' in window)) { frames.forEach(show); return; }
	var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { show(e.target); io.unobserve(e.target); } }); }, { rootMargin: '400px 0px' });
	frames.forEach(function (f) { io.observe(f); });
	document.addEventListener('click', function () { setTimeout(function () { document.querySelectorAll('[data-lof-poster]').forEach(function (f) { if (f.offsetParent) { show(f); } }); }, 50); });
})();

/* 0.63.0: shop category slider arrows. */
(function () {
	function init() {
		document.querySelectorAll('[data-cats-track]').forEach(function (track) {
			var box = track.closest('.lof-cats');
			if (!box) { return; }
			var prev = box.querySelector('[data-cats-prev]'), next = box.querySelector('[data-cats-next]');
			function step() { var c = track.querySelector('li'); return c ? (c.getBoundingClientRect().width + 16) * 2 : 300; }
			function sync() {
				var max = track.scrollWidth - track.clientWidth - 2;
				if (prev) { prev.disabled = track.scrollLeft <= 2; }
				if (next) { next.disabled = track.scrollLeft >= max; }
				box.classList.toggle('is-static', max <= 0);
			}
			if (prev) { prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); }); }
			if (next) { next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); }); }
			track.addEventListener('scroll', sync, { passive: true });
			window.addEventListener('resize', sync);
			sync();
		});
		document.querySelectorAll('.lof-shop63 .woocommerce-ordering select').forEach(function (s) {
			s.addEventListener('change', function () { if (s.form) { s.form.submit(); } });
		});
	}
	if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); } else { init(); }
}());
