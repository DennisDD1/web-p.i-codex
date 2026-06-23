(function () {
	'use strict';
	var root = document.querySelector('[data-archive-player]');
	if (!root) return;
	var viewport = root.querySelector('[data-archive-viewport]');
	var scenes = Array.prototype.slice.call(root.querySelectorAll('[data-scene]'));
	var buttons = Array.prototype.slice.call(root.querySelectorAll('[data-scene-target]'));
	var menu = root.querySelector('.painter-archive__menu');
	var toggle = root.querySelector('[data-menu-toggle]');
	var close = root.querySelector('[data-menu-close]');
	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var active = 0;
	var locked = false;
	var touchStart = 0;

	function setActive(index, scroll) {
		active = Math.max(0, Math.min(index, scenes.length - 1));
		scenes.forEach(function (scene, i) { scene.classList.toggle('is-active', i === active); });
		buttons.forEach(function (button, i) {
			button.classList.toggle('is-active', i === active);
			button.setAttribute('aria-current', i === active ? 'true' : 'false');
		});
		if (scroll) {
			viewport.scrollTop = scenes[active].offsetTop;
		}
	}

	function nearestScene() {
		var best = 0;
		var distance = Infinity;
		scenes.forEach(function (scene, i) {
			var next = Math.abs(scene.getBoundingClientRect().top);
			if (next < distance) { best = i; distance = next; }
		});
		setActive(best, false);
	}

	viewport.addEventListener('scroll', function () { window.requestAnimationFrame(nearestScene); }, { passive: true });
	viewport.addEventListener('wheel', function (event) {
		if (Math.abs(event.deltaY) < 18 || locked) return;
		event.preventDefault();
		locked = true;
		setActive(active + (event.deltaY > 0 ? 1 : -1), true);
		window.setTimeout(function () { locked = false; }, reducedMotion ? 100 : 650);
	}, { passive: false });
	viewport.addEventListener('touchstart', function (event) { touchStart = event.touches[0].clientY; }, { passive: true });
	viewport.addEventListener('touchend', function (event) {
		var distance = touchStart - event.changedTouches[0].clientY;
		if (Math.abs(distance) > 45) setActive(active + (distance > 0 ? 1 : -1), true);
	}, { passive: true });
	buttons.forEach(function (button) {
		button.addEventListener('click', function () { setActive(Number(button.getAttribute('data-scene-target')), true); });
	});

	function toggleMenu(open) {
		menu.classList.toggle('is-open', open);
		menu.setAttribute('aria-hidden', open ? 'false' : 'true');
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
	}
	toggle.addEventListener('click', function () { toggleMenu(!menu.classList.contains('is-open')); });
	close.addEventListener('click', function () { toggleMenu(false); });
	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') toggleMenu(false);
		if (event.key === 'ArrowDown') setActive(active + 1, true);
		if (event.key === 'ArrowUp') setActive(active - 1, true);
	});
	setActive(0, false);
}());
