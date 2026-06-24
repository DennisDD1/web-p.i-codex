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
	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var captionWipeDuration = reducedMotion ? 0 : 180;
	var active = 0;
	var ticking = false;
	var viewMode = 'player';

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
			scene.classList.toggle('is-active', index === active);
			scene.classList.toggle('is-before', index < active);
			scene.classList.toggle('is-after', index > active);
		});
	}

	function setActive(index, withCaption) {
		index = Math.max(0, Math.min(index, scenes.length - 1));
		if (index === active) return;
		active = index;
		normalizeScenes();
		updateButtons(active);
		if (withCaption !== false) updateCaption(active);
	}

	function nearestSceneIndex() {
		var targetLine = window.innerHeight * 0.42;
		var closest = active;
		var closestDistance = Infinity;
		scenes.forEach(function (scene, index) {
			var rect = scene.getBoundingClientRect();
			var distance = Math.abs(rect.top - targetLine);
			if (distance < closestDistance) {
				closest = index;
				closestDistance = distance;
			}
		});
		return closest;
	}

	function scheduleActiveFromScroll() {
		if (viewMode !== 'player' || ticking) return;
		ticking = true;
		window.requestAnimationFrame(function () {
			ticking = false;
			setActive(nearestSceneIndex());
		});
	}

	function scrollToScene(index) {
		index = Math.max(0, Math.min(index, scenes.length - 1));
		scenes[index].scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'start' });
		setActive(index);
	}

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			scrollToScene(Number(button.getAttribute('data-scene-target')));
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
		if (viewMode === 'grid') {
			window.scrollTo({ top: 0, behavior: 'auto' });
		} else {
			scrollToScene(active);
		}
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
	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') toggleMenu(false);
		if (viewMode !== 'player') return;
		if (event.key === 'ArrowDown') scrollToScene(active + 1);
		if (event.key === 'ArrowUp') scrollToScene(active - 1);
	});
	window.addEventListener('scroll', scheduleActiveFromScroll, { passive: true });
	window.addEventListener('resize', scheduleActiveFromScroll, { passive: true });

	normalizeScenes();
	updateButtons(0);
	setViewMode('player');
}());
