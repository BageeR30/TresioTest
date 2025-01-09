<template>
    <card>
        <template #title>
            <h3>List of bank accounts</h3>
        </template>

        <table>
            <thead>
                <tr>
                    <th>Banks</th>
                    <th>Currency</th>
                    <th>Starting balance</th>
                    <th>End balance</th>
                    <th>End balance (CHF)</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(row, index) in tableData" :key="index">
                    <td
                        v-if="editingCell !== `${index}-bank`"
                        contenteditable="true"
                        @click="startEditing(`${index}-bank`)"
                    >
                        {{ row.bank }}
                    </td>
                    <td v-else>
                        <input
                            type="text"
                            v-model="row.bank"
                            @blur="stopEditing()"
                            @keyup.enter="stopEditing()"
                            ref="tableInput"
                        />
                    </td>

                    <td>{{ row.currency }}</td>

                    <td
                        v-if="editingCell !== `${index}-starting-balance`"
                        contenteditable="true"
                        @click="startEditing(`${index}-starting-balance`)"
                    >
                        {{ row.startingBalance }}
                    </td>
                    <td v-else>
                        <input
                            type="number"
                            v-model="row.startingBalance"
                            @blur="stopEditing()"
                            @keyup.enter="stopEditing()"
                            ref="tableInput"
                        />
                    </td>

                    <td>{{ round(row.endBalance + Number.parseFloat(row.startingBalance)) }}</td>

                    <td>{{ round((row.endBalance + Number.parseFloat(row.startingBalance)) * getRate(row.currency)) }}</td>
                </tr>
            </tbody>
        </table>
    </card>
</template>

<script>
import Card from "@/includes/Card.vue";
import _ from "lodash";

export default {
    components: {Card},
    props: {
        transactions: Array,
        rates: Object,
    },
    data() {
        return {
            tableData: [],
            editingCell: null,
        };
    },
    methods: {
        startEditing(cell) {
            this.editingCell = cell;

            this.$nextTick(() => {
                this.$refs.tableInput[0].focus();
            });
        },
        stopEditing() {
            this.editingCell = null;
        },
        round(value) {
            return +value.toFixed(2);
        },
        getRate(currency) {
            return {...this.rates, CHF: 1}[currency];
        }
    },
    watch: {
        transactions() {
            let grouped = _.groupBy(this.transactions, 'account');

            let result = {};
            _.forEach(grouped, (value, key) => {
                result[key] = {
                    bank: key,
                    currency: value[0].currency,
                    startingBalance: 0,
                    endBalance: _.sumBy(value, 'amount'),
                };
            });

            this.tableData = Object.values(result);
        },
        tableData() {
            this.$emit('updated', this.tableData);
        }
    },
};
</script>

<style scoped>
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    input {
        width: 100%;
        border: none;
        outline: none;
    }
</style>
