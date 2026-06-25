(function () {
	'use strict';

	var root = document.querySelector('[data-archive-player]');
	if (!root) return;

	var scenes = Array.prototype.slice.call(root.querySelectorAll('[data-scene]'));
	var buttons = Array.prototype.slice.call(root.querySelectorAll('[data-scene-target]'));
	var viewButtons = Array.prototype.slice.call(root.querySelectorAll('[data-view-mode]'));
	var gridView = root.querySelector('[data-grid-view]');
	var rail = root.querySelector('[data-coverflow]');
	var caption = root.querySelector('[data-archive-caption]');
	var captionCopy = root.querySelector('[data-caption-copy]');
	var captionFields = {
		sku: root.querySelector('[data-caption-sku]'),
		kicker: root.querySelector('[data-caption-kicker]'),
		title: root.querySelector('[data-caption-title]'),
		story: root.querySelector('[data-caption-story]'),
		size: root.querySelector('[data-caption-size]'),
		year: root.querySelector('[data-caption-year]')
	};
	var menu = root.querySelector('.painter-archive__menu');
	var toggle = root.querySelector('[data-menu-toggle]');
	var close = root.querySelector('[data-menu-close]');
	var mailtoLinks = Array.prototype.slice.call(root.querySelectorAll('[data-mailto-trigger]'));
	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var visualDuration = reducedMotion ? 0 : 560;
	var captionWipeDuration = reducedMotion ? 0 : 180;
	var active = 0;
	var transitioning = false;
	var cooldownUntil = 0;
	var viewMode = 'player';
	var touchStart = null;

	function sceneData(index) {
		var scene = scenes[index];
		return {
			sku: scene.getAttribute('data-scene-sku') || '',
			kicker: scene.getAttribute('data-scene-kicker') || '',
			title: scene.getAttribute('data-scene-title') || '',
			story: scene.getAttribute('data-scene-story') || '',
			size: scene.getAttribute('data-scene-size') || '',
			year: scene.getAttribute('data-scene-year') || '',
			accent: scene.getAttribute('data-scene-accent') || '#111'
		};
	}

	function updateButtons(index) {
		buttons.forEach(function (button, buttonIndex) {
			var selected = buttonIndex === index;
			button.classList.toggle('is-active', selected);
			button.setAttribute('aria-current', selected ? 'true' : 'false');
		});
		if (buttons[index] && rail) {
			var horizontal = window.getComputedStyle(rail).flexDirection === 'row';
			var scrollOptions = { behavior: reducedMotion ? 'auto' : 'smooth' };
			if (horizontal) {
				scrollOptions.left = Math.max(0, buttons[index].offsetLeft - (rail.clientWidth - buttons[index].offsetWidth) / 2);
			} else {
				scrollOptions.top = Math.max(0, buttons[index].offsetTop - (rail.clientHeight - buttons[index].offsetHeight) / 2);
			}
			rail.scrollTo(scrollOptions);
		}
	}

	function updateCaption(index) {
		var data = sceneData(index);
		captionCopy.classList.remove('is-revealing');
		captionCopy.classList.add('is-wiping');

		window.setTimeout(function () {
			captionFields.sku.textContent = data.sku;
			captionFields.kicker.textContent = data.kicker;
			captionFields.title.textContent = data.title;
			captionFields.story.textContent = data.story;
			captionFields.size.textContent = data.size;
			captionFields.year.textContent = data.year;
			caption.style.setProperty('--caption-accent', data.accent);
			captionCopy.classList.remove('is-wiping');
			void captionCopy.offsetWidth;
			captionCopy.classList.add('is-revealing');
		}, captionWipeDuration);
	}

	function normalizeScenes() {
		scenes.forEach(function (scene, index) {
			scene.classList.remove('is-active', 'is-before', 'is-after', 'is-entering', 'is-leaving-up', 'is-leaving-down');
			scene.classList.add(index < active ? 'is-before' : index > active ? 'is-after' : 'is-active');
		});
	}

	function finishVisualTransition(target) {
		active = target;
		transitioning = false;
		normalizeScenes();
		updateButtons(active);
		updateCaption(active);
	}

	function changeScene(target) {
		target = Math.max(0, Math.min(target, scenes.length - 1));
		if (transitioning || target === active) return false;

		var current = active;
		var direction = target > current ? 1 : -1;
		var currentScene = scenes[current];
		var targetScene = scenes[target];
		transitioning = true;
		updateButtons(target);

		targetScene.classList.remove('is-before', 'is-after');
		targetScene.classList.add(direction > 0 ? 'is-after' : 'is-before');
		void targetScene.offsetWidth;
		targetScene.classList.add('is-entering');
		currentScene.classList.add(direction > 0 ? 'is-leaving-up' : 'is-leaving-down');

		window.setTimeout(function () {
			finishVisualTransition(target);
		}, visualDuration);
		return true;
	}

	function registerWheelGesture(event) {
		if (viewMode !== 'player' || menu.classList.contains('is-open')) return;
		if (Math.abs(event.deltaY) < 10) return;
		event.preventDefault();

		if (transitioning || Date.now() < cooldownUntil) return;
		if (changeScene(active + (event.deltaY > 0 ? 1 : -1))) {
			cooldownUntil = Date.now() + visualDuration + 70;
		}
	}

	document.addEventListener('wheel', registerWheelGesture, { passive: false, capture: true });

	function isInteractiveTouchTarget(target) {
		return !!(target && target.closest('.painter-archive__chrome, .painter-archive__menu, .painter-archive__coverflow, .painter-archive__wear-actions'));
	}

	function registerTouchStart(event) {
		if (viewMode !== 'player' || menu.classList.contains('is-open') || isInteractiveTouchTarget(event.target)) {
			touchStart = null;
			return;
		}
		var touch = event.touches && event.touches[0];
		if (!touch) return;
		touchStart = {
			x: touch.clientX,
			y: touch.clientY,
			time: Date.now()
		};
	}

	function registerTouchMove(event) {
		if (!touchStart || viewMode !== 'player') return;
		var touch = event.touches && event.touches[0];
		if (!touch) return;
		var deltaX = touch.clientX - touchStart.x;
		var deltaY = touch.clientY - touchStart.y;
		if (Math.abs(deltaY) > 12 && Math.abs(deltaY) > Math.abs(deltaX) * 1.15) {
			event.preventDefault();
		}
	}

	function registerTouchEnd(event) {
		if (!touchStart || viewMode !== 'player' || menu.classList.contains('is-open')) {
			touchStart = null;
			return;
		}
		var touch = event.changedTouches && event.changedTouches[0];
		if (!touch) {
			touchStart = null;
			return;
		}
		var deltaX = touch.clientX - touchStart.x;
		var deltaY = touch.clientY - touchStart.y;
		touchStart = null;
		if (Math.abs(deltaY) < 48 || Math.abs(deltaY) < Math.abs(deltaX) * 1.15) return;
		if (transitioning || Date.now() < cooldownUntil) return;
		if (changeScene(active + (deltaY < 0 ? 1 : -1))) {
			cooldownUntil = Date.now() + visualDuration + 70;
		}
	}

	document.addEventListener('touchstart', registerTouchStart, { passive: true, capture: true });
	document.addEventListener('touchmove', registerTouchMove, { passive: false, capture: true });
	document.addEventListener('touchend', registerTouchEnd, { passive: true, capture: true });

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			changeScene(Number(button.getAttribute('data-scene-target')));
		});
	});

	function setViewMode(mode) {
		viewMode = mode === 'grid' ? 'grid' : 'player';
		root.setAttribute('data-current-view', viewMode);
		document.body.classList.toggle('painter-archive-grid-mode', viewMode === 'grid');
		document.documentElement.classList.toggle('painter-archive-grid-mode', viewMode === 'grid');
		gridView.setAttribute('aria-hidden', viewMode === 'grid' ? 'false' : 'true');
		viewButtons.forEach(function (button) {
			var selected = button.getAttribute('data-view-mode') === viewMode;
			button.classList.toggle('is-active', selected);
			button.setAttribute('aria-pressed', selected ? 'true' : 'false');
		});
		transitioning = false;
		cooldownUntil = 0;
		if (viewMode === 'grid') window.scrollTo(0, 0);
	}

	viewButtons.forEach(function (button) {
		button.addEventListener('click', function () {
			setViewMode(button.getAttribute('data-view-mode'));
		});
	});

	function toggleMenu(open) {
		menu.classList.toggle('is-open', open);
		menu.setAttribute('aria-hidden', open ? 'false' : 'true');
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	toggle.addEventListener('click', function () { toggleMenu(!menu.classList.contains('is-open')); });
	close.addEventListener('click', function () { toggleMenu(false); });
	mailtoLinks.forEach(function (link) {
		link.addEventListener('click', function (event) {
			event.preventDefault();
			var user = link.getAttribute('data-mailto-user') || '';
			var domain = link.getAttribute('data-mailto-domain') || '';
			if (user && domain) window.location.href = 'mailto:' + user + '@' + domain;
		});
	});
	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') toggleMenu(false);
		if (viewMode !== 'player') return;
		if (event.key === 'ArrowDown') changeScene(active + 1);
		if (event.key === 'ArrowUp') changeScene(active - 1);
	});
	window.addEventListener('blur', function () {
		transitioning = false;
		cooldownUntil = 0;
	}, { passive: true });

	normalizeScenes();
	updateButtons(0);
	setViewMode('player');
}());
