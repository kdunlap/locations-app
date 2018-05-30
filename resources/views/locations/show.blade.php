@extends('layout')

@section('content')
    <div id="map"></div>
@endsection

@push('footer-scripts')
    <script>

        function initMap() {

            var myLatLng = { lat: {{ $center_city->latitude }}, lng: {{ $center_city->longitude }} };

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: myLatLng,
                scrollwheel: false
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: '{{ $center_city->name }}'
            });

            var cityCircleInner = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map,
                center: myLatLng,
                radius: 30000
            });

            var cityCircleOuter = new google.maps.Circle({
                strokeColor: '#3366cc',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#3366cc',
                fillOpacity: 0.35,
                map: map,
                center: myLatLng,
                radius: 60000
            });

            var spreadMarkers = [];
        @foreach( $all_cities as $idx => $city )
            @if( $spread_cities->contains( function( $value, $key ) use( $city ){ return $value->id == $city->id; } ) )

                spreadMarkers[{{ $idx }}] = new google.maps.Marker({
                    position: {lat: {{ $city->latitude }}, lng: {{ $city->longitude }} },
                    map: map,
                    title: '{{ $city->name }}',
                    icon: 'https://maps.gstatic.com/mapfiles/ms2/micons/red-dot.png',
                    url: '{{ route( 'locations.show', [ $city->state->slug, $city->slug ] ) }}'
                });
                google.maps.event.addListener(spreadMarkers[{{ $idx }}], 'click', function() {
                    window.location.href = this.url;
                });
            @else
                new google.maps.Marker({
                    position: {lat: {{ $city->latitude }}, lng: {{ $city->longitude }} },
                    map: map,
                    title: '{{ $city->name }}',
                    icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
                });
            @endif
        @endforeach
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuVYUVnaC3qold3_yT8koyBa6BjAQ1hoM&callback=initMap">
    </script>
@endpush