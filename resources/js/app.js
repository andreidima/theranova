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
            recoltariSangeProduse: ((typeof recoltariSangeProduse !== 'undefined') ? recoltariSangeProduse : []),

            tipuri: ['S', 'D', 'D1', 'T'],
            tip: ((typeof tip !== 'undefined') ? tip : ''),

            produs: ((typeof produs !== 'undefined') ? produs : ''), // doar pentru adaugare

            pungi: ((typeof pungi !== 'undefined') ? pungi : ''), // doar pentru modificare
        }
    },
    watch: {
        tip: function () {
            this.pungi = [];
            switch (this.tip){
                case "S":
                    this.pungi[1] = ({ produs: 15, cantitate:200});
                    break;
                case "D":
                    this.pungi[1] = ({ produs: 4, cantitate: 200 });
                    this.pungi[2] = ({ produs: 16, cantitate: 250 });
                    break;
                case "D1":
                    this.pungi[1] = ({ produs: 17, cantitate: 200 });
                    break;
                case "T":
                    this.pungi[1] = ({ produs: 16, cantitate: 200 });
                    this.pungi[2] = ({ produs: 4, cantitate: 150 });
                    this.pungi[3] = ({ produs: 18, cantitate: 100 });
                    break;
            }
        },
    },
    // created: function () {
    //     this.nrPungi = this.pungi.length;
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
            recoltareSangeCantitate: '',
            recoltariSangeCautate: [],

            recoltariSangeAdaugateLaComandaIDuriVechi: ((typeof recoltariSangeAdaugateLaComandaIDuriVechi !== 'undefined') ? recoltariSangeAdaugateLaComandaIDuriVechi : []),

            recoltariSangeAdaugateLaComanda: [],

            mesajCautareRecoltari: '',
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
            // this.recoltariSangeCautate = [];
            // this.mesajCautareRecoltari = "";
            for (var i = 0; i < this.recoltariSange.length; i++) {
                if (this.recoltariSange[i].cod && (this.recoltariSange[i].cod === this.recoltareSangeCod) && (this.recoltariSange[i].cantitate == this.recoltareSangeCantitate)) {
                    this.recoltariSangeCautate.push(this.recoltariSange[i]);
                }
            }
            if (!this.recoltariSangeCautate.length){
                this.mesajCautareRecoltari = "<div class='bg-danger text-white rouded-3'><center>Nu au fost găsite recoltări</center>Criterii căutate: <ul> <li>Cod: " + this.recoltareSangeCod + "</li><li>Cantitate: " + this.recoltareSangeCantitate + "</li></ul></div>";
            }
        },
        cautaRecoltariSangeCuDelay() {
            this.mesajCautareRecoltari = "<div class='bg-info text-white rouded-3'><center>Se caută recoltări</center></div>";
            this.recoltariSangeCautate = [];
            setTimeout(() => this.cautaRecoltariSange(), 500);
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


// Formular intrare recoltare sange
const recoltareSangeIntrare = createApp({
    el: '#recoltareSangeIntrare',
    data() {
        return {
            recoltariSangeProduse: recoltariSangeProduse,
            recoltariSangeGrupe: recoltariSangeGrupe,
            // nrPungi: nrPungi,
            nrPungi: '',
            pungi: pungi,
        }
    },
    watch: {
    },
    created: function () {
    },
    methods: {
        adaugaPungi() {
            if (Number.isInteger(parseInt(this.nrPungi))) {
                if (this.nrPungi < 100){
                    for (var i = 0; i < this.nrPungi; i++) {
                        this.pungi.push({ id: '', data: '', recoltari_sange_grupa_id: '', cod: '', recoltari_sange_produs_id: '', cantitate: '' });
                    }
                }
            }
            this.nrPungi = '';
        },
        stergePunga(index) {
            this.pungi.splice(index, 1);
        }
    }
});
recoltareSangeIntrare.component('vue-datepicker-next', VueDatepickerNext);
if (document.getElementById('recoltareSangeIntrare') != null) {
    recoltareSangeIntrare.mount('#recoltareSangeIntrare');
}
