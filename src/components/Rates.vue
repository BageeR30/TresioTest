<template>
    <card>
        <template #title>
            <h3>Rates</h3>
        </template>

        <div>
            <h3>CURRENT FX RATES</h3>
            <p>Value in CHF</p>
            <table>
                <thead>
                <tr>
                    <th>Currency</th>
                    <th>FX Rate</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(rate, currency) in rates" :key="currency">
                    <td>{{ currency }}</td>
                    <td>{{ rate }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </card>
</template>

<script>
    import Card from "@/includes/Card.vue";

    export default {
        components: {Card},
        data() {
            return {
                rates: {},
            }
        },
        mounted() {
            $.ajax({
                url: process.env.VUE_APP_RATES_URL,
                method: 'GET',
                success: response => {
                    this.$emit('rates-updated', response.data);

                    this.rates = response.data;
                }
            })
        }
    }
</script>

<style scoped>
    h3 {
        font-size: 1.2em;
        margin-bottom: 5px;
    }

    p {
        font-size: 0.9em;
        color: #555;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 8px 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }
</style>
