<!DOCTYPE html>

<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="assets/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.nouislider.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/colors/brown.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/user.style.css') }}" type="text/css">

    <title>Youtube Downloader</title>

</head>

<body onunload="" class="map-fullscreen page-homepage navigation-off-canvas" id="page-top">

<!-- Outer Wrapper-->
<div id="outer-wrapper">
    <!-- Inner Wrapper -->
    <div id="inner-wrapper">
        <!-- Navigation-->
        <div class="header">
            <div class="wrapper">
                <div class="brand">
                    <a href="/"><img src="{{ asset('assets/img/savevid-logo.png') }}" alt="logo"></a>
                </div>
                <nav class="navigation-items">
                    <div class="wrapper">
                        <ul class="main-navigation navigation-top-header"></ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- end Navigation-->
        <!-- Page Canvas-->
        <div id="page-canvas">
            <!--Off Canvas Navigation-->
            <nav class="off-canvas-navigation">
                <header>Navigation</header>
                <div class="main-navigation navigation-off-canvas"></div>
            </nav>
            <!--end Off Canvas Navigation-->
            <!--Page Content-->
            <div id="page-content">
                <!--Hero Image-->
                <section class="hero-image search-filter-middle height-500">
                    <div class="inner">
                        <div class="container">
                            <div class="alert alert-info" id="notification" style="display: none;"></div>

                            <h1>YouTube Downloader</h1>

                            <div class="search-bar horizontal">
                                <form class="main-search border-less-inputs background-dark narrow">
                                    <div class="input-row">
                                        <div class="form-group">
                                            <label for="keyword">YouTube Link</label>
                                            <input type="text" class="form-control" id="youtube-link" authcomplete="off" placeholder="link of video: e.g http://youtube.com/watch?v=bMUxpTb_wWc">
                                        </div>
                                        <!-- /.form-group -->
                                        <div class="form-group">
                                            <label for="model">Quality</label>
                                            <select name="model" id="quality-list" data-live-search="true" disabled="disabled">
                                                <option disabled>Enter a YouTube URL first...</option>
                                            </select>
                                        </div>
                                        <!-- /.form-group -->
                                        <div class="form-group">
                                            <a id="download-link" class="btn btn-default" style="margin-top: 25px;" href="javascript:void(0);" disabled>
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                </form>
                                <!-- /.main-search -->
                            </div>
                            <!-- /.search-bar -->
                        </div>
                    </div>
                    <div class="background">
                        <img src="http://yourtubetheme.com/wp-content/uploads/2013/12/red-hot-youtube-channel-art-980x551.jpg" alt="">
                    </div>
                </section>
                <!--end Hero Image-->

                <!--Popular-->
                <section class="block background-color-white">
                    <div class="container">
                        <header><h2>Information</h2></header>
                        <div id="generic-info">Get started by entering a Youtube URL in the field above.</div>
                        <div id="download-info" style="display: none;">
                            <style>
                                .videowrapper {
                                    float: none;
                                    clear: both;
                                    width: 100%;
                                    position: relative;
                                    padding-bottom: 56.25%;
                                    padding-top: 25px;
                                    height: 0;
                                }
                                .videowrapper iframe {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    width: 100%;
                                    height: 100%;
                                }
                            </style>

                            <div class="col-md-6" style="padding-left: 0px;">
                                <div class="videowrapper">
                                    <div id="video-placeholder"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wrapper">
                                    <a href="item-detail.html"><h2 style="margin-top: 0px; margin-bottom: 20px;" id="video-title">Loading title...</h2></a>
                                    <figure style="margin-bottom: 15px;">
                                        <i class="fa fa-user"></i>
                                        <span>Posted by <strong id="video-author">Loading author...</strong> in <strong id="video-category">Loading category...</strong></span>
                                    </figure>
                                    <!--/.info-->
                                    <p id="video-description">Loading description...</p>
                                    <a class="read-more icon" href="javascript:void(0);" id="video-link" target="_blank">View the video</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/.container-->
                </section>
                <!--end Popular-->
            </div>
        </div>
    </div>
    <!-- end Inner Wrapper -->
</div>
<!-- end Outer Wrapper-->

<script type="text/javascript" src="{{ asset('assets/js/jquery-2.1.0.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/before.load.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery-migrate-1.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/smoothscroll.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.hotkeys.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.nouislider.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script>
<script src="https://www.youtube.com/iframe_api"></script>

