@extends('app')
@section('content')
    <div style="background-image: url('img/back.svg');background-repeat: no-repeat; width: 90%; height: 100%; margin-top: 20px; margin-bottom: 20px; margin-left: 67px;">
        <a style="width: 77px;height: 77px;display:block;float:right" href="{{ route('signout') }}"></a>
        <div id="map" style="height:428px;width:1000px;position:absolute !important;bottom:0;overflow: hidden;margin-left: 358px;margin-bottom: 200px;display:block;"></div>
        <btn id="sendAnswer" style="position:absolute !important; margin-top:617px; margin-left:1264px; color: white; font-size:160%;">Trimite<br>solutie</btn>
        <div style="position:absolute !important; color: white; font-size:280%; margin-top:516px; margin-left:90px;">Scor:</div>

        <div id="leaderboardtext" style="color:white; position:absolute !important; font-size:200%; margin-top:0px;margin-left:0px; z-index: 1;margin-top:117px; margin-left:47px">Leaderboard</div>
        <div id="leaderback" style="background-image: url('img/leaderback.svg'); background-repeat: no-repeat; position:absolute; !important; margin-left:11px; margin-top:110px; width: 18%; height: 100%"></div>
        <div id="leaderboard" style="position: absolute; !important; color:white; font-size:90%; height: 335px; width: 281px;margin-top: 187px; margin-left:12px;"></div>
        <div id="logo" style="background-image: url('img/logo.svg');background-repeat: no-repeat; height: 79px; position: absolute; width: 304px;"></div>
        <div id="scor" style="position:absolute !important; color: white; font-size:280%; margin-top:589px; margin-left:90px;"></div>
    </div>
    <div id="quiz" style="position:absolute !important; color: white; font-size: 170%; margin-top:-156px; margin-left: 445px;"></div>
@endsection

@section('specificFooter')
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRr85trF2DPDrSjrbbNYF5oOCrGpf8SA0&callback=initMap&v=weekly"
        async></script>
    <script>
        var latitudeAns = "";
        var longitudeAns = "";
        var score = 0;
        var isFirstQuestionInit = false;
        // Initialize and add the map
        var marker = false;
        function initMap() {
            // The location of Uluru
            const uluru = {lat: 40.866667, lng: 34.566667};
            const markerImage = {
                url: "img/checkpoint.svg",
                scaledSize: new google.maps.Size(50, 50)
            }
            // The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                mapId: "6102c8d0d2be64a0",
                zoom: 2.3,
                center: uluru,
                disableDefaultUI: true,
                zoomControl: true,
            });
            // The marker, positioned at Uluru

            google.maps.event.addListener(map, 'click', function (event) {
                if (!isFirstQuestionInit) {
                    return;
                }
                //Get the location that the user clicked.
                var clickedLocation = event.latLng;
                console.log(clickedLocation)
                //If the marker hasn't been added.
                if (marker === false) {
                    //Create the marker.
                    marker = new google.maps.Marker({
                        position: clickedLocation,
                        icon: markerImage,
                        map: map,
                        draggable: true //make it draggable
                    });
                    //Listen for drag events!
                    google.maps.event.addListener(marker, 'dragend', function (event) {
                        markerLocation();
                    });
                } else {
                    //Marker has already been added, so just change its location.
                    marker.setPosition(clickedLocation);
                }
                //Get the marker's location.
                markerLocation();
            });

            //This function will get the marker's current location and then add the lat/long
            //values to our textfields so that we can save the location.
            function markerLocation() {
                //Get location.
                var currentLocation = marker.getPosition();
                //Add lat and lng values to a field that we can save.
                latitudeAns = currentLocation.lat();
                longitudeAns = currentLocation.lng();
                console.log("lat:" + currentLocation.lat() + " lng:" + currentLocation.lng());
            }
        }

        function nextQuestion() {
            marker = false;
            loader("We are looking for new challenges!");
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                data: {
                    "longitude": latitudeAns,
                    "latitude": longitudeAns,
                },
                url: "{{ route('nextQuestion') }}",
                contentType: false,
                processData: false,
                success: function (request, e, p) {
                    isFirstQuestionInit = true;
                    console.log(request);
                    $("#quiz").html(request.data.question)
                },
                error: function (request) {
                    console.log(request);
                }
            }).done(function () {
                Swal.close();
            });
        }

        function initQuestions() {
            nextQuestion();
        }

        function loader(text) {
            Swal.fire({
                title: "Processing",
                text: text,
                imageUrl: "/img/loading.svg",
                showConfirmButton: false,
                allowOutsideClick: false
            });
        }

        function loadLeaderBoard() {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                url: "{{ route('getLeaderBoard') }}",
                processData: false,
                success: function (request, e, p) {
                    console.log(request);
                    var leaderBoard = "";
                    var data = request.data;
                    for(var i = 0; i < data.length; ++i) {
                        leaderBoard += "<div><span>" + data[i].email + " - " + data[i].score + "</span></div><br>"
                    }
                    $("#leaderboard").html(leaderBoard);
                },
                error: function (request) {
                    console.log(request);
                }
            });
        }

        function updateScore(score) {
            $('#scor').html(score);
        }

        $(document).ready(function () {
            //initMap();
            initQuestions();
            updateScore(score);
            loadLeaderBoard();
            $('#sendAnswer').on('click', function () {
                if (!isFirstQuestionInit) {
                    return;
                }
                if (latitudeAns === "" || longitudeAns === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: "Warning",
                        text: "You have to point on map first!",
                        showConfirmButton: true,
                    });
                    return;
                }
                loader("Your answer is processing!");
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    data: JSON.stringify({
                        "longitude": latitudeAns,
                        "latitude": longitudeAns,
                    }),
                    url: "{{ route('postSendAnswer') }}",
                    processData: false,
                    success: function (request, e, p) {
                        console.log(request.score);
                        score += request.score;
                        updateScore(score);
                        if (request.isEndGame === false) {
                            nextQuestion();
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: "Congratulation!",
                                text: "You rock it!",
                                confirmButtonText: 'Play again',
                                showConfirmButton: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                        console.log(request);
                    },
                    error: function (request) {
                        Swal.close();
                        console.log(request);
                    }
                });
                loadLeaderBoard();
            });
        });
    </script>
@endsection
