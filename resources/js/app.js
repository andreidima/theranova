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
            pacient_telefon: '',
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
                    // this.pacient_data_nastere = new Date(this.pacienti[i].data_nastere); this.pacient_data_nastere = this.pacient_data_nastere.toLocaleString('ro-RO', { dateStyle: 'short' });
                    this.pacient_telefon = this.pacienti[i].telefon;
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
const clickOutside = {
    beforeMount: (el, binding) => {
        el.clickOutsideEvent = event => {
            if (!(el == event.target || el.contains(event.target))) {
                binding.value();
            }
        };
        document.addEventListener("click", el.clickOutsideEvent);
    },
    unmounted: el => {
        document.removeEventListener("click", el.clickOutsideEvent);
    },
};

pacientAutocomplete.directive("clickOut", clickOutside);

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

const fisaCazFormCerinte = createApp({
    el: '#fisaCazFormCerinte',
    data() {
        return {
            cerinte: cerinte,
        }
    },
    created: function () {
        if (this.cerinte.length === 0) {
            this.cerinte.push({});
        }
    },
});
if (document.getElementById('fisaCazFormCerinte') != null) {
    fisaCazFormCerinte.mount('#fisaCazFormCerinte');
}


// 07.06.2024 - it was made a single function for fisaCaz, that included: pacientAutocomplete, fisaCazFormDateMedicale and fisaCazFormCerinte
const fisaCazForm = createApp({
    el: '#fisaCazForm',
    data() {
        return {
            pacient_id: pacientIdVechi,
            pacient_nume: '',
            pacient_telefon: '',
            pacient_localitate: '',
            pacienti: pacienti,
            pacientiListaAutocomplete: [],
            dateMedicale: dateMedicale,
            cerinte: cerinte,

            tip_lucrare_solicitata: tipLucrareSolicitataVeche,
            displayAllFields: true,
        }
    },
    watch: {
        tip_lucrare_solicitata: {
            immediate: true,
            handler: function (newVal, oldVal) {
                if ((this.tip_lucrare_solicitata == "Disp mers") || (this.tip_lucrare_solicitata == "Fotoliu") || (this.tip_lucrare_solicitata == "Orteză") ||
                    (this.tip_lucrare_solicitata == "Proteză sân") || (this.tip_lucrare_solicitata == "Proteză sân+sutien") || (this.tip_lucrare_solicitata == "Sutien")) {
                    this.displayAllFields = false;
                } else {
                    this.displayAllFields = true;
                }
            }
        }
    },
    created: function () {
        if (this.pacient_id) {
            for (var i = 0; i < this.pacienti.length; i++) {
                if (this.pacienti[i].id == this.pacient_id) {
                    this.pacient_nume = this.pacienti[i].nume + ' ' + this.pacienti[i].prenume;
                    // this.pacient_data_nastere = new Date(this.pacienti[i].data_nastere); this.pacient_data_nastere = this.pacient_data_nastere.toLocaleString('ro-RO', { dateStyle: 'short' });
                    this.pacient_telefon = this.pacienti[i].telefon;
                    this.pacient_localitate = this.pacienti[i].localitate;
                    break;
                }
            }
        }
        if (this.dateMedicale.length === 0) {
            this.dateMedicale.push({});
        }
        if (this.cerinte.length === 0) {
            this.cerinte.push({});
        }
    },
    methods: {
        autocompletePacienti() {
            this.pacientiListaAutocomplete = [];

            for (var i = 0; i < this.pacienti.length; i++) {
                if (this.pacienti[i].nume || this.pacienti[i].prenume) {
                    var nume = this.pacienti[i].nume + ' ' + this.pacienti[i].prenume;
                    if (nume.toLowerCase().normalize("NFD").replace(/\p{Diacritic}/gu, "").includes(this.pacient_nume.toLowerCase().normalize("NFD").replace(/\p{Diacritic}/gu, ""))) {
                        this.pacientiListaAutocomplete.push(this.pacienti[i]);
                    }
                }
            }
        },
    }
});

fisaCazForm.directive("clickOut", clickOutside);
fisaCazForm.component('vue-datepicker-next', VueDatepickerNext);

if (document.getElementById('fisaCazForm') != null) {
    fisaCazForm.mount('#fisaCazForm');
}


const pacientFormApartinatori = createApp({
    el: '#pacientFormApartinatori',
    data() {
        return {
            apartinatori: apartinatori,
        }
    },
    // methods: {
    //     adaugaApartinator() {
    // created: function () {
    //     if (this.apartinatori.length === 0) {
    //         this.apartinatori.push({});
    //     }
    // },
});
if (document.getElementById('pacientFormApartinatori') != null) {
    pacientFormApartinatori.mount('#pacientFormApartinatori');
}


const comandaComponente = createApp({
    el: '#comandaComponente',
    data() {
        return {
            comenziComponente: comenziComponente,
        }
    },
    // created: function () {
    //     if (this.comenziComponente.length === 0) {
    //         this.comenziComponente.push({});
    //     }
    // },
});
if (document.getElementById('comandaComponente') != null) {
    comandaComponente.mount('#comandaComponente');
}

// 04.11.2024 - added incasari to oferte
const incasari = createApp({
    el: '#incasari',
    data() {
        const initialIncasari = (typeof incasariVechi !== 'undefined' && Array.isArray(incasariVechi)) ? incasariVechi : [];

        return {
            ofertaId: typeof ofertaId !== 'undefined' ? ofertaId : null,
            incasari: initialIncasari,
        }
    },
    created: function () {
        this.incasari = this.incasari.map((incasare) => ({
            id: incasare.id ?? null,
            oferta_id: incasare.oferta_id ?? this.ofertaId,
            suma: incasare.suma ?? '',
            data: incasare.data ?? '',
            observatii: incasare.observatii ?? '',
            tip: incasare.tip ?? 'incasare',
        }));
    },
    methods: {
        adaugaIncasare() {
            this.incasari.push({
                id: null,
                oferta_id: this.ofertaId,
                suma: '',
                data: '',
                observatii: '',
                tip: 'incasare',
            });
        }
    }
});
// incasari.component('vue-datepicker-next', VueDatepickerNext);
if (document.getElementById('incasari') != null) {
    incasari.mount('#incasari');
}

const deciziiCas = createApp({
    el: '#deciziiCas',
    data() {
        const initialDecizii = (typeof deciziiCasVechi !== 'undefined' && Array.isArray(deciziiCasVechi)) ? deciziiCasVechi : [];

        return {
            ofertaId: typeof ofertaId !== 'undefined' ? ofertaId : null,
            deciziiCas: initialDecizii,
        }
    },
    created: function () {
        this.deciziiCas = this.deciziiCas.map((decizie) => ({
            id: decizie.id ?? null,
            oferta_id: decizie.oferta_id ?? this.ofertaId,
            suma: decizie.suma ?? '',
            data: decizie.data ?? '',
            data_inregistrare: decizie.data_inregistrare ?? '',
            data_validare: decizie.data_validare ?? '',
            observatii: decizie.observatii ?? '',
            tip: decizie.tip ?? 'decizie_cas',
        }));
    },
    methods: {
        adaugaDecizieCas() {
            this.deciziiCas.push({
                id: null,
                oferta_id: this.ofertaId,
                suma: '',
                data: '',
                data_inregistrare: '',
                data_validare: '',
                observatii: '',
                tip: 'decizie_cas',
            });
        }
    }
});
if (document.getElementById('deciziiCas') != null) {
    deciziiCas.mount('#deciziiCas');
}

