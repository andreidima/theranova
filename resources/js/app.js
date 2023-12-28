/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import '../sass/app.scss'
import '../css/andrei.css'

import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);

import VueDatepickerNext from './components/DatePicker.vue';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.component('vue-datepicker-next', VueDatepickerNext);


if (document.getElementById('app') != null) {
    app.mount('#app');
}


// App pentru DatePicker
const datePicker = createApp({});
datePicker.component('vue-datepicker-next', VueDatepickerNext);
if (document.getElementById('datePicker') != null) {
    datePicker.mount('#datePicker');
}


const pacientAutocomplete = createApp({
    el: '#pacientAutocomplete',
    data() {
        return {
            pacient_id: pacientIdVechi,
            pacient_nume: '',
            pacient_data_nastere: '',
            pacient_localitate: '',
            pacienti: pacienti,
            pacientiListaAutocomplete: []
        }
    },
    created: function () {
        if (this.pacient_id) {
            for (var i = 0; i < this.pacienti.length; i++) {
                if (this.pacienti[i].id == this.pacient_id) {
                    this.pacient_nume = this.pacienti[i].nume + ' ' + this.pacienti[i].prenume;
                    this.pacient_data_nastere = new Date(this.pacienti[i].data_nastere); this.pacient_data_nastere = this.pacient_data_nastere.toLocaleString('ro-RO', { dateStyle: 'short' });;
                    this.pacient_localitate = this.pacienti[i].localitate;
                    break;
                }
            }
        }
    },
    methods: {
        autocompletePacienti() {
            this.pacientiListaAutocomplete = [];

            for (var i = 0; i < this.pacienti.length; i++) {
                if (this.pacienti[i].nume || this.pacienti[i].prenume){
                    var nume = this.pacienti[i].nume + ' ' + this.pacienti[i].prenume;
                    if (nume.toLowerCase().normalize("NFD").replace(/\p{Diacritic}/gu, "").includes(this.pacient_nume.toLowerCase().normalize("NFD").replace(/\p{Diacritic}/gu, ""))){
                        this.pacientiListaAutocomplete.push(this.pacienti[i]);
                    }
                }
            }
        },
    }
});
if (document.getElementById('pacientAutocomplete') != null) {
    pacientAutocomplete.mount('#pacientAutocomplete');
}

const fisaCazFormDateMedicale = createApp({
    el: '#fisaCazFormDateMedicale',
    data() {
        return {
            dateMedicale: dateMedicale,
        }
    },
    created: function () {
        if (this.dateMedicale.length === 0) {
            this.dateMedicale.push({});
        }
    },
});
if (document.getElementById('fisaCazFormDateMedicale') != null) {
    fisaCazFormDateMedicale.mount('#fisaCazFormDateMedicale');
}


