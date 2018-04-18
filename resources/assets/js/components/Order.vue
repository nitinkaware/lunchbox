<template>
    <div class="card card-order">
        <div class="card-header"><strong>Orders History</strong></div>
        <div class="card-body">
            <div class="row" v-for="order in orders">
                <div class="col-md-1"><img src="https://bootdey.com/img/Content/user_3.jpg"
                                           class="media-object img-thumbnail"></div>
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-12">
                            <span><strong>{{ order.meal.description }}</strong></span>

                            <p>Quantity : {{ order.quantity }}, You Pay : {{ order.owes }}</p>
                            <small>This order is shared between: {{ order.shared_between }}</small>
                            <div class="mr-1">
                                <a data-placement="top"
                                   class="btn btn-success btn-xs glyphicon glyphicon-ok"
                                   href="#" title="View">Pay</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            Order placed {{ order.created_at }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data: function () {
            return {
                orders: {},
            }
        },
        mounted() {
            this.orders = this.getOrders();
        },
        methods: {
            getOrders(page = 1) {
                axios.get(route('orders.index')).then((response) => {
                    this.orders = response.data.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
        }
    }
</script>