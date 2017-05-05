$(function () {

	// Plyr setup
	var players = plyr.setup({
		enabled: true,
		debug: false,
		autoplay: false,
		loop: false,
		seekTime: 10,
		volume: 8,
		volumeMin: 0,
		volumeMax: 10,
		volumeStep: 1,
		duration: null,
		displayDuration: false,
		loadSprite: true,
		iconPrefix: 'plyr',
		//https://cdn.plyr.io/2.0.11/plyr.svg
		iconUrl: 'typo3conf/ext/tkmedia/Resources/Public/Icons/plyr.svg',
		clickToPlay: true,
		hideControls: true,
		showPosterOnEnd: false,
		disableContextMenu: true,
		keyboardShorcuts: {
			focused: true,
			global: false
		},
		tooltips: {
			controls: false,
			seek: true
		},
		captions: {
			defaultActive: false
		},
		fullscreen: {
			enabled: true,
			fallback: true,
			allowAudio: false
		},
		storage: {
			enabled: true,
			key: 'plyr'
		}
	});

	if (players.length > 0) {
		initPlayers(players);
	}

});

// Init multiple Players
function initPlayers(players) {
	var id = 1;
	players.forEach(function (player) {
		var container = player.getContainer();
		container.setAttribute('id', 'plyId-' + id);
		player.plyId = 'plyr-' + id;
		id++;

		player.on('play', function () {
			var currentPid = player.plyId;
			players.forEach(function (instance) {
				if (currentPid != instance.plyId) {
					instance.pause();
				}
			});
		});

	});
}
