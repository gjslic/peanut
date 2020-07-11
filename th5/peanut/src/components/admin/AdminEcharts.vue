<template>
    <div>
        <EchartsUser/>
        <EchartsSale/>
    </div>
</template>

<script>
    import EchartsUser from "./EchartsUser";
    import EchartsSale from "./EchartsSale";
    export default {
        name: "AdminEcharts",
        components: {
            EchartsUser,
            EchartsSale
        }
    };
</script>

<style scoped>
</style>