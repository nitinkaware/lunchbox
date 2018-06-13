<template>
    <div>
        <div class="card">
            <div class="card-header"><strong>Meals Summary</strong></div>
            <div class="card-body">
                <h3>Total Order Amount: {{ orderAmount }}</h3>
                <h3>Total Paid: {{ totalPaid }}</h3>
                <h3>Total Owe: {{ totalOwe }}</h3>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Meal</th>
                        <th scope="col">Total Owe</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(meal, i) in meals">
                        <th scope="row">{{ i + 1 }}</th>
                        <td>{{ meal.description }}</td>
                        <td>{{ meal.owe }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['meals', 'paid'],
        data: function () {
            return {}
        },
        created() {
            console.log(this.meals);
        },
        computed: {
            orderAmount: function () {
                return collect(this.meals).sum('owe').toFixed(2);
            },
            totalPaid: function () {
                return this.paid;
            },
            totalOwe: function () {
                return (collect(this.meals).sum('owe') - this.paid).toFixed(2);
            }
        },
    }
</script>