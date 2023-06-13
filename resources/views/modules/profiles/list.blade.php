@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="{{ $table_title }}" theme="default" icon="fas fa-sm fa-user" collapsible>
                <x-slot name="toolsSlot">
                    <a href="{{ route('profiles.create') }}">
                        <x-adminlte-button class="btn-sm btn-flat" label="{{ __('adminlte::adminlte.add_new') }}" theme="success" icon="fas fa-plus"/>
                    </a>
                </x-slot>
                @include('flash::message')
                <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" head-theme="dark" striped hoverable compressed />
            </x-adminlte-card>
        </div>
    </div>
@stop

<script>
function switchAjax(id) {
    var toggle = $('#activeSwitch' + id);
    $.ajax({
        url: 'profiles/countstatus',
        success: function(count){
            if(count > 0){
                $.ajax({
                url: 'profiles/checkStatusById/'+id,
                success: function(data) {
                    if(data.exist ==  1){
                        Swal.fire({
                            title: 'Are you sure to deactivate?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'profiles/updateStatusById/'+id,
                                    data: {
                                        '_token': '{{ csrf_token() }}',
                                        'status':0,
                                        'id' : id
                                        },
                                    success: function() {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Status updated. Please activate new profile'
                                        });
                                        toggle.next('.active_switch').text('Inactive');
                                        /**setTimeout(function() {
                                            location.reload();
                                        }, 3000);*/
                                    },
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire('Cancelled', 'Record not updated', 'error');
                                toggle.prop('checked',true);
                            }
                        });
                    }else{
                        Swal.fire({
                            'icon': 'warning',
                            'title': 'You have active profile. Please deactivate first.',
                        });
                        toggle.prop('checked',false);
                    }
                }
                });
            }else{
                Swal.fire({
                    title: 'Are you sure to activate?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'profiles/updateStatusById/'+id,
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'status': 1,
                                'id' : id
                                },
                            success: function() {
                                Swal.fire({
                                icon: 'success',
                                title: 'Profile updated.'
                                });
                                toggle.next('.active_switch').text('Active');
                                /**setTimeout(function() {
                                    location.reload();
                                }, 3000);*/
                            },
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Cancelled', 'Record not updated', 'error');
                        toggle.prop('checked',false);
                    }
                });
            }
          
        }
    });
}</script>