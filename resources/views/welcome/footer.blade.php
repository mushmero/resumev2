<!-- Footer -->
<div id="footer">

    <h1>{{ $data['name'] }}</h1>
    <h4>You've made it this far. Check out the links below.</h4>
    <hr />
    <h4>
    @if (!empty($data['socials']))
        @foreach ($data['socials'] as $social)
            <a href ="{{ $social->link }}" target="_blank" title="{{ $social->name }}"><i class="{{ $social->icons->fullname }} fa-2x"></i></a>
        @endforeach        
    @else
        None
    @endif  
    </h4>
</div>
@if (env('APP_ENV') == 'local')
<div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) | 
    @auth
    <a href="{{ route('home') }}" target="_blank">Admin</a>
    @else
    <a href="{{ route('login') }}" target="_blank">Login</a>
    @endauth
</div>    
@endif
<!-- End Footer -->