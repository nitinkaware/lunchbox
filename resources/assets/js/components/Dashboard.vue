<template>
    <div>
        <div v-for="user in users" class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">{{ user.name }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Total Payable Amount: {{ user.owe }}</h6>
                <p class="card-text">
                    Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['users'],
        data: function () {
            return {}
        },
        mounted() {

        },
        methods: {}
    }
</script>