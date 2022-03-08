@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.vet.actions.edit', ['name' => $vet->name]))


@section('style')
<style> 
    .image-preview {
        height: 250px;
        width: 350px;
        border: solid 1px #b9c8de;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-preview__hint-text {
        display: none;
        color:#b9c8de;
        cursor: default;
    }

    .image-button {
        display: none;
    }

    .image-preview__image {
        object-fit: cover;
    }

    #inpFile {
        display: none;
    }
</style>
@endsection


@section('body')

    <div class="container-xl">
        <div class="card">

            <vet-form
                :action="'{{ $vet->resource_url }}'"
                :data="{{ $vet->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.vet.actions.edit', ['name' => $vet->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.vet.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </vet-form>

        </div>
    
</div>


@endsection




@section('bottom-scripts')
    {{-- <script src="{{asset('craftable/js/image-preview.js')}}"></script> --}}
@endsection