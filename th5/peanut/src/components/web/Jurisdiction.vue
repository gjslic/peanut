<template>
    <div class="contanir">
        <h1>权限管理</h1>
        <el-table :data="tableData" style="width: 50%;float:left">
            <el-table-column
                prop="ID"
                label="ID"
                width="180">
            </el-table-column>
            <el-table-column
                prop="role"
                label="角色"
                width="180">
            </el-table-column>
        </el-table>
        <el-tree :data="data" show-checkbox node-key="id" :default-expanded-keys="[2, 3]" :default-checked-keys="[5]"></el-tree>

    </div>
</template>
<script>
    export default {
        name: 'Jurisdic',
        data() {
            return {
                tableData: [{
                    ID: '1',
                    role: '超级管理员',
                }, {
                    ID: '2',
                    role: '普通管理员',
                }, {
                    ID: '3',
                    role: '业务员',
                }, {
                    ID: '4',
                    role: '客服',
                }],
                data: [{
                    id: 1,
                    label: '一级 2',
                    children: [{
                        id: 3,
                        label: '二级 2-1',
                        children: [{
                        id: 4,
                        label: '三级 3-1-1'
                        }, {
                        id: 5,
                        label: '三级 3-1-2',
                        disabled: true
                        }]
                    }, {
                        id: 2,
                        label: '二级 2-2',
                        disabled: true,
                        children: [{
                        id: 6,
                        label: '三级 3-2-1'
                        }, {
                        id: 7,
                        label: '三级 3-2-2',
                        disabled: true
                        }]
                    }]
                }],
                defaultProps: {
                children: 'children',
                label: 'label'
                }
            }
        }
    } 
</script>
<style scoped>
    .el-tree {
        position: relative;
        cursor: default;
        background: #FFF;
        color: #606266;
        width: 50%;
        float: left;
    }
</style>
