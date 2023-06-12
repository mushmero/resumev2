@extends('layouts.welcome')

@section('content')

    <div id="main-background">
        <div class="flash-message">
            @include('flash::message')
        </div>
        <img src="{!! url($data['profile-image']) !!}" alt="{{ $data['name'] }}" />
        <h1>{{ $data['name'] }}</h1>
        <h2>{{ $data['position'] }}</h2>
    </div>

    <div id="about">
        <div id="about-header">
            <h1>Know {{ $data['name'] }} more</h1>
        </div>
        <div id="about-me">
            <figure>
                <img src="https://picsum.photos/id/373/4896/3264" alt="" />
                <figcaption>
                    Reach me <br>
                    @if (!empty($data['email']))
                        <a href="mailto:{{ $data['email'] }}" target="_blank"><i class="fas fa-fw fa-at"></i> {{ $data['email'] }}</a>
                    @else
                        None
                    @endif                    
                    <br>
                    @if (!empty($data['phone']))
                        <a href="tel:{{ $data['phone'] }}" target="_blank"><i class="fas fa-fw fa-phone"></i> tel:{{ $data['phone'] }}</a>
                    @else
                        None
                    @endif
                </figcaption>
            </figure>
            <figure>
                <img src="https://picsum.photos/id/389/4562/3042" alt="" />
                <figcaption>
                    I study <br>
                    @if (!empty($data['educations']))
                        @foreach($data['educations'] as $education)
                            {{ $education->name.' '.$education->institution.' ('.$education->start.'-'.$education->end.')' }}
                        @endforeach
                    @else
                        None
                    @endif
                </figcaption>
            </figure>
            <figure>
                <img src="https://picsum.photos/id/791/5000/3333" alt="" />
                <figcaption>
                    I speak and listen to <br>
                    @if (!empty($data['languages']))
                        @foreach ($data['languages'] as $language)
                            {{ $language->name.' ('.$language->proficiency->proficiency.')' }} <br>
                        @endforeach                        
                    @else
                        None
                    @endif
                </figcaption>
            </figure>
            <figure>
                <img src="https://picsum.photos/id/668/4133/2745" alt="" />
                <figcaption>
                    Visit me 
                    @if (!empty($data['website']))
                        <a href="{{ $data['website'] }}" target="_blank">{{ $data['website'] }}</a>                        
                    @else
                        None
                    @endif
                </figcaption>
            </figure>
        </div>

    </div>

    <div id="experiences">
        <div id="experiences-header">
            <h1>Who I have been worked for?</h1>
        </div>
        <div id="experiences-me">
            @if (!empty($data['experiences']))
                @foreach ($data['experiences'] as $experience)
                    <figure>
                        <div>{{ $experience->employer }} ({{ $experience->position }})</div>
                        <figcaption>
                            <center><i class="fas fa-fw fa-calendar-day"></i> {{ $experience->start }} - {{ $experience->end }} </center>
                            <br>
                            {!! $experience->description !!}
                        </figcaption>
                    </figure>
                @endforeach                
            @else
                None
            @endif
        </div>
    </div>

    <div id="skills">
        <div id="skills-header">
            <h1>Skills forte</h1>
        </div>
        <div id="skills-me">
            @if (!empty($data['skills']))
                @foreach ($data['skills'] as $skill)
                    <skill-block>
                        <i class="{{ $skill->icons->fullname }} fa-8x"></i> <span>{{ $skill->name }}</span>
                    </skill-block>                
                @endforeach                
            @else
                None
            @endif
        </div>
    </div>

    <div id="projects">
        <div id="projects-header">
            <h1>Hobbies & Projects</h1>
            <h3>Leisure time bobbies & projects</h3>
        </div>
        <div id="projects-me">
            @if (!empty($data['projects']))
                @foreach ($data['projects'] as $project)
                    <figure>
                        <figcaption>
                            <center><h4>{{ $project['name'] }}</h4></center>
                            @if (!empty($project['description']))
                                <br> {!! $project['description'] !!}
                            @endif
                        </figcaption>
                    </figure>            
                @endforeach            
            @else
                None
            @endif
        </div>
    </div>
@endsection

@section('footer')
    @include('welcome.footer')
@endsection