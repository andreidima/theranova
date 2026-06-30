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
import ProspectProductSelector from './components/ProspectProductSelector.vue';

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
            nr_data: decizie.nr_data ?? '',
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
                nr_data: '',
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

const ofertaProspectareForm = createApp({
    el: '#ofertaProspectareForm',
    data() {
        return {
            linii: (typeof ofertaProspectareLiniiVechi !== 'undefined' && Array.isArray(ofertaProspectareLiniiVechi)) ? ofertaProspectareLiniiVechi : [],
            amputatii: (typeof ofertaProspectareAmputatiiVechi !== 'undefined' && Array.isArray(ofertaProspectareAmputatiiVechi)) ? ofertaProspectareAmputatiiVechi : [],
            adaosIntervale: (typeof ofertaProspectareAdaosIntervale !== 'undefined' && Array.isArray(ofertaProspectareAdaosIntervale)) ? ofertaProspectareAdaosIntervale : [],
            decontare_cas: Number((typeof ofertaProspectareValoriVechi !== 'undefined' ? ofertaProspectareValoriVechi.decontare_cas : 0) ?? 0),
            buget_disponibil: Number((typeof ofertaProspectareValoriVechi !== 'undefined' ? ofertaProspectareValoriVechi.buget_disponibil : 0) ?? 0),
            total_oferta: Number((typeof ofertaProspectareValoriVechi !== 'undefined' ? ofertaProspectareValoriVechi.total_oferta : 0) ?? 0),
            discount_aditional: Number((typeof ofertaProspectareValoriVechi !== 'undefined' ? ofertaProspectareValoriVechi.discount_aditional : 0) ?? 0),
        }
    },
    created: function () {
        this.amputatii = this.amputatii.map((amputatie) => ({
            id: amputatie.id ?? null,
            parte_amputata: amputatie.parte_amputata ?? '',
            amputatie: amputatie.amputatie ?? '',
        }));

        if (this.amputatii.length === 0) {
            this.adaugaAmputatie();
        }

        this.linii = this.linii.map((linie, index) => ({
            row_key: linie.row_key || `existing-${linie.id || index}-${Date.now()}`,
            id: linie.id ?? null,
            produs_prospectare_id: linie.produs_prospectare_id ?? null,
            denumire_produs: linie.denumire_produs ?? '',
            produs_label: linie.produs_label ?? '',
        }));

        if (this.linii.length === 0) {
            this.adaugaLinie();
        }
    },
    computed: {
        subtotal() {
            return Math.max(0, Number(this.total_oferta || 0));
        },
        adaosInterval() {
            return this.adaosIntervale.find((interval) => {
                const min = Number(interval.valoare_min || 0);
                const max = interval.valoare_max === null || interval.valoare_max === undefined || interval.valoare_max === ''
                    ? null
                    : Number(interval.valoare_max);

                return this.subtotal >= min && (max === null || this.subtotal <= max);
            }) || null;
        },
        adaos_procent() {
            return Number(this.adaosInterval?.procent || 0);
        },
        adaos_valoare() {
            return Math.round(this.subtotal * this.adaos_procent / 100);
        },
        totalCuAdaos() {
            return this.subtotal + this.adaos_valoare;
        },
        totalDupaCas() {
            const buget = this.decontare_cas ? Number(this.buget_disponibil || 0) : 0;
            return Math.max(0, this.totalCuAdaos - buget);
        },
        total() {
            return Math.max(0, this.totalDupaCas - Number(this.discount_aditional || 0));
        },
        avans() {
            return Math.round(this.total * 0.7);
        },
    },
    methods: {
        adaugaAmputatie() {
            this.amputatii.push({
                id: null,
                parte_amputata: '',
                amputatie: '',
            });
        },
        adaugaLinie() {
            this.linii.push({
                row_key: `new-${Date.now()}-${Math.random().toString(36).slice(2)}`,
                id: null,
                produs_prospectare_id: null,
                denumire_produs: '',
                produs_label: '',
            });
        },
        stergeLinie(index) {
            this.linii.splice(index, 1);
        },
        alegeProdusSelector(index, event) {
            const produs = event?.detail?.product;
            const query = (event?.detail?.query || '').trim();
            if (!produs) {
                this.linii[index].produs_prospectare_id = null;
                this.linii[index].produs_label = '';
                this.linii[index].denumire_produs = query;
                return;
            }

            this.linii[index].produs_prospectare_id = produs.id;
            this.linii[index].produs_label = produs.label || '';
            this.linii[index].denumire_produs = produs.denumire || produs.label || '';
        },
        formatMoney(value) {
            return new Intl.NumberFormat('ro-RO', { maximumFractionDigits: 0 }).format(Number(value || 0));
        },
    }
});
ofertaProspectareForm.component('prospect-product-selector', ProspectProductSelector);
if (document.getElementById('ofertaProspectareForm') != null) {
    ofertaProspectareForm.mount('#ofertaProspectareForm');
}
