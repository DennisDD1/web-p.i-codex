(function () {
	'use strict';

	var root = document.querySelector('[data-archive-player]');
	if (!root) return;

	var stage = root.querySelector('[data-archive-viewport]');
	var scenes = Array.prototype.slice.call(root.querySelectorAll('[data-scene]'));
	var buttons = Array.prototype.slice.call(root.querySelectorAll('[data-scene-target]'));
	var caption = root.querySelector('[data-archive-caption]');
	var captionCopy = root.querySelector('[data-caption-copy]');
	var captionFields = {
		number: root.querySelector('[data-caption-number]'),
		kicker: root.querySelector('[data-caption-kicker]'),
		title: root.querySelector('[data-caption-title]'),
		story: root.querySelector('[data-caption-story]'),
		year: root.querySelector('[data-caption-year]')
	};
	var menu = root.querySelector('.painter-archive__menu');
	var toggle = root.querySelector('[data-menu-toggle]');
	var close = root.querySelector('[data-menu-close]');
	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var visualDuration = reducedMotion ? 0 : 780;
	var captionWipeDuration = reducedMotion ? 0 : 230;
	var active = 0;
	var transitioning = false;
	var wheelGestureActive = false;
	var wheelReleaseTimer = 0;
	var wheelReleasePending = false;
	var touchStart = 0;

	function sceneData(index) {
		var scene = scenes[index];
		return {
			number: String(index + 1).padStart(2, '0'),
			kicker: scene.getAttribute('data-scene-kicker') || '',
			title: scene.getAttribute('data-scene-title') || '',
			story: scene.getAttribute('data-scene-story') || '',
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
	}

	function updateCaption(index) {
		var data = sceneData(index);
		captionCopy.classList.remove('is-revealing');
		captionCopy.classList.add('is-wiping');

		window.setTimeout(function () {
			captionFields.number.textContent = data.number;
			captionFields.kicker.textContent = data.kicker;
			captionFields.title.textContent = data.title;
			captionFields.story.textContent = data.story;
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

	function releaseWheelGesture() {
		if (transitioning) {
			wheelReleasePending = true;
			return;
		}
		wheelGestureActive = false;
		wheelReleasePending = false;
	}

	function finishVisualTransition(target) {
		active = target;
		transitioning = false;
		normalizeScenes();
		updateButtons(active);
		updateCaption(active);
		if (wheelReleasePending) releaseWheelGesture();
	}

	function changeScene(target) {
		target = Math.max(0, Math.min(target, scenes.length - 1));
		if (transitioning || target === active) return;

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
	}

	function registerWheelGesture(event) {
		event.preventDefault();
		wheelReleasePending = false;
		window.clearTimeout(wheelReleaseTimer);
		wheelReleaseTimer = window.setTimeout(releaseWheelGesture, 220);

		if (wheelGestureActive || transitioning || Math.abs(event.deltaY) < 12) return;
		wheelGestureActive = true;
		changeScene(active + (event.deltaY > 0 ? 1 : -1));
	}

	stage.addEventListener('wheel', registerWheelGesture, { passive: false });
	stage.addEventListener('touchstart', function (event) {
		touchStart = event.touches[0].clientY;
	}, { passive: true });
	stage.addEventListener('touchend', function (event) {
		var distance = touchStart - event.changedTouches[0].clientY;
		if (!transitioning && Math.abs(distance) > 45) changeScene(active + (distance > 0 ? 1 : -1));
	}, { passive: true });

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			if (!transitioning) changeScene(Number(button.getAttribute('data-scene-target')));
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
		if (!transitioning && event.key === 'ArrowDown') changeScene(active + 1);
		if (!transitioning && event.key === 'ArrowUp') changeScene(active - 1);
	});

	normalizeScenes();
	updateButtons(0);
}());
