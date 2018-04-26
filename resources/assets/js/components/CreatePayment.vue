<template>
    <div>
        <form class="m-4" @submit.prevent="store">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="users">Pay To</label>
                    <select class="custom-select" id="users" v-model="to_user_id">
                        <option value="">Select</option>
                        <option v-for="user in users" :value="user.id">
                            {{ user.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="amount">Amount</label>
                    <input type="number" class="form-control" id="amount" placeholder="Enter amount" v-model="amount">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-primary" @click="cancel">Cancel</button>
        </form>
    </div>
</template>

<script>

    import vSelect from 'vue-select';

    export default {
        props: ['users'],
        data: function () {
            return {
                amount: '',
                to_user_id: ''
            }
        },
        methods: {
            store() {
                axios.post(route('payments.store'), {
                    amount: this.amount,
                    to_user_id: this.to_user_id,
                }).then((response) => {
                    this.$root.$emit('paymentCreated', {data: response.data, status: response.status});
                }).catch(function (error) {
                    console.log(error);
                });
            },
            cancel() {
                this.$root.$emit('paymentCancelled');
            }
        }
    }
</script>