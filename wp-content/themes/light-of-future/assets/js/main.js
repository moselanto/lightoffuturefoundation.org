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

	function initHeadHeight() {
		var root = document.documentElement;
		function set() {
			var t = document.querySelector('.lof-topbar');
			var h = document.querySelector('.lof-header');
			var v = (t ? t.offsetHeight : 0) + (h ? h.offsetHeight : 0);
			if (v) { root.style.setProperty('--lof-head-h', v + 'px'); }
			navTop();
		}
		function navTop() {
			var h = document.querySelector('.lof-header');
			if (h) { root.style.setProperty('--lof-nav-top', Math.max(0, Math.round(h.getBoundingClientRect().bottom)) + 'px'); }
		}
		set();
		window.addEventListener('resize', set);
		window.addEventListener('scroll', navTop, { passive: true });
		var tg = document.querySelector('.lof-nav-toggle');
		if (tg) { tg.addEventListener('click', navTop); }
	}

	function initHeroSlider() {
		document.querySelectorAll('[data-lof-slider]').forEach(function (box) {
			var slides = box.querySelectorAll('.lof-hero__slide');
			var dots = box.querySelectorAll('.lof-hero__dot');
			if (slides.length < 2) { return; }
			var i = 0, timer = null;
			var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			function go(n) {
				slides[i].classList.remove('is-active');
				if (dots[i]) { dots[i].classList.remove('is-active'); dots[i].removeAttribute('aria-current'); }
				i = (n + slides.length) % slides.length;
				slides[i].classList.add('is-active');
				if (dots[i]) { dots[i].classList.add('is-active'); dots[i].setAttribute('aria-current', 'true'); }
			}
			function play() { if (!still && !timer) { timer = window.setInterval(function () { go(i + 1); }, 6000); } }
			function stop() { window.clearInterval(timer); timer = null; }
			dots.forEach(function (d, n) { d.addEventListener('click', function () { stop(); go(n); play(); }); });
			box.addEventListener('mouseenter', stop);
			box.addEventListener('mouseleave', play);
			box.addEventListener('focusin', stop);
			box.addEventListener('focusout', play);
			play();
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
		initHeadHeight();
		initHeroSlider();
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
