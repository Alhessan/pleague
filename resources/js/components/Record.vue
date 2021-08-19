<template>


        <tr>

           <td colspan="2">{{ record.team.name }}</td>
           <td class="col-md-2">{{ record.PTS }}</td>
           <td class="col-md-1">{{ record.P }}</td>
           <td class="col-md-1">{{ record.W }}</td>
           <td class="col-md-1">{{ record.D }}</td>
           <td class="col-md-1">{{ record.L }}</td>
           <td class="col-md-2">{{ record.GD }}</td>
        </tr>

</template>

<script>
    export default {
        name: "Record",
        props: ['record'],

    }
</script>

<style scoped>

</style>
