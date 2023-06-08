@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop
@section('plugins.TempusDominusBs4', true)

@section('content')
    <div class="row">
        <div class="col-12">
            @include('flash::message')
            <form class="form" action="{{ route('educations.update',$data->id) }}" method="post">
                @method('put')
                @csrf
                <x-adminlte-card title="{{ $table_title }} {{ $data->name }}" theme="default" icon="fas fa-sm fa-archive" collapsible>
                    <x-adminlte-input name="name" label="Education Name" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->name }}" required enable-old-support/>
                    <x-adminlte-input name="institution" label="Institution" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" value="{{ $data->institution }}" enable-old-support/>
                    <x-adminlte-input-date name="startDate" label="From" fgroup-class="col-md-6 row" label-class="col-md-2 control-label" igroup-class="col-md-10" :config="$startDate" placeholder="Choose start date..." value="{{ $data->start }}">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-input-date name="endDate" label="To" fgroup-class="row col-md-6" label-class="col-md-2 control-label" igroup-class="col-md-10" :config="$endDate" placeholder="Choose end date..." value="{{ $data->end }}">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                    <x-adminlte-select2 id="level" name="level" label="Level" fgroup-class="col-md-6 row" label-class="col-md-2 control-label"  igroup-class="col-md-10" igroup-size="md" :config="$configEducations" enable-old-support>
                        <option/>
                        @foreach ($educations as $edu)
                            <option value="{{ $edu->id }}" {{ $data->level_id == $edu->id ? "selected" : "" }}>{{ $edu->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-slot name="footerSlot">
                        <a href="{{ route('educations') }}">
                            <x-adminlte-button class="btn-flat" label="{{ __('adminlte::adminlte.back') }}" theme="default" icon="fas fa-chevron-left"/>
                        </a>
                        <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </div>
    </div>
@stop