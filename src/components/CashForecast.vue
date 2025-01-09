<template>
    <card>
        <template #title>
            <h3>Cash forecast</h3>
        </template>

        <chart :option="{...chartOptions, series}"/>
    </card>
</template>

<script>

import Chart from "@/includes/Chart.vue";
import Card from "@/includes/Card.vue";
import _ from "lodash";

export default {
    components: {
        Card,
        Chart
    },
    props: {
        transactions: Array,
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    type: 'line',
                },
                xAxis: {
                    type: 'datetime',
                    dateTimeLabelFormats: {month: '%Y-%m-%d'},
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br>',
                    pointFormat: '{point.x:%e. %b %Y}: {point.y:.2f}'
                },
            },
            totalData: {name: "Total", data: []},
        };
    },
    mounted() {
        this.fetchTotalData();
        Bus.on('refetch-chart', () => this.fetchTotalData());
    },
    methods: {
        transformToSeries(data) {
            this.totalData.data = [];

            _.forEach(data, value => {
                this.totalData.data.push([value.date, +value.cumulative_sum]);
            })
        },
        fetchTotalData() {
            fetch('/api/total-chart')
                .then(response => response.json())
                .then(data => {
                    this.transformToSeries(data.data);
                });
        },
    },
    computed: {
        series() {
            let grouped = _.groupBy(this.transactions, 'account');

            _.forEach(grouped, (value, key) => {
                grouped[key] = _.sortBy(value, 'date');

                let s = 0;
                _.forEach(grouped[key], (transaction, index) => {
                    let amount = +transaction.amount + s;
                    let date = transaction.date;
                    s += transaction.amount;
                    grouped[key][index] = [date, amount];
                });
            })


            let series = [];
            _.forEach(grouped, (value, key) => {
                series.push({
                    name: key,
                    data: value
                });
            });

            series.push(this.totalData);

            return series;
        },
    },
};
</script>
