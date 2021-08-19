<template>
    <tr>

        <td >{{ match.home_team_match.team.name }}</td>
        <td >{{ match.home_team_match.goals_count }}</td>

        <td >{{ match.guest_team_match.goals_count }}</td>
        <td >{{ match.guest_team_match.team.name }}</td>

    </tr>
</template>

<script>
    export default {
        name: "Match",
        props:{match:{}}
    }
</script>

<style scoped>

</style>
