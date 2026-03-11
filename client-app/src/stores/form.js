import {defineStore} from "pinia";

const useFormStore = defineStore('form', {
    state: () => ({
        isLoading: false,
        form: {
            birthDate: '',
            carForm: '',
            carUse: ''
        },
        results: []
    }),
    getters: {

    },
    actions: {
        persist() {
            sessionStorage.setItem("form", JSON.stringify(this.form))
        },
        load() {
            const data = sessionStorage.getItem("form");
            if (data) {
                this.form = JSON.parse(data);
            }
        }
    }
});

export default useFormStore;