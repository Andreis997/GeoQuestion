@extends('app')
@section('content')
    <!--<nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="#">GeoQuestions</a>
        <a class="navbar-brand" href="{{ route('signout') }}">
            <img src="img/butonprofil.svg" width="30px" height="30px">
        </a>
        <btn class="btn btn-success" id="sendAnswer">Trimite solutie</btn>
    </nav>
    <div id="map" style="height:500px;width:1000px;position:fixed !important;bottom:0;"></div>-->
    <div style="background-image: url('img/back.svg');background-repeat: no-repeat; width: 90%; height: 100%; margin-top: 20px; margin-bottom: 20px; margin-left: 67px;">
        <div style="position:absolute !important; color: white;margin-top: 3px;font-size:270%;margin-left:4px;">Geo</div>
        <div style="position:absolute !important; color: white;margin-top: 3px;font-size:270%;margin-left:124px;">uizzes</div>
        <a style="width: 77px;height: 77px;display:block;float:right" href="{{ route('signout') }}"></a>
        <div id="map" style="height:428px;width:1000px;position:absolute !important;bottom:0;overflow: hidden;margin-left: 358px;margin-bottom: 200px;display:block;"></div>
        <btn id="sendAnswer" style="position:absolute !important; margin-top:617px; margin-left:1264px; color: white; font-size:160%;">Trimite<br>solutie</btn>
        <div style="position:absolute !important; color: white; font-size:280%; margin-top:516px; margin-left:90px;">Scor:</div>

        <div id="leaderboard" style="position: absolute; !important; height: 369px; width: 298px;margin-top: 97px"></div>
        <div id="quiz" style="position:absolute !important; color: white; font-size: 200%; padding-top:632px; padding-left: 425px;"></div>
        <div id="scor" style="position:absolute !important; color: white; font-size:280%; margin-top:589px; margin-left:90px;"></div>
    </div>
@endsection

@section('specificFooter')
    <script>
        var latitudeAns = "";
        var longitudeAns = "";
        var score = 0;
        // Initialize and add the map
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
            var marker = new google.maps.Marker({
                icon: markerImage,
                map: map,
            });
            google.maps.event.addListener(map, 'click', function (event) {
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
            initQuestions();
            updateScore(score);
            loadLeaderBoard();
            $('#sendAnswer').on('click', function () {
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
                                showConfirmButton: true,
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
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRr85trF2DPDrSjrbbNYF5oOCrGpf8SA0&callback=initMap&v=weekly"
        async></script>
@endsection
