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
			if (window.innerWidth > 991) { setOpen(false); }
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
				box.classList.add('has-moved');
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

	function initLeaderTabs() {
		// 0.31.0: no hidden tabs. Every group stays visible on one page; a simple
		// "Jump to" bar of plain buttons scrolls to each group (easier for everyone).
		var secs = Array.prototype.slice.call(document.querySelectorAll('.lof-leaders'));
		if (!secs.length) { return; }
		var steps = document.querySelector('.lof-steps');
		var stepsSec = steps ? steps.closest('section') : null;
		var items = [
			{ id: 'founder', label: 'Our founder', el: secs.filter(function (s) { return /lof-leaders--executive\b/.test(s.className); })[0] },
			{ id: 'board', label: 'Board members', el: secs.filter(function (s) { return /lof-leaders--board\b/.test(s.className); })[0] },
			{ id: 'programme-leads', label: 'Programme leads', el: secs.filter(function (s) { return /lof-leaders--team\b/.test(s.className); })[0] },
			{ id: 'how-we-are-run', label: 'How we are run', el: stepsSec }
		].filter(function (g) { return g.el; });
		if (items.length < 2) { return; }
		var bar = document.createElement('nav');
		bar.className = 'lof-jump';
		bar.setAttribute('aria-label', 'Jump to a section');
		var html = '<div class="lof-container"><span class="lof-jump__label">Jump to:</span><ul>';
		items.forEach(function (g) {
			g.el.id = g.el.id || g.id;
			g.el.hidden = false;
			html += '<li><a href="#' + g.el.id + '">' + g.label + '</a></li>';
		});
		bar.innerHTML = html + '</ul></div>';
		secs[0].parentNode.insertBefore(bar, secs[0]);
		secs.forEach(function (el) { el.querySelectorAll('.lof-reveal').forEach(function (r) { r.classList.add('is-visible'); }); });
	}

	function initFaq() {
		document.querySelectorAll('.lof-faq').forEach(function (faq) {
			var items = faq.querySelectorAll('details');
			items.forEach(function (d) {
				d.addEventListener('toggle', function () {
					if (d.open) { items.forEach(function (o) { if (o !== d) { o.open = false; } }); }
				});
			});
		});
	}

	function initGivePanel() {
		document.querySelectorAll('[data-lof-give]').forEach(function (f) {
			var other = f.querySelector('input[name="amount_other"]');
			var wrap = f.querySelector('.lof-givepanel__other');
			function sync() {
				var sel = f.querySelector('input[name="amount"]:checked');
				var isOther = sel && sel.value === 'other';
				if (wrap) { wrap.classList.toggle('is-active', !!isOther); }
				if (other) { other.required = !!isOther; }
			}
			f.addEventListener('change', sync);
			if (other) {
				other.addEventListener('focus', function () { var o = f.querySelector('input[name="amount"][value="other"]'); if (o) { o.checked = true; sync(); } });
			}
			f.addEventListener('submit', function () {
				var sel = f.querySelector('input[name="amount"]:checked');
				if (other && !(sel && sel.value === 'other')) { other.disabled = true; }
			});
			sync();
		});
	}

	function initCine() {
		document.querySelectorAll('[data-lof-cine]').forEach(function (box) {
			var slides = box.querySelectorAll('.lof-cine__slide');
			var tabs = box.querySelectorAll('.lof-cine__tab');
			var count = box.querySelector('.lof-cine__count strong');
			var pauseBtn = box.querySelector('[data-pause]');
			if (slides.length < 2) { return; }
			var ms = (parseInt(box.getAttribute('data-interval'), 10) || 7) * 1000;
			var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			var i = 0, timer = null, paused = still, hover = false;
			/* 0.63.0: the first slide stays still until the page has finished loading. */
			var ready = document.readyState === 'complete';
			if (ready) { box.classList.add('is-ready'); } else { window.addEventListener('load', function () { window.setTimeout(function () { ready = true; box.classList.add('is-ready'); restart(); }, 400); }); }
			function pad(n) { return (n < 10 ? '0' : '') + n; }
			function go(n) {
				slides[i].classList.remove('is-active');
				slides[i].setAttribute('aria-hidden', 'true');
				slides[i].querySelectorAll('a').forEach(function (a) { a.tabIndex = -1; });
				if (tabs[i]) { tabs[i].classList.remove('is-active'); tabs[i].removeAttribute('aria-current'); }
				i = (n + slides.length) % slides.length;
				slides[i].classList.add('is-active');
				slides[i].removeAttribute('aria-hidden');
				slides[i].querySelectorAll('a').forEach(function (a) { a.removeAttribute('tabindex'); });
				if (tabs[i]) {
					tabs[i].classList.remove('is-active');
					void tabs[i].offsetWidth;
					tabs[i].classList.add('is-active');
					tabs[i].setAttribute('aria-current', 'true');
				}
				if (count) { count.textContent = pad(i + 1); }
				restart();
			}
			function restart() {
				window.clearTimeout(timer);
				box.classList.toggle('is-paused', paused || hover);
				if (!paused && !hover && ready) { timer = window.setTimeout(function () { go(i + 1); }, ms); }
			}
			tabs.forEach(function (t) { t.addEventListener('click', function () { go(parseInt(t.getAttribute('data-go'), 10)); }); });
			var prev = box.querySelector('[data-prev]'), next = box.querySelector('[data-next]');
			if (prev) { prev.addEventListener('click', function () { go(i - 1); }); }
			if (next) { next.addEventListener('click', function () { go(i + 1); }); }
			if (pauseBtn) {
				pauseBtn.setAttribute('aria-pressed', paused ? 'true' : 'false');
				pauseBtn.addEventListener('click', function () {
					paused = !paused;
					pauseBtn.setAttribute('aria-pressed', paused ? 'true' : 'false');
					pauseBtn.setAttribute('aria-label', paused ? 'Play slideshow' : 'Pause slideshow');
					restart();
				});
			}
			box.addEventListener('mouseenter', function () { hover = true; restart(); });
			box.addEventListener('mouseleave', function () { hover = false; restart(); });
			box.addEventListener('focusin', function () { hover = true; restart(); });
			box.addEventListener('focusout', function (e) { if (!box.contains(e.relatedTarget)) { hover = false; restart(); } });
			box.addEventListener('keydown', function (e) {
				if (e.key === 'ArrowRight') { go(i + 1); } else if (e.key === 'ArrowLeft') { go(i - 1); }
			});
			var x0 = null;
			box.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
			box.addEventListener('touchend', function (e) {
				if (x0 === null) { return; }
				var dx = e.changedTouches[0].clientX - x0;
				if (Math.abs(dx) > 45) { go(i + (dx < 0 ? 1 : -1)); }
				x0 = null;
			}, { passive: true });
			document.addEventListener('visibilitychange', function () { if (document.hidden) { window.clearTimeout(timer); } else { restart(); } });
			restart();
		});
	}

	function initMobileSubmenus() {
		var nav = document.querySelector('.lof-nav');
		if (!nav) { return; }
		var mq = window.matchMedia('(max-width: 991px)');
		nav.querySelectorAll('.lof-menu > .menu-item-has-children').forEach(function (li, n) {
			var sub = li.querySelector(':scope > .sub-menu');
			var link = li.querySelector(':scope > a');
			if (!sub || !link) { return; }
			sub.id = sub.id || ('lof-sub-' + n);
			var b = document.createElement('button');
			b.type = 'button';
			b.className = 'lof-sub-toggle';
			b.setAttribute('aria-controls', sub.id);
			b.setAttribute('aria-expanded', 'false');
			b.setAttribute('aria-label', 'Show ' + link.textContent.trim() + ' pages');
			b.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="M7.4 8.6 12 13.2l4.6-4.6L18 10l-6 6-6-6z"/></svg>';
			link.insertAdjacentElement('afterend', b);
			function set(open) {
				li.classList.toggle('is-open', open);
				b.setAttribute('aria-expanded', open ? 'true' : 'false');
			}
			b.addEventListener('click', function () {
				var open = !li.classList.contains('is-open');
				if (open) { nav.querySelectorAll('.lof-menu > .is-open').forEach(function (o) { if (o !== li) { o.classList.remove('is-open'); var ob = o.querySelector(':scope > .lof-sub-toggle'); if (ob) { ob.setAttribute('aria-expanded', 'false'); } } }); }
				set(open);
			});
			if (mq.matches && (li.classList.contains('current-menu-ancestor') || li.classList.contains('current-menu-parent'))) { set(true); }
		});
		nav.classList.add('has-sub-toggles');
	}

	function initTabbar() {
		var btn = document.querySelector('[data-lof-tabmenu]');
		var toggle = document.querySelector('.lof-nav-toggle');
		if (!btn || !toggle) { return; }
		btn.addEventListener('click', function () {
			toggle.click();
			btn.setAttribute('aria-expanded', toggle.getAttribute('aria-expanded') === 'true' ? 'true' : 'false');
			window.scrollTo({ top: 0, behavior: 'smooth' });
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
		initCine();
		initTabbar();
		initMobileSubmenus();
		initLeaderTabs();
		initFaq();
		initGivePanel();
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

/* 0.63.5: footer link groups open and close on phones. */
(function(){var f=document.getElementById('site-footer');if(!f){return;}f.classList.add('lof-footer--acc');f.querySelectorAll('.lof-footer__col:not(.lof-footer__support)').forEach(function(col,n){var t=col.querySelector('.lof-footer__title');var list=col.querySelector('.lof-footer__links');if(!t||!list){return;}list.id=list.id||('lof-footlinks-'+n);var c=document.createElement('span');c.className='lof-footacc__chev';c.setAttribute('aria-hidden','true');c.innerHTML='<svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M7.4 8.6 12 13.2l4.6-4.6L18 10l-6 6-6-6z"/></svg>';t.appendChild(c);var mq=window.matchMedia('(max-width: 767px)');function sync(){if(mq.matches){t.setAttribute('role','button');t.setAttribute('tabindex','0');t.setAttribute('aria-controls',list.id);t.setAttribute('aria-expanded',col.classList.contains('is-open')?'true':'false');}else{t.removeAttribute('role');t.removeAttribute('tabindex');t.removeAttribute('aria-expanded');}}function toggle(){if(!mq.matches){return;}col.classList.toggle('is-open');sync();}t.addEventListener('click',toggle);t.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();toggle();}});if(mq.addEventListener){mq.addEventListener('change',sync);}sync();});}());
