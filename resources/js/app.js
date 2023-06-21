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


// Formular adaugare recoltare sange
const adaugareRecoltareSange = createApp({
    el: '#adaugareRecoltareSange',
    data() {
        return {
            nrPungi: ((typeof nrPungi !== 'undefined') ? nrPungi : ''),
            // recoltariSangeGrupaId: '',
            // cod: '',
            // tip: '',
            // recoltariSange: [],
            cantitatiPungiSange: ((typeof cantitatiPungiSange !== 'undefined') ? cantitatiPungiSange : []),
        }
    },
    // watch: {
    //     nrPungi: function () {
    //         this.recoltariSange = [];
    //         for (var i = 0; i < this.nrPungi; i++) {
    //             var recoltareSange = {
    //                 recoltariSangeGrupaId: this.recoltariSangeGrupaId,
    //                 cod: this.cod,
    //                 tip: this.tip,
    //                 cantitate: '',
    //             }
    //             this.recoltariSange[i] = recoltareSange;
    //         }
    //     },
    //     cod: function () {
    //         for (var i = 0; i < this.nrPungi; i++) {
    //             this.recoltariSange[i].cod = this.cod;
    //         }
    //     },
    //     tip: function () {
    //         for (var i = 0; i < this.nrPungi; i++) {
    //             this.recoltariSange[i].tip = this.tip;
    //         }
    //     },
    // },
    methods: {
    }
});
adaugareRecoltareSange.component('vue-datepicker-next', VueDatepickerNext);
if (document.getElementById('adaugareRecoltareSange') != null) {
    adaugareRecoltareSange.mount('#adaugareRecoltareSange');
}


// Formular comanda recoltare sange
const recoltareSangeComanda = createApp({
    el: '#recoltareSangeComanda',
    data() {
        return {
            recoltariSange: ((typeof recoltariSange !== 'undefined') ? recoltariSange : []),

            recoltareSangeCod: '',
            recoltariSangeCautate: [],


            recoltariSangeAdaugateLaComandaIDuriVechi: ((typeof recoltariSangeAdaugateLaComandaIDuriVechi !== 'undefined') ? recoltariSangeAdaugateLaComandaIDuriVechi : []),

            recoltariSangeAdaugateLaComanda: [],
        }
    },
    created: function () {
        if (this.recoltariSangeAdaugateLaComandaIDuriVechi.length) {
            for (var i = 0; i < this.recoltariSangeAdaugateLaComandaIDuriVechi.length; i++) {
                for (var j = 0; j < this.recoltariSange.length; j++) {
                    if (this.recoltariSangeAdaugateLaComandaIDuriVechi[i] == this.recoltariSange[j].id) {
                        this.recoltariSangeAdaugateLaComanda.unshift(this.recoltariSange[j]);
                    }
                }
            }
        }
    },
    methods: {
        cautaRecoltariSange() {
            this.recoltariSangeCautate = [];

            for (var i = 0; i < this.recoltariSange.length; i++) {
                if (this.recoltariSange[i].cod && (this.recoltariSange[i].cod === this.recoltareSangeCod)) {
                    this.recoltariSangeCautate.push(this.recoltariSange[i]);
                }
            }
        },
        adaugaRecoltareSangeLaComanda: function (recoltareSangeId) {
            for (var i = 0; i < this.recoltariSangeAdaugateLaComanda.length; i++) {
                if (this.recoltariSangeAdaugateLaComanda[i].id === recoltareSangeId){
                    return;
                }
            }
            for (var i = 0; i < this.recoltariSangeCautate.length; i++) {
                if (this.recoltariSangeCautate[i].id && (this.recoltariSangeCautate[i].id === recoltareSangeId)) {
                    this.recoltariSangeAdaugateLaComanda.unshift(this.recoltariSangeCautate[i]);
                }
            }
        },
        stergeRecoltareSangeLaComanda: function (recoltareSangeId) {
            for (var i = 0; i < this.recoltariSangeAdaugateLaComanda.length; i++) {
                if (this.recoltariSangeAdaugateLaComanda[i].id === recoltareSangeId) {
                    this.recoltariSangeAdaugateLaComanda.splice(i, 1);
                    break;
                }
            }
        },
    }
});
recoltareSangeComanda.component('vue-datepicker-next', VueDatepickerNext);
if (document.getElementById('recoltareSangeComanda') != null) {
    recoltareSangeComanda.mount('#recoltareSangeComanda');
}
