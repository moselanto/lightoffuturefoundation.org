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
			var t = phone && phone.matches && window.scrollY < 160 && panel.hidden;
			chat.classList.toggle('is-tucked', !!t);
		};
		tuck();
		window.addEventListener('scroll', tuck, { passive: true });
		window.addEventListener('resize', tuck);
		window.lofChat = { open: function (msg) { if (msg) { text.value = msg; } open(); if (msg) { setTimeout(function () { text.focus(); }, 60); } } };
		var close = function () { panel.hidden = true; fab.setAttribute('aria-expanded', 'false'); fab.focus(); };
		typing.hidden = true;
		fab.addEventListener('click', function (e) { e.preventDefault(); if (panel.hidden) { open(); } else { close(); } });
		chat.querySelector('.lof-chat__close').addEventListener('click', close);
		chat.querySelector('.lof-chat__teaser-open').addEventListener('click', open);
		chat.querySelector('.lof-chat__teaser-close').addEventListener('click', function () { teaser.hidden = true; store.set('lofChatSeen', '1'); });
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
		if ('1' !== chat.getAttribute('data-quiet') && !store.get('lofChatSeen') && !(window.matchMedia && window.matchMedia('(max-width: 640px)').matches)) {
			setTimeout(function () { if (panel.hidden) { teaser.hidden = false; dot.hidden = false; } }, 6000);
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
