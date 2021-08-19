<template>
    <div id="panel" class="container">
        <div class="container row p-4" >
            <div id="records" class="col-lg-4 col-md-4 col-sm-4">
                <h2>LEAGUE Table</h2>
                <table class="table table-bordered" >
                    <thead>
                    <tr>
                        <th colspan="2">Team</th>
                        <th>PTS</th>
                        <th>P</th>
                        <th>W</th>
                        <th>D</th>
                        <th>L</th>
                        <th>GD</th>
                    </tr>
                    </thead>
                    <Record v-for="(record, index) in records" :key="index"
                            :record="record"
                            class="record" />
                </table>


            </div>

            <div  class="ml-5 col-lg-4 col-md-4 col-sm-4">
                <h2> {{currentWeek.name}} Week Matches</h2>
                <table class="table table-bordered" >
                    <thead>
                    <tr>
                        <th colspan="2">Home Team</th>

                        <th  colspan="2">Guest Team</th>
                    </tr>
                    </thead>
                    <Match v-for="(record, index) in matches" :key="index"
                           :match="record"
                           class="record" />
                </table>
            </div>
            <div v-if="currentWeek.id >3 " class="col-md-3">
                <h2>PREDICTIONS {{currentWeek.name}} Week</h2>
                <table class="table table-bordered" >
                    <thead>
                    <tr>
                        <th width="75%">Team</th>

                        <th  >Prediction</th>
                    </tr>
                    </thead>
                    <Prediction v-for="(prediction, index) in predictions" :key="index"
                           :prediction="prediction"
                           class="prediction" />
                </table>
            </div>

        </div>
        <div class="row">

            <div class="col-lg-6">
                <button class=" float-left" @click="playAll()" >Play All</button>
            </div>
            <div class="col-lg-6">
                <button class=" float-right" @click="nextWeek()" >Next Week </button>
            </div>



        </div>
    </div>
</template>

<script>

    // export default {
    //     mounted() {
    //         console.log('Component mounted.')
    //     }
    // }

    // import Match from './Match.vue';
    // import Prediction from './Prediction.vue';
    import Record from "./Record";
    import Match from "./Match";
    import Prediction from "./Prediction";


    export default {
        data() {
            return {
                records: [],
                matches: [],
                predictions: [],
                currentWeek:0
            }
        },
        methods: {
            async nextWeek() {

                axios.get(this.currentWeek.id? '/weekresults?week_id='+ (this.currentWeek.id +1):  '/weekresults?week_id=1')
                    .then( response=>{
                        this.matches  = response.data.matches;
                        this.currentWeek = response.data.week;
                        // console.log(response);
                        axios.get('/calculateRecords')
                            .then( response=>{
                                this.records = response.data
                                 // console.log(response.data);
                            }).catch( error =>{
                            console.log(error);
                        })
                        if(this.currentWeek.id>3)
                            axios.get('/predictions')
                                .then( response=>{
                                    this.predictions = response.data
                                     console.log(response);
                                }).catch( error =>{
                                console.log(error);
                            })
                    }).catch( error =>{
                    console.log(error);
                })

            },
            async playAll() {
                axios.get('/playall')
                    .then( response=>{
                        this.matches  = response.data.matches;
                        this.currentWeek = response.data.week;
                        // console.log(response);
                        axios.get('/calculateRecords')
                            .then( response=>{
                                this.records = response.data
                                // console.log(response.data);
                            }).catch( error =>{
                            console.log(error);
                        })
                        if(this.currentWeek.id>3)
                            axios.get('/predictions')
                                .then( response=>{
                                    this.predictions = response.data
                                    console.log(response);
                                }).catch( error =>{
                                console.log(error);
                            })
                    }).catch( error =>{
                    console.log(error);
                })
            },
            async initialize(){
                //create matches
                axios.get('/generate')
                    .then( response=>{
                        this.matches  = response.data.matches;
                        this.currentWeek = response.data.week;
                        console.log(response);
                    }).catch( error =>{
                    console.log(error);
                })

                //create records
                axios.get('/calculateRecords')
                    .then( response=>{
                        this.records = response.data
                        // console.log(response);
                    }).catch( error =>{
                    console.log(error);
                })
            }
        },
        created(){
            this.initialize();
        },
        components: {
            Prediction,
            Match,
            Record,

        }
    }
</script>

<style scoped>
    #panel{
        width: 100%;
        min-width: 800px;
    }
</style>
