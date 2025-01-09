<template>
    <card>
        <template #title>
            <h3>Transactions</h3>
        </template>

        <table id="transactionsTable">
            <thead>
            <tr>
                <th>Account</th>
                <th>Transaction No</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Date</th>
                <th></th>
            </tr>
            </thead>
        </table>
    </card>
</template>

<script>
    import 'datatables.net-dt/css/dataTables.dataTables.css';
    import 'datatables.net-buttons-dt/js/buttons.dataTables'
    import 'datatables.net-buttons-dt/css/buttons.dataTables.css';
    import 'datatables.net-datetime/css/dataTables.dateTime.scss';
    import DataTable from 'datatables.net-dt';
    import 'datatables.net-select-dt/js/select.dataTables';
    import 'datatables.net-select-dt/css/select.dataTables.css';
    import Editor from '@datatables.net/editor-2025-01-20-dt/js/editor.dataTables';
    import '@datatables.net/editor-2025-01-20-dt/css/editor.dataTables.css'
    import Card from "@/includes/Card.vue";
    import JSZip from 'jszip';

    export default {
        components: {Card},
        data() {
            return {
                dataTable: null,
                editor: null,
            };
        },
        mounted() {
            DataTable.Buttons.jszip(JSZip);

            this.editor = new Editor({
                ajax: '/api/transactions-dt',
                table: '#transactionsTable',
                fields: [
                    { name: 'transactions.id' },
                    { name: 'transactions.amount'},
                    { name: 'transactions.date', type: 'datetime'},
                ],
            });

            this.dataTable = new DataTable('#transactionsTable', {
                ajax: {
                    url: '/api/transactions-dt',
                    type: 'POST',
                },
                "columns": [
                    { data: "accounts.name" },
                    { data: "transactions.id" },
                    { data: "transactions.amount" },
                    { data: "accounts.currency" },
                    { data: "transactions.date" },
                    {
                        data: null,
                        className: 'dt-center editor-delete',
                        defaultContent: '<button><i class="fa-solid fa-trash"></i></button>',
                        orderable: false,
                    },
                ],
                searching: false,
                layout: {
                    topStart: {
                        buttons: [
                            {
                                extend: 'collection',
                                text: 'Export',
                                buttons: [
                                    {
                                        extend: 'excel',
                                        text: 'Excel',
                                        title: null,
                                        filename: 'Transactions',
                                        exportOptions: {
                                            format: {
                                                body: function (data, row, column) {
                                                    if (column === 4) {
                                                        return data + ' 00:00:00';
                                                    }

                                                    if (column === 5) {
                                                        return '';
                                                    }

                                                    return data;
                                                },
                                            }
                                        },
                                    },
                                    'pdf',
                                ],
                            }
                        ]
                    }
                },
            });

            this.dataTable.on('click', 'td', (event) => {
                const columnIndex = $(event.currentTarget).index();

                if ([1, 2, 4].includes(columnIndex)) {
                    this.editor.inline(event.currentTarget, {
                        onBlur: 'submit',
                    });
                }
            });

            this.dataTable.on('click', 'td.editor-delete button', (e) => {
                this.editor.remove(e.target.closest('tr'), {
                    title: 'Delete record',
                    message: 'Are you sure you wish to remove this record?',
                    buttons: 'Delete'
                });
            });

            this.editor.on('submitComplete', () => {
                Bus.emit('refetch-sums');
                Bus.emit('refetch-chart');
            });

            Bus.on('refetch-transactions', () => this.dataTable.ajax.reload());
        }
    }
</script>

<style scoped>
    @import '~@fortawesome/fontawesome-free/css/all.min.css';
</style>
