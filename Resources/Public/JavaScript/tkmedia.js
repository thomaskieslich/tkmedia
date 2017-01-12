$(function () {

    // Plyr setup
    var players = plyr.setup({
        enabled: true,
        debug: false,
        autoplay: false,
        volume: 2,
        // html: controls,
        // controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
        disableContextMenu: false,
        tooltips: {
            controls: false,
            seek: false
        },
        fullscreen: {
            enabled: true,
            fallback: true,
            allowAudio: false
        }
    });

    if (players.length) {
        initPlayers(players);
    }

});

// Init multiple Players
function initPlayers(players) {
    var id = 1;
    //Buttons for Playlists
    var prev = [
        "<button type='button' data-plyr='prev'>",
        "<svg><use xlink:href='#plyr-rewind'></use></svg>",
        "<span class='plyr__sr-only'>Previous Title</span>",
        "</button>"
    ].join("");

    var next = [
        "<button type='button' data-plyr='next'>",
        "<svg><use xlink:href='#plyr-fast-forward'></use></svg>",
        "<span class='plyr__sr-only'>Next Title</span>",
        "</button>"
    ].join("");


    players.forEach(function (player) {

        var container = player.getContainer();
        container.setAttribute('id', 'plyId-' + id);
        player.plyId = 'plyr-' + id;
        id++;

        player.on('ready', function () {
            if ($('audio, video', container).attr('title')) {
                var title = $('audio, video', container).attr('title');
                setTitle(container, title);
            }
        });

        player.on('play', function (event) {
            var currentPid = player.plyId;
            players.forEach(function (instance) {
                if (currentPid != instance.plyId) {
                    instance.pause();
                }
            });
        });

        //Playlist
        if ($(container).next('.playlist').length > 0) {
            //build list
            var pl = $(container).next('.playlist');
            var titles = [];
            $('li', pl).each(function () {
                var title = {};
                title.mime = $(this).data('mime');
                title.src = $(this).data('src');
                title.title = $(this).data('title');
                titles.push(title);
            });

            var titlesTotal = titles.length;
            var titlesCurrent = 0;

            //Buttons
            $('.plyr__progress', container).before(prev);
            $('.plyr__progress', container).after(next);

            var buttonPrev = $('button[data-plyr=prev]', container);
            var buttonNext = $('button[data-plyr=next]', container);
            $(buttonNext).on('click', function () {
                if (titlesCurrent < titlesTotal - 1) {
                    titlesCurrent++;
                    changeSource(player, titles[titlesCurrent]);
                    setTitle(container, titles[titlesCurrent].title);
                }
            });

            $(buttonPrev).on('click', function () {
                if (titlesCurrent > 0) {
                    titlesCurrent--;
                    changeSource(player, titles[titlesCurrent]);
                    setTitle(container, titles[titlesCurrent].title);
                }
            });
        }

    });
}

//Change Player Src
function changeSource(player, source) {
    player.pause();
    player.source({
        title: source.title,
        sources: [{
            src: source.src,
            type: source.mime
        }]
    });
    player.play();
}

// Change Playlist Title
function setTitle(container, title) {
    if (!$('.title', container).length) {
        $('.plyr__progress', container).prepend('<div class="title">' + title + '</div>');
    } else {
        $('.title', container).text(title);
    }
}
