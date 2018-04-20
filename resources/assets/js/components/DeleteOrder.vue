<template>
    <div class="mr-1">
        <a data-placement="top" @click="destroy"
           class="btn btn-danger btn-xs glyphicon glyphicon-ok" :class="getClass()"
           href="#" title="View">Delete</a>
    </div>
</template>

<script>

    export default {
        props: ['order'],
        data: function () {
            return {
                destroying: false,
            }
        },
        methods: {
            getClass() {
                return {
                    'disabled': this.destroying,
                }
            },
            destroy() {
                if (!confirm('Are you sure?')) {
                    return;
                }

                this.destroying = true;
                axios.delete(route('orders.destroy', this.order.id)).then((response) => {
                    this.$root.$emit('orderDeleted', {status: response.status});
                    this.destroying = false;
                }).catch((error) => {
                    this.destroying = false;
                    console.log(error);
                });
            }
        },
    }
</script>