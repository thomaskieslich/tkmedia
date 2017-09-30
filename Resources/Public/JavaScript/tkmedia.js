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
        iconUrl: '/typo3conf/ext/tkmedia/Resources/Public/Icons/plyr.svg',
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
                if (currentPid !== instance.plyId) {
                    instance.pause();
                }
            });
        });
        var containerWidth = $(container).width();

        if (containerWidth <= 290) {
            player.on('ready', function (event) {
                var target = event.target;
                $('.plyr__controls [data-plyr="play"]', target).hide();
                $('.plyr__controls [data-plyr="pause"]', target).hide();
                $('.plyr__controls [data-plyr="mute"]', target).hide();
                $('.plyr__controls .plyr__progress', target).hide();
                $('.plyr__controls .plyr__time', target).hide();
                $('.plyr__controls .plyr__volume', target).hide();
            });

            player.on('enterfullscreen', function (event) {
                var target = event.target;
                $('.plyr__controls [data-plyr="play"]', target).show();
                $('.plyr__controls [data-plyr="pause"]', target).show();
                $('.plyr__controls [data-plyr="mute"]', target).show();
                $('.plyr__controls .plyr__progress', target).show();
                $('.plyr__controls .plyr__time', target).show();
                $('.plyr__controls .plyr__volume', target).show();
            });

            player.on('exitfullscreen', function (event) {
                var target = event.target;
                $('.plyr__controls [data-plyr="play"]', target).hide();
                $('.plyr__controls [data-plyr="pause"]', target).hide();
                $('.plyr__controls [data-plyr="mute"]', target).hide();
                $('.plyr__controls .plyr__progress', target).hide();
                $('.plyr__controls .plyr__time', target).hide();
                $('.plyr__controls .plyr__volume', target).hide();
            });
        }

    });
}
