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
			panel.hidden = false; teaser.hidden = true; dot.hidden = true;
			fab.setAttribute('aria-expanded', 'true'); store.set('lofChatSeen', '1');
			if (!reduce && !store.get('lofChatTyped')) {
				bubble.hidden = true; typing.hidden = false;
				setTimeout(function () { typing.hidden = true; bubble.hidden = false; store.set('lofChatTyped', '1'); }, 900);
			} else { typing.hidden = true; }
			setTimeout(function () { var c = panel.querySelector('.lof-chat__chip'); if (c) { c.focus(); } }, 50);
		};
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

	/* Order on WhatsApp on the product page: add chosen design and quantity. */
	document.querySelectorAll('[data-lof-wa-order]').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			var form = document.querySelector('form.cart');
			var parts = [];
			if (form) {
				form.querySelectorAll('select[name^="attribute_"]').forEach(function (s) {
					if (s.value) { parts.push(s.options[s.selectedIndex].text); }
				});
			}
			var qtyEl = form ? form.querySelector('input.qty') : null;
			var qty = qtyEl ? parseInt(qtyEl.value, 10) || 1 : 1;
			var priceEl = document.querySelector('.woocommerce-variation-price .amount');
			var price = priceEl ? priceEl.textContent.trim() : btn.getAttribute('data-price');
			var msg = 'Hello Light of Future Foundation, I would like to order: ' + btn.getAttribute('data-name');
			if (parts.length) { msg += ' (' + parts.join(', ') + ')'; }
			msg += '\nQuantity: ' + qty;
			if (price) { msg += '\nPrice: ' + price; }
			msg += '\n' + btn.getAttribute('data-url');
			e.preventDefault();
			window.open('https://wa.me/' + btn.getAttribute('data-number') + '?text=' + encodeURIComponent(msg), '_blank', 'noopener');
		});
	});
}());