<script>
    $(document).on('ready', function() {
        $('#quality-list').attr('disabled', true).selectpicker('refresh');
        $('#youtube-link').val('');

        $('#youtube-link').on('keyup', function() {
            var userInput = $(this).val();

            if (isValidYoutubeURL(userInput)) {
                clearTimeout(notification);
                $('#notification').fadeOut(300);

                getDownloadable(userInput);
            } else {
                if (userInput.length > 20) {
                    $('#notification').html('<strong>Info!</strong> Please enter a valid YouTube URL');

                    $('#notification').fadeIn(300, function() {
                        notification = setTimeout(function() {
                            $('#notification').fadeOut(300);
                        }, 5000);
                    });
                }

                $('#download-info').fadeOut();
                $('#quality-list').html('<option disabled>Enter a YouTube URL first...</option>').attr('disabled', true).selectpicker('refresh');
                $('#download-link').attr('disabled', true).attr('href', 'javascript:void(0);');
            }
        });

        $('#quality-list').on('change', function() {
            changeDownloadLink();
        });

        $('#download-link').on('click', function() {
            $(this).html('<i class="fa fa-refresh fa-spin"></i>');
        });

        $(window).blur(function() {
            $('#download-link').html('<i class="fa fa-download"></i>');
        });
    });

    var token = '{{ csrf_token() }}';
    var notification = null;
    var player = null;

    function getDownloadable(youtubeUrl) {
        $.ajax({
            url: '{{ url('download') }}',
            type: 'GET',
            data: {
                _token: token,
                youtube_url: youtubeUrl
            },
            beforeSend: function() {
                $('#download-info').fadeOut();
                $('#quality-list').html('<option disabled>Loading qualities...</option>').attr('disabled', true).selectpicker('refresh');
                $('#download-link').attr('disabled', true).attr('href', 'javascript:void(0);');

                youtubeId = null;
            }
        }).done(function(jsonResponse) {
            if (jsonResponse.error == 0) {
                var videoJson = jsonResponse.video;
                var availableQualities = jsonResponse.qualities;
                youtubeId = videoJson.id;

                $('.video-placeholder').html('');

                changeDownloadInfo(videoJson);
                changeQualityOptions(availableQualities)
                changeDownloadLink();

                if (player == null) {
                    player = new YT.Player('video-placeholder', {
                        videoId: videoJson.id,
                        playerVars: {
                            color: 'white'
                        }
                    });
                } else {
                    player.loadVideoById(videoJson.id);
                }

                $('#generic-info').fadeOut(300, function() {
                    $('#download-info').fadeIn();
                });
            }
        });
    }

    function changeQualityOptions(arrayOfQualities) {
        var qualityList = $('#quality-list');

        qualityList.html('').removeAttr('disabled').selectpicker('refresh');

        for (var index in arrayOfQualities) {
            var availableQuality = arrayOfQualities[index];

            qualityList.append('<option>' + availableQuality + '</option>');
        }

        qualityList.val($("#quality-list option:first").val()).selectpicker('refresh');
    }

    function changeDownloadInfo(downloadInfo) {
        var videoTitle = downloadInfo.snippet.title;
        var videoDescription = downloadInfo.snippet.description;
        var videoAuthor = downloadInfo.snippet.channelTitle;
        var videoCategory = getCategoryNameFromId(downloadInfo.snippet.categoryId);
        var videoLink = 'https://youtube.com/watch?v=' + downloadInfo.id;

        $('#video-title').html(videoTitle);
        $('#video-description').html(videoDescription);
        $('#video-author').html(videoAuthor);
        $('#video-category').html(videoCategory);
        $('#video-link').attr('href', videoLink);
    }

    function changeDownloadLink() {
        var downloadLink;

        if (youtubeId == null) {
            downloadLink = 'javascript:void(0);';
        } else {
            var downloadInformation = $('#quality-list').find(":selected").val().split(' ');

            var format = downloadInformation[0];
            var quality = downloadInformation[1];

            downloadLink = '{{ url('download') }}/' + youtubeId + '?quality=' + quality + '&format=' + format;
        }

        $('#download-link').attr('href', downloadLink);
        $('#download-link').removeAttr('disabled');
    }

    function getCategoryNameFromId(categoryId) {
        var categories = {
            '1': 'Film & Animation',
            '2': 'Autos & Vehicles',
            '10': 'Music',
            '15': 'Pets & Animals',
            '17': 'Sports',
            '18': 'Short Movies',
            '19': 'Travel & Events',
            '20': 'Gaming',
            '21': 'Videoblogging',
            '22': 'People & Blogs',
            '23': 'Comedy',
            '24': 'Entertainment',
            '25': 'News & Politics',
            '26': 'Howto & Style',
            '27': 'Education',
            '28': 'Science & Technology',
            '29': 'Nonprofits & Activism',
            '30': 'Movies',
            '31': 'Anime/Animation',
            '32': 'Action/Adventure',
            '33': 'Classics',
            '34': 'Comedy',
            '35': 'Documentary',
            '36': 'Drama',
            '37': 'Family',
            '38': 'Foreign',
            '39': 'Horror',
            '40': 'Sci-Fi/Fantasy',
            '41': 'Thriller',
            '42': 'Shorts',
            '43': 'Shows',
            '44': 'Trailers',
        };

        return categories[categoryId];
    }

    function isValidYoutubeURL(url) {
        if (url != undefined || url != '') {
            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
            var match = url.match(regExp);
            if (match && match[2].length == 11) {
                return true;
            }
        }

        return false;
    }
</script>

<!--[if lte IE 9]>
<script type="text/javascript" src="assets/js/ie-scripts.js"></script>
<![endif]-->
</body>
</html>