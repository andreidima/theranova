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

            recoltariSangeGrupe: ((typeof recoltariSangeGrupe !== 'undefined') ? recoltariSangeGrupe : []),
            recoltariSangeProduse: ((typeof recoltariSangeProduse !== 'undefined') ? recoltariSangeProduse : []),
            cerereGrupa: '',
            cerereProdus: '',
            cerereNrPungi: '',
            cereriSange: ((typeof cereriSangeVechi !== 'undefined') ? cereriSangeVechi : []),
        }
    },
    created: function () {
        if (this.recoltariSangeAdaugateLaComandaIDuriVechi.length) {
            for (var i = 0; i < this.recoltariSangeAdaugateLaComandaIDuriVechi.length; i++) {
                for (var j = 0; j < this.recoltariSange.length; j++) {
                    if (this.recoltariSangeAdaugateLaComandaIDuriVechi[i] == this.recoltariSange[j].id) {
                        // this.recoltariSangeAdaugateLaComanda.unshift(this.recoltariSange[j]);
                        this.recoltariSangeAdaugateLaComanda.push(this.recoltariSange[j]);
                    }
                }
            }
        }
    },
    methods: {
        cerereAdauga() {
            if (this.cerereGrupa && this.cerereProdus && this.cerereNrPungi) {
                this.cereriSange.push({ recoltari_sange_grupa_id: this.cerereGrupa, recoltari_sange_produs_id: this.cerereProdus, nr_pungi: this.cerereNrPungi });
                this.cerereGrupa = '';
                this.cerereProdus = '';
                this.cerereNrPungi = '';
            }
        },
        cautaRecoltariSange() {
            this.recoltareSangeCod = this.recoltareSangeCod.replaceAll("A", ""); // daca se lucreaza cu cititorul de barcode, acesta va pune un „A” in fata codului si un „A” la final
            // console.log(this.recoltareSangeCod.slice(0, -1));
            // this.recoltariSangeCautate = [];
            // this.mesajCautareRecoltari = "";
            for (var i = 0; i < this.recoltariSange.length; i++) {
                // if (this.recoltariSange[i].data === '2023-06-30') { // la recoltarile vechi lipsea ultimul caracter din barcod
                // if (this.recoltareSangeCod.length < 9) { // la recoltarile vechi lipsea ultimul caracter din barcod
                    // recoltari din import -> se cauta fara ultimul caracter
                    // console.log(this.recoltareSangeCod.slice(0, -1));
                    // if (i < 5){
                    //     console.log(this.recoltariSange[i].cod.substring(0, this.recoltareSangeCod.length));
                    // }

                // la recoltarile vechi lipsea ultimul caracter din barcod
                // if (this.recoltariSange[i].cod && (this.recoltariSange[i].cod.substring(0, this.recoltareSangeCod.length) === this.recoltareSangeCod) && (this.recoltariSange[i].cantitate == this.recoltareSangeCantitate)) {
                //     this.recoltariSangeCautate.push(this.recoltariSange[i]);
                // }

                // recoltari din aplicatia noua -> se cauta normal
                // } else if (this.recoltariSange[i].cod && (this.recoltariSange[i].cod === this.recoltareSangeCod) && (this.recoltariSange[i].cantitate == this.recoltareSangeCantitate)) {
                    // console.log(this.recoltareSangeCod);
                    // this.recoltariSangeCautate.push(this.recoltariSange[i]);
                // }

                // la recoltarile vechi lipsea ultimul caracter din barcod, asa ca daca barcodul este mai scurt, se cauta doar cat este el
                if (this.recoltariSange[i].cod && (this.recoltariSange[i].cod === this.recoltareSangeCod.substring(0, this.recoltariSange[i].cod.length)) && (this.recoltariSange[i].cantitate == this.recoltareSangeCantitate)) {
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
                    // this.recoltariSangeAdaugateLaComanda.unshift(this.recoltariSangeCautate[i]);
                    this.recoltariSangeAdaugateLaComanda.push(this.recoltariSangeCautate[i]);
                }
            }

            // Se golesc campurile, si se pune prompterul in inputul cod
            this.mesajCautareRecoltari = '',
            this.recoltareSangeCod = '',
            this.recoltareSangeCantitate = '',
            this.recoltariSangeCautate = [],
            this.$nextTick(() => this.$refs.focusCod.focus());
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
                        this.pungi.push({ id: '', data_expirare: '', recoltari_sange_grupa_id: '', cod: '', recoltari_sange_produs_id: '', cantitate: '' });
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


// Validare inregistrari in laborator
const validareInregistrareInLaborator = createApp({
    el: '#validareInregistrareInLaborator',
    data() {
        return {
            cod: '',
            recoltariSangeGasite: [],
            mesajCautareRecoltari: '',

            afisareInterfataRebut: 0,
            dataRebut: dataRebut,
            idRebut: '',
            axiosMesajModificareRebut: '',
        }
    },
    watch: {
        recoltariSangeGasite: function () {
            // cand se reincarca recoltarile, se sterge mesajul de eroare de modificare rebut
            this.axiosMesajModificareRebut = '';

            // Se pune la fiecare recoltare data din baza de data, sau daca nu exista se pune data curenta
            for(var i = 0; i< this.recoltariSangeGasite.length; i++) {
                if (this.recoltariSangeGasite[i].rebut_data) {
                    this.recoltariSangeGasite[i].dataRebut = new Date(this.recoltariSangeGasite[i].rebut_data).toLocaleString('ro-RO').split(',')[0];
                } else {
                    this.recoltariSangeGasite[i].dataRebut = new Date().toLocaleString('ro-RO').split(',')[0];
                }
            }
        }
    },
    mounted() {
        this.$nextTick(() => this.$refs.focusMe.focus())
    },
    methods: {
        axiosCautaPungaCuDelay() {
            this.mesajCautareRecoltari = "<div class='bg-info text-white text-center rounded-3 h4 py-1'>Se caută recoltări</div>";
            this.recoltariSangeGasite = [];
            setTimeout(() => this.axiosCautaPunga(), 500);
        },
        axiosCautaPunga() {
            axios
                .post('/recoltari-sange-validare-inregistrari-in-laborator/axios-cauta-punga',
                    {
                        cod: this.cod
                    },
                    {
                        params: {
                            // request: 'actualizareSuma',
                        }
                    })
                .then(response => {
                    this.recoltariSangeGasite = response.data.recoltariSangeGasite;

                    // Daca nu se gasesc recoltari, se afiseaza mesaj de atentionare
                    if (this.recoltariSangeGasite.length === 0) {
                        this.mesajCautareRecoltari = "<div class='bg-warning text-center rounded-3 h4 py-1'>Codul <span class='h4 fw-bold'>" + this.cod + "</span> nu există în baza de date</div>";
                    } else {
                        this.mesajCautareRecoltari = "";
                    }

                    // Se goleste campul cod, si se pune prompterul in inputul cod
                    this.cod = '';
                    this.$nextTick(() => this.$refs.focusMe.focus());
                });
        },
        valideazaInvalideaza(actiune,recoltareSangeId) {
            // console.log(actiune, recoltareSangeId);
            axios
                .post('/recoltari-sange-validare-inregistrari-in-laborator/valideaza-invalideaza-punga',
                    {
                        actiune: actiune,
                        recoltareSangeId: recoltareSangeId
                    },
                    {
                        params: {
                            // request: 'actualizareSuma',
                        }
                    })
                .then(response => {
                    this.recoltariSangeGasite = response.data.recoltariSangeGasite;
                    // console.log(response);
                });
            this.$nextTick(() => this.$refs.focusMe.focus())
        },
        modificaRebutPunga(recoltareSangeId, dataRebut, rebutId){
            // console.log(recoltareSangeId, rebutId);
            // console.log(actiune, recoltareSangeId);
            axios
                .post('/recoltari-sange-validare-inregistrari-in-laborator/modifica-rebut-punga',
                    {
                        recoltareSangeId: recoltareSangeId,
                        rebutId: rebutId,
                        dataRebut: dataRebut,
                    },
                    {
                        params: {
                            // request: 'actualizareSuma',
                        }
                    })
                .then(response => {
                    if (response.data.mesaj === "succes"){
                        this.recoltariSangeGasite = response.data.recoltariSangeGasite;
                    } else {
                        this.axiosMesajModificareRebut = response.data.mesaj;
                    }
                    // console.log(response);
                });
            this.$nextTick(() => this.$refs.focusMe.focus())
        }
    }
});
if (document.getElementById('validareInregistrareInLaborator') != null) {
    validareInregistrareInLaborator.mount('#validareInregistrareInLaborator');
}
