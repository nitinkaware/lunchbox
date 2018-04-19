<template>
    <div>
        <div class="card card-order" v-if="!creating">
            <div class="card-header"><strong>Orders History</strong></div>
            <div class="card-body">
                <div class="row mb4">
                    <button type="button" class="btn btn-primary m-4" @click="create">Create Order</button>
                </div>
                <div class="row" v-for="order in orders">
                    <div class="col-md-1"><img src="https://bootdey.com/img/Content/user_3.jpg"
                                               class="media-object img-thumbnail"></div>
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <span><strong>{{ order.meal.description }}</strong></span>

                                <p>Quantity : {{ order.quantity }}, You Pay : {{ order.owes }}</p>
                                <small>This order is shared between: {{ order.shared_between }}</small>
                                <pay-order :propOrder="order"></pay-order>
                            </div>
                            <div class="col-md-12">
                                Order placed {{ order.created_at }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <create-order :prop-meals="meals" :prop-users="users" v-if="creating"></create-order>
    </div>
</template>

<script>

    import PayOrder from './PayOrder';
    Vue.component('pay-order', PayOrder);

    export default {
        data: function () {
            return {
                orders: {},
                users: {},
                meals: {},
                creating: false
            }
        },
        mounted() {
            this.orders = this.getOrders();
        },
        created() {
            this.$root.$on('orderCreated', (response) => {
                this.creating = false;
                this.getOrders();
            });
        },
        methods: {
            getOrders(page = 1) {
                axios.get(route('orders.index')).then((response) => {
                    this.orders = response.data.data;
                    this.meals = response.data.meals;
                    this.users = response.data.users;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            create() {
                this.creating = true;
            }
        }
    }
</script>