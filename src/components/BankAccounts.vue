<template>
    <card>
        <template #title>
            <h3>List of bank accounts</h3>
        </template>

        <table id="banksTable">
            <thead>
            <tr>
                <th>Banks</th>
                <th>Currency</th>
                <th>Starting balance</th>
                <th>End balance</th>
                <th>End balance (CHF)</th>
            </tr>
            </thead>
        </table>
    </card>

</template>

<script>
    import Editor from '@datatables.net/editor-2025-01-20-dt/js/editor.dataTables';
    import 'datatables.net-dt/css/dataTables.dataTables.css';
    import Card from "@/includes/Card.vue";

    export default {
        components: {Card},
        props: {
            rates: Object,
        },
        data() {
            return {
                dataTable: null,
                editor: null,
            }
        },
        mounted() {
            this.editor = new Editor({
                ajax: '/api/sums',
                table: '#banksTable',
                fields: [
                    { name: 'accounts.name' },
                    { name: 'sums.starting_balance'},
                    { name: 'sums.sum'},
                ],
                idSrc: 'sums.account_id',
            });

            this.dataTable = $('#banksTable').DataTable({
                ajax: {
                    url: '/api/sums',
                    type: 'POST',
                },
                "columns": [
                    { data: "accounts.name" },
                    { data: "accounts.currency" },
                    { data: "sums.starting_balance" },
                    { data: "sums.sum", render: (data, type, row) => parseFloat(row.sums.starting_balance) + parseFloat(row.sums.sum), },
                    { data: null, render: (data, type, row) => this.round((parseFloat(row.sums.starting_balance) + parseFloat(row.sums.sum)) * this.getRate(row.accounts.currency)), },
                ],
                paging: false,
                searching: false,
            });

            this.dataTable.on('click', 'td', (event) => {
                const columnIndex = $(event.currentTarget).index();

                if ([0, 2].includes(columnIndex)) {
                    this.editor.inline(event.currentTarget, {
                        onBlur: 'submit',
                    });
                }
            });

            Bus.on('refetch-sums', () => this.dataTable.ajax.reload());

            this.editor.on('submitSuccess', () => {
                Bus.emit('refetch-transactions');
                Bus.emit('refetch-chart');
            });
        },
        methods: {
            getRate(currency) {
                return {...this.rates, CHF: 1}[currency];
            },
            round(value) {
                return +value.toFixed(2);
            },
        },
    }
</script>

<style scoped>

</style>
