<template>
    <div class="card card-order">
        <div class="card-header"><strong>Create Order</strong></div>
        <div class="card-body">
            <form class="m-4" @submit.prevent="store">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="meal">Meal</label>
                        <select class="custom-select" id="meal" v-model="mealSelected">
                            <option>Select Meal</option>
                            <option v-for="meal in meals" :value="meal.id">
                                {{ meal.description }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="users">Users</label>
                        <select class="custom-select" id="users" multiple v-model="usersSelected">
                            <option v-for="user in users" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="quantity">Quantity</label>
                        <select class="custom-select" id="quantity" v-model="quantity">
                            <option>Select Quantity</option>
                            <option v-for="i in 5" :value="i">
                                {{ i }}
                            </option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</template>

<script>

    import vSelect from 'vue-select'

    Vue.component('v-select', vSelect)


    export default {
        props: ['propMeals', 'propUsers'],
        data: function () {
            return {
                meals: this.propMeals,
                users: this.propUsers,
                mealSelected: '',
                quantity: '',
                usersSelected: []
            }
        },
        methods: {
            store() {
                axios.post(route('orders.store'), {
                    quantity: this.quantity,
                    meal_id: this.mealSelected,
                    user_id: this.usersSelected,
                }).then((response) => {
                    this.$root.$emit('orderCreated', {data: response.data, status: response.status});
                }).catch(function (error) {
                    console.log(error);
                });
            },
        }
    }
</script>