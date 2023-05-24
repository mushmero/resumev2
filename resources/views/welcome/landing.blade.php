@extends('layouts.welcome')

@section('content')

    <div id="main-background">
        <img src="{!! url($data['profile-image']) !!}" alt="{{ $data['name'] }}" />
        <h1>Hello There!</h1>
        <h2>You found this site</h2>
        <h2>Unfortunately it is not ready yet</h2>
        <h2>Keep calm and lets wait for it to bloom</h2>
    </div>

@endsection