<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Youtube Downloader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Youtube Downloader</a>
            </div>
        </div>
    </nav>
    
    <div class="container" style="width: 970px !important;">
        <div class="input-group">
            <input id="youtube-input" class="form-control" type="text" placeholder="enter the link of the video you want to download: e.g http://www.youtube.com/watch?v=bMUxpTb_wWc">
            <span class="input-group-btn">
                <button id="submit-button" class="btn btn-default" type="button">Download</button>
            </span>
        </div>

        <br />

        <div class="row" id="download-info" style="display: none;">
            <div class="col-md-8">
                <div id="video-placeholder"></div>
            </div>

            <div class="col-md-4" style="margin-top: -4px;">
                <h3 id="video-title" class="media-heading"></h3>

                <select id="quality-list" class="form-control" name="quality">
                    <option value="best" selected="selected">Best Quality MP4</option>
                    <option disabled>Loading more options...</option>
                </select>

                <br />

                <div class="btn-group">
                    <a id="download-button" type="button" class="btn btn-success" href="javascript:void(0);">Download 'Best Quality MP4'</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://www.youtube.com/iframe_api"></script>

    <script>
        $(document).on('ready', function() {
            $('#submit-button').on('click', function() {
                var youtubeUrl = $('#youtube-input').val();

                getDownloadable(youtubeUrl);
            });

            $('#quality-list').on('change', function() {
                changeDownloadLink();
            });
        });

        var token = '{{ csrf_token() }}';
        var player = null;
        var availableQualityLevels = [];
        var youtubeId = null;

        function getDownloadable(youtubeUrl) {
            $.ajax({
                url: '/download',
                type: 'GET',
                data: {
                    _token: token,
                    youtube_url: youtubeUrl
                },
                beforeSend: function() {
                    $('#download-info').fadeOut();
                    youtubeId = null;
                }
            }).done(function(jsonResponse) {
                if (jsonResponse.error == 0) {
                    $('#quality-list').html(
                        '<option value="best" selected="selected">Best Quality MP4</option>' +
                        '<option disabled>Loading more options...</option>'
                    );

                    $('#download-button').html('Download \'' + $('#quality-list').find(":selected").text() + '\'');

                    var videoJson = jsonResponse.video;

                    $('#video-title').text(videoJson.snippet.title);
                    $('.video-placeholder').html('');

                    if (player == null) {
                        player = new YT.Player('video-placeholder', {
                            videoId: videoJson.id,
                            playerVars: {
                                color: 'white',
                                autoplay: true
                            },
                            events: {
                                'onStateChange': onPlayerStateChange
                            }
                        });
                    } else {
                        player.loadVideoById(videoJson.id);
                    }

                    youtubeId = videoJson.id;

                    changeDownloadLink();

                    $('#download-info').fadeIn();
                }
            });
        }

        function changeDownloadLink() {
            var downloadLink;

            if (youtubeId == null) {
                downloadLink = 'javascript:void(0);';
            } else {
                downloadLink = '{{ url('/download') }}/' + youtubeId + '?quality=' + $('#quality-list').find(":selected").val();
            }

            $('#download-button').html('Download \'' + $('#quality-list').find(":selected").text() + '\'');
            $('#download-button').attr('href', downloadLink);
        }

        function getQualityName(qualityLevel) {
            switch (qualityLevel) {
                case 'hd2160':
                    return 'UHD 2160p';
                case 'hd1440':
                    return 'UHD 1440p';
                case 'hd1080':
                    return 'HD 1080p';
                case 'hd720':
                    return 'HD 720p';
                case 'large':
                    return '480p';
                case 'medium':
                    return '360p';
                case 'small':
                    return '240p';
                case 'tiny':
                    return '144p';
                default:
                    return null;
            }
        }

        function onPlayerStateChange(event)
        {
            if (player.getAvailableQualityLevels().length > 0) {
                availableQualityLevels = player.getAvailableQualityLevels();

                $('#quality-list').html('<option value="best" selected="selected">Best Quality MP4</option>');

                for (var index in availableQualityLevels) {
                    var availableQualityLevel = availableQualityLevels[index];
                    var namedQualityLevel = getQualityName(availableQualityLevel);

                    if (namedQualityLevel != null) {
                        $('#quality-list').append('<option value="' + availableQualityLevel + '">MP4 (' + namedQualityLevel + ')</option>');
                    }
                }

                player.stopVideo();
            }
        }
    </script>
</body>
</html>