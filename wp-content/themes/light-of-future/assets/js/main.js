/* Light of Future Foundation - front-end behaviour v0.5.0 */
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

	function initTheme() {
		var btn = document.querySelector('.lof-theme-toggle');
		var root = document.documentElement;
		if (!btn) { return; }
		function sync() {
			var dark = root.getAttribute('data-theme') === 'dark';
			btn.setAttribute('aria-pressed', dark ? 'true' : 'false');
		}
		btn.addEventListener('click', function () {
			var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
			root.setAttribute('data-theme', next);
			try { localStorage.setItem('lof-theme', next); } catch (e) {}
			sync();
		});
		if (window.matchMedia) {
			var mq = window.matchMedia('(prefers-color-scheme: dark)');
			var onChange = function (e) {
				var saved = null;
				try { saved = localStorage.getItem('lof-theme'); } catch (err) {}
				if (!saved) { root.setAttribute('data-theme', e.matches ? 'dark' : 'light'); sync(); }
			};
			if (mq.addEventListener) { mq.addEventListener('change', onChange); }
		}
		sync();
	}

	function initSubmenus() {
		document.querySelectorAll('.lof-nav .menu-item-has-children > a').forEach(function (a) {
			a.setAttribute('aria-haspopup', 'true');
			a.setAttribute('aria-expanded', 'false');
			var li = a.parentNode;
			li.addEventListener('mouseenter', function () { a.setAttribute('aria-expanded', 'true'); });
			li.addEventListener('mouseleave', function () { a.setAttribute('aria-expanded', 'false'); });
			li.addEventListener('focusin', function () { a.setAttribute('aria-expanded', 'true'); });
			li.addEventListener('focusout', function (e) { if (!li.contains(e.relatedTarget)) { a.setAttribute('aria-expanded', 'false'); } });
		});
	}

	function initRibbons() {
		document.querySelectorAll('.lof-ribbon__pause').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var r = btn.closest('.lof-ribbon');
				var paused = r.classList.toggle('is-paused');
				btn.setAttribute('aria-pressed', paused ? 'true' : 'false');
				btn.setAttribute('aria-label', paused ? (btn.getAttribute('data-label-play') || 'Play moving text') : (btn.getAttribute('data-label-pause') || 'Pause moving text'));
			});
		});
	}

	function initMobileNavFocus() {
		var nav = document.getElementById('lof-primary-nav');
		var toggle = document.querySelector('.lof-nav-toggle');
		if (!nav || !toggle) { return; }
		nav.addEventListener('keydown', function (e) {
			if (e.key !== 'Tab' || !nav.classList.contains('is-open')) { return; }
			var f = nav.querySelectorAll('a, button');
			if (!f.length) { return; }
			var first = f[0], last = f[f.length - 1];
			if (e.shiftKey && document.activeElement === first) { e.preventDefault(); toggle.focus(); }
			else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); toggle.focus(); }
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		initRibbons();
		initMobileNavFocus();
		initTheme();
		initSubmenus();
		initCount();
		initNav();
		initHeaderShadow();
		initReveal();
	});
})();
