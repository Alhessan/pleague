<template>
    <tr>

        <td >{{ prediction.team_name}}</td>
        <td ><span>{{prediction["prediction"]}}</span><span>%</span></td>

    </tr>
</template>

<script>
    export default {
        name: "Prediction",
        props:{prediction:{}}
    }
</script>

<style scoped>

</style>
