<template>
    <div class="container">
        <div class="row">
            <file-upload @file-uploaded="transactions = $event"/>
            <rates @rates-updated="rates = $event"/>
        </div>

        <bank-accounts
            v-if="rates"
            :transactions="transactions"
            :rates="rates"
            @updated="list = $event"
        />

        <cash-forecast :transactions="transactions" :list="list"/>

        <transactions />
    </div>
</template>

<script>
    import FileUpload  from "@/components/FileUpload.vue";
    import Rates from "@/components/Rates.vue";
    import BankAccounts from "@/components/BankAccounts.vue";
    import CashForecast from "@/components/CashForecast.vue";
    import Transactions from "@/components/Transactions.vue";

    export default {
        components: {
            Transactions,
            CashForecast,
            Rates,
            FileUpload,
            BankAccounts,
        },
        data() {
            return {
                transactions: [],
                rates: null,
                list: [],
            }
        },
        mounted() {
            this.fetchTransactions();
            Bus.on('refetch-chart', () => this.fetchTransactions())
        },
        methods: {
            fetchTransactions() {
                fetch('/api/transactions')
                    .then(response => response.json())
                    .then(data => {
                        this.transactions = data.data;
                    });
            }
        }
    }
</script>

<style scoped>
    .row {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .row > * {
        margin: 10px;
    }

    .container > * {
        margin: 10px;
    }
</style>
