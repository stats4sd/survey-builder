<template>
    <div class="container">
        <b-card header-bg-variant="dark" class="border-0">
            <template #header>
                <h2>Form Overview</h2>
            </template>
            <div class="px-5">
                <b-table :items="forms" :fields="formFields" striped bordered>

<!--                    <template #cell(publishedVersion)="row">-->
<!--                        <span class="d-flex justify-content-between">-->
<!--                            <span>{{row.item.publishedVersion}}</span>-->
<!--                            <a href="#">Collect Data</a>-->
<!--                        </span>-->
<!--                    </template>-->

<!--                    <template #cell(draftVersion)="row">-->
<!--                        <span class="d-flex justify-content-between">-->
<!--                            <span>{{row.item.draftVersion}}</span>-->
<!--                            <a href="#">Test Form</a>-->
<!--                        </span>-->
<!--                    </template>-->


                    <template #cell(actions)="row">
                        <b-dropdown text="Options" no-flip>
                            <b-dropdown-header>Published Version</b-dropdown-header>
                            <b-dropdown-item>Collect Data</b-dropdown-item>
                            <b-dropdown-item>View Collected Data</b-dropdown-item>
                            <b-dropdown-header>Draft Version</b-dropdown-header>
                            <b-dropdown-item>Edit Form</b-dropdown-item>
                            <b-dropdown-item>Test Form</b-dropdown-item>
                            <b-dropdown-item>Publish New Version</b-dropdown-item>
                            <b-dropdown-header>Management</b-dropdown-header>
                            <b-dropdown-item>Manage Users</b-dropdown-item>
                        </b-dropdown>
                    </template>
                </b-table>
            </div>
        </b-card>
    </div>

</template>

<script>
export default {
    name: "DemoTest",
    data() {
        return {
            forms: [
                {
                    formName: 'example1',
                    createdAt: '20/6/2022',
                    submissions: 5,
                    publishedVersion: "V4",
                    draftVersion: "V6",
                }
            ],
            formFields: [
                'formName',
                'createdAt',
                'submissions',
                'publishedVersion',
                'draftVersion',
                {
                    key: 'actions',
                    label: '',
                }
            ]
        }
    }
}
</script>

<style scoped>

</style>
