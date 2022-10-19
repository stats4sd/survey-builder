<template>
    <a class="border-0 btn d-flex align-items-center justify-content-center" :href="url" target="_blank">
        <HelpCircleOutline/>
    </a>
</template>

<script>
const baseDocsUrl = process.env.MIX_DOCS_URL

import HelpCircleOutline from 'vue-material-design-icons/HelpCircleOutline.vue';


export default {
    name: "HelpLink",
    components: {
        HelpCircleOutline,
    },
    props: {
        section: '',
        heading: '',
    },
    computed: {
        url() {
            return `${baseDocsUrl}/${this.section}#${this.heading}`
        }
    }

}
</script>

<style scoped>

</style>
