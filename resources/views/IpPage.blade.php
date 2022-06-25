<h1 style="text-align: center;">5 Day Weather Forcast for {{ $city }} , {{ $country }}</h1>

<form method="get" action="{{ url('/') }}" style="text-align: center;">
    <input type="text" value="{{ $ip }}" name="customip" />
    <input type="submit" value="Submit">
</form>


@if( $city != null )
@foreach ( json_decode( $weather["weatherdata"]) as $weatherdata )
<div style="width: 180px; float: left;border: 2px solid grey; margin: 20px; padding: 20px;">
    <img src="http://openweathermap.org/img/w/{{ $weatherdata->icon }}.png">
    <br/>
    {{ $weatherdata->day }}
    <br/>
    {{ $weatherdata->description }}
</div>

@endforeach

@else
<p>Sorry, there's no weather data for your location</p>
@endif


