/* Light of Future Foundation - front-end behaviour v0.3.0 */
(function () {
	'use strict';

	function initNav() {
		var toggle = document.querySelector('.lof-nav-toggle');
		var nav = document.getElementById('lof-primary-nav');
		if (!toggle || !nav) { return; }
		function setOpen(open) {
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			nav.classList.toggle('is-open', open);
			document.documentElement.style.overflow = open ? 'hidden' : '';
		}
		toggle.addEventListener('click', function () {
			setOpen(toggle.getAttribute('aria-expanded') !== 'true');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && nav.classList.contains('is-open')) { setOpen(false); toggle.focus(); }
		});
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a')) { setOpen(false); }
		});
		window.addEventListener('resize', function () {
			if (window.innerWidth > 1024) { setOpen(false); }
		});
	}

	function initHeaderShadow() {
		var header = document.querySelector('[data-lof-header]');
		if (!header) { return; }
		var onScroll = function () { header.classList.toggle('is-scrolled', window.scrollY > 8); };
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	function initReveal() {
		var items = document.querySelectorAll('.lof-reveal');
		if (!items.length) { return; }
		if (!('IntersectionObserver' in window)) {
			items.forEach(function (el) { el.classList.add('is-visible'); });
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) { entry.target.classList.add('is-visible'); io.unobserve(entry.target); }
			});
		}, { rootMargin: '0px 0px -10% 0px' });
		items.forEach(function (el) { io.observe(el); });
	}

	function initCount() {
		var els = document.querySelectorAll('.lof-count[data-count]');
		if (!els.length || !('IntersectionObserver' in window)) { return; }
		if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) { return; }
				var el = entry.target, end = parseInt(el.getAttribute('data-count'), 10), start = null;
				io.unobserve(el);
				function step(ts) {
					if (!start) { start = ts; }
					var p = Math.min((ts - start) / 1400, 1);
					el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3))).toLocaleString();
					if (p < 1) { window.requestAnimationFrame(step); }
				}
				el.textContent = '0';
				window.requestAnimationFrame(step);
			});
		}, { threshold: 0.4 });
		els.forEach(function (el) { io.observe(el); });
	}

	document.addEventListener('DOMContentLoaded', function () {
		initCount();
		initNav();
		initHeaderShadow();
		initReveal();
	});
})();
