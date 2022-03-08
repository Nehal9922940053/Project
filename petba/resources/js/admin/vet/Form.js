import AppForm from '../app-components/Form/AppForm';

Vue.component('vet-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                image:  '' ,
                phone_number:  '' ,
                address:  '' ,
                details:  '' ,
                gender:  '' ,
                latitude:  '' ,
                longitude:  '' ,
                
            }
        }
    }

});