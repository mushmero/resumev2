@extends('lapdash::page')

@section(config('adminlte.title'), 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="{{ $table_title }}" theme="default" icon="fas fa-sm fa-wrench" collapsible>
                <x-slot name="toolsSlot">
                    <a href="{{ route('icons.create') }}">
                        <x-adminlte-button class="btn-sm btn-flat" label="{{ __('adminlte::adminlte.add_new') }}" theme="success" icon="fas fa-plus"/>
                    </a>
                    <x-adminlte-button class="btn-sm btn-flat" label="{{ __('adminlte::adminlte.import') }}" theme="success" icon="fas fa-upload" onclick="importModal()"/>
                </x-slot>
                @include('flash::message')
                <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" head-theme="dark" striped hoverable compressed />
            </x-adminlte-card>
        </div>
    </div>
@stop
<script>
function importModal(){
    (async () => {
        const { value: file } = await Swal.fire({
            title: 'Import {{ $title }}',
            html: 'Support json file only <br>Please follow this <a href="{!! url("templates/icons/icons_template.json") !!}" target="_blank">template</a> for reference',
            input: 'file',
            inputAttributes: {
                'accept': 'json/*',
                'aria-label': 'Upload your json file'
            },
            confirmButtonText: 'Upload',
            preConfirm: (file) => {
                if(file.type != 'application/json'){
                    Swal.showValidationMessage(
                        'Invalid file format: '+ file.type
                    )
                }
            },
        })

        if(file){
            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}'),
            formData.append('iconfile',file);
            $.ajax({
                type: 'POST',            
                url: 'icons/import',
                data: formData,
                contentType: false,
                processData:false,
                success: function(data){
                    if(data.success){
                        var successmsg = 'Total: '+data.recordCount+'<br>Success: '+data.successCount+'<br>Failed: '+data.failedCount;
                        Swal.fire({
                            icon: 'success',
                            title: data.success,
                            html: successmsg,
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }else if(data.error){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: data.error,
                        });
                    }
                }
            });
        }
    })()
}
</script>