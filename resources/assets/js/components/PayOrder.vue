<template>
    <div class="mr-1">
        <a data-placement="top" @click="alert"
           class="btn btn-success btn-xs glyphicon glyphicon-ok" :class="getClass()"
           href="#" title="View">{{ this.order.paid_at !== null ? 'Paid' : 'Pay'}}</a>
    </div>
</template>

<script>

    export default {
        props: ['propOrder'],
        data: function () {
            return {
                paying: false,
                order: this.propOrder,
            }
        },
        computed: {
            idPaid: function () {
                return this.order.paid_at !== null;
            }
        },
        methods: {
            getClass() {
                return {
                    'disabled': this.paying,
                    'btn-danger': this.order.paid_at === null,
                    'btn-success': this.order.paid_at !== null,
                }
            },
            alert(e) {
                this.paying = true;
            }
        },
    }
</script>