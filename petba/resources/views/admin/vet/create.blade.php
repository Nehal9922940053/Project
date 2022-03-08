

@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.vet.actions.create'))


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
        color:#b9c8de;
        cursor: default;
    }

    .image-button {
        display: none;
    }

    .image-preview__image {
        display: none;
        object-fit: cover;
    }

    #inpFile {
        display: none;
    }

    .image-location {
        display: none;
    }
</style>
@endsection


@section('body')

    <div class="container-xl">

        <div class="card">
        
            <vet-form
                :action="'{{ url('admin/vets') }}'"
                v-cloak
                inline-template>

                <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action" novalidate>
                    
                    <div class="card-header">
                        <i class="fa fa-plus"></i> {{ trans('admin.vet.actions.create') }}
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
    <script>
        const inpFile = document.getElementById("inpFile");
        const previewContainer = document.getElementById("imagePreview");
        const previewImage = previewContainer.querySelector(".image-preview__image");
        const previewDefaultText = previewContainer.querySelector(".image-preview__hint-text");

        inpFile.addEventListener("change", function(){
            const file = this.files[0];
            if(file){
                const reader = new FileReader();

                previewDefaultText.style.display = "none";
                previewImage.style.display = "block";

                reader.addEventListener("load",function(){
                    previewImage.setAttribute("src",this.result);
                });
                reader.readAsDataURL(file);
            }
            else{
                previewDefaultText.style.display = null;
                previewImage.style.display = null;
                previewImage.setAttribute("src","");
            }
        });
    </script>
@endsection
