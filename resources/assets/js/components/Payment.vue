<template>
    <div>
        <div class="card">
            <div class="card-header"><strong>Payments</strong></div>
            <div class="card-body">
                <div v-if="!creating">
                    <button type="button" class="btn btn-primary m-2" @click="creating = true">Make Payment</button>
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Amount Paid</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(payment, i) in payments">
                            <th scope="row">{{ i + 1 }}</th>
                            <td>{{ payment.name }}</td>
                            <td>{{ payment.paid_amount }}</td>
                            <td>Pay</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <create-payment :users="payments" v-if="creating"></create-payment>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['propPayments'],
        data: function () {
            return {
                payments: {},
                creating: false
            }
        },
        methods: {
            init(payments) {
                this.payments = payments;
            },
            getPayments() {
                axios.get(route('payments.index')).then((response) => {
                    this.init(response.data.data);
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.init(this.propPayments);
        },
        created() {
            this.$root.$on('paymentCreated', () => {
                this.creating = false;
                this.getPayments();
            });

            this.$root.$on('paymentCancelled', () => {
                this.creating = false;
            });
        }
    }
</script>