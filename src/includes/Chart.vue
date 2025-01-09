<template>
    <div :id="chartId" class="high-charts" />
</template>

<script>
    import $ from 'jquery';

    import Highcharts from 'highcharts';
    require('highcharts/modules/exporting');
    require('highcharts/modules/offline-exporting');
    require('highcharts/modules/accessibility');

    export default {
        props: {
            id: String,
            option: Object,
        },
        data() {
            return {
                charts: null,
                chartId : '',
            }
        },
        computed: {
        },
        created() {
            this.chartId = this.id || 'high-charts-' + Math.round(Math.random() * 1000);
        },
        mounted() {
            this.draw();
            window.addEventListener('resize', this.charts.reflow());
        },
        destroyed() {
            window.removeEventListener('resize', this.charts.reflow());
            this.charts = null;
        },
        methods: {
            draw() {
                if (!$('#' + this.chartId).length) {
                    return;
                }

                let highcharts_ = Highcharts;

                highcharts_.setOptions({
                    lang: {
                        decimalPoint: ',',
                        thousandsSep: ' ',
                        locale: 'en-US',
                    }
                });

                this.charts = highcharts_.chart(this.chartId, {
                    ...this.option,
                    title: {
                        text: '',
                    },
                    credits: {enabled: false},
                });

            }
        },
        watch: {
            option: {
                deep: true,
                handler() {
                    this.draw();
                }
            }
        }
    }
</script>
