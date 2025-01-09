<template>
    <div class="card">
        <slot name="title"/>

        <div class="card-body">
            <slot/>
        </div>
    </div>
</template>

<script>
export default {
}
</script>
<style scoped>
    .card {
        text-align: left;
        width: 100%;
    }

    .card-body {
        padding: 20px;
        background: white;
    }
</style>
