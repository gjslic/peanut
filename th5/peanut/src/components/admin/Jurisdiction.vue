<template>
    <div class="contanir">
        <el-row><h2 style="text-align:center">权限管理</h2></el-row>
        <el-row :gutter="24">
            <el-col :span="1" style="text-align:center">
                 <el-tooltip class="item" effect="dark" content="点击查看所有权限" placement="top">
                        <el-button type="primary" circle @click="getJurisditionList('权限列表')" icon="el-icon-view"></el-button>
                </el-tooltip>
            </el-col>
            <el-col :span="10" style="text-align:center"><h4>角色菜单</h4></el-col>
            <el-col :span="10" style="text-align:center"><h4>{{jurTitle}}</h4></el-col>
        </el-row>
        <el-table :data="tableData" highlight-current-row border  style="width: 50%;float:left">
            <el-table-column align="center" prop="id" label="ID"></el-table-column>
            <el-table-column align="center" prop="role_name" label="角色"></el-table-column>
            <el-table-column align="center" label="权限配置">
                <template slot-scope="scope">
                    <el-tooltip class="item" effect="dark" content="点击查看当前拥有权限" placement="top">
                        <el-button icon="el-icon-search" @click="getJurisdition(scope.row)" circle></el-button>
                    </el-tooltip>
                    <el-tooltip class="item" effect="light" content="点击修改权限" placement="top">
                        <el-button type="primary" v-if="scope.row.role_name == '超级管理员'" disabled @click="getNowJurisdition(scope.row)" icon="el-icon-edit" circle></el-button>
                        <el-button type="primary" v-else  @click="getNowJurisdition(scope.row)" icon="el-icon-edit" circle></el-button>
                    </el-tooltip>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog :title="jurTitle" destroy-on-close :visible.sync="editJurDialog" width="50%" :before-close="handleClose">
            <template >
                <el-transfer v-model="editJurVal" :titles="transferTitle" :data="editJurData"></el-transfer>
            </template>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editJurDialog = false">取 消</el-button>
                <el-button type="primary" @click="editJurisdition">确 定</el-button>
            </span>
        </el-dialog>
        <el-tree :data="menuData" :props="defaultProps"></el-tree>
    </div>
</template>
<script>
    export default {
        name: 'Jurisdic',
        data() {
            return {
                url: 'http://localhost/th5/public/admin_jurisdiction/Jurisdition/',
                tableData: [], // 左侧菜单容器
                menuData: [], // 右侧权限列表容器
                allMenuArr: [], // 初始菜单
                editJurDialog: false,
                editJurData: [], // 穿梭框左侧容器
                editJurVal: [],  // 穿梭框右侧容器
                nowRoleId: '',   // 当前选中角色id
                transferTitle:['所有权限','现有权限'],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                },
                jurTitle: '', // 权限列表标题
                currentRow:'', //当前选中行
                selectIndex: '' // 当前选中行下标
            }
        },
        mounted () {
            let that = this;
            that.getJurisditionList('权限列表')
        },
        methods : {
           
            /**
             * [ 获取所有权限列表]
             */
            getJurisditionList(title){
                this.jurTitle = title;
                this.$fetch(this.url+'getRoleList',{
                }).then(res => {
                    if(res.code == 1){
                        this.tableData = res.data.roleList;
                        this.allMenuArr.push(res.data.jurList);
                        this.menuData = [];
                        this.getTree(res.data.jurList,this.menuData);
                    }
                }).catch(error =>{
                    console.log(error)
                })
            },
            /**
             * [getJurisdition 查看角色拥有权限列表]
             */
            getJurisdition (row) {
                var promise = new Promise((resolve,reject)=>{
                    this.$fetch(this.url+'getJurisditionList',{nowId:row.id})
                    .then((res)=>{
                        if(res.data){
                            let jurArr = res.data[0].menu_id;
                            let newArr = jurArr.split(',');
                            resolve(newArr.map(Number));
                        }else{
                            this.menuData = [];
                            this.jurTitle = '暂无权限，请配置权限'
                        }
                    })
                    .catch((err)=>{
                        console.log(err)
                    })
                });
                promise.then((res)=>{
                    this.$fetch(this.url+'getJurisditionTree',{'idArr': JSON.stringify(res)})
                    .then((response)=>{
                        //只有当url1请求到数据后才会调用url2，否则等待
                        if(response.code == 1){
                            this.jurTitle = row.role_name+'权限';
                            this.menuData = [];
                            this.getTree(response.data,this.menuData);
                        }else{
                            alert(response.msg)
                        }
                    })
                    .catch((err)=>{
                        console.log(err)
                    })
                })
            },
            /**
             * [getTree 树状结构生成]
             */
             getTree(jurArr,menuArr){
                for(let item in jurArr){
                    if(jurArr[item].fid == 0){
                        // 一级菜单
                        let firstArr = {
                            'id': jurArr[item].id,
                            'label':  jurArr[item].label,
                            children:[]
                        };
                        for (let x in jurArr){
                            if(jurArr[x].fid == jurArr[item].id){
                                // 一级菜单对应的二级菜单
                                firstArr.children.push({
                                    'id': jurArr[x].id,
                                    'label':  jurArr[x].label
                                })
                            }
                        }
                        menuArr.push(firstArr)
                    }
                }
            },
            /**
             * [getNowJurisdition 穿梭框获取当前角色权限]
             */
            getNowJurisdition (row) {
                this.editJurDialog = true;
                let menuArr = this.allMenuArr[0];
                const leftData = []; // 定义穿梭框左侧容器
                this.nowRoleId = row.id;
                this.$fetch(this.url+'getJurisditionList',{nowId:row.id})
                .then(res => {
                    if(res.data){
                        let nowJurArr = res.data[0].menu_id;
                        let newArr = nowJurArr.split(',');
                        let jurArr = newArr.map(Number);
                        const leftData = []; // 定义穿梭框左侧容器
                        let rightData = [];
                        for(let x in menuArr){
                            // 定义穿梭狂左侧数据格式，插入数据
                            leftData.push({
                                key: menuArr[x].id,
                                label: menuArr[x].label
                            })
                        }
                        // 循环菜单数组和拥有权限，填充进右侧容器
                        for(let i = 0; i < menuArr.length; i++){
                            for(let j = 0; j < jurArr.length; j++){
                                if(menuArr[i].id == jurArr[j]){
                                    rightData.push(jurArr[j])
                                }
                            }
                        }
                        this.editJurData = leftData
                        this.editJurVal = rightData
                    }else{
                        for(let x in menuArr){
                            // 定义穿梭狂左侧数据格式，插入数据
                            leftData.push({
                                key: menuArr[x].id,
                                label: menuArr[x].label
                            })
                        }
                        this.editJurData = leftData;
                        this.$message({
                            showClose: true,
                            message: res.msg,
                            type: 'error'
                        });
                    }
                    
                }).catch(err => {   
                    console.log(err)
                });
            },
            /**
             * [handleClose 修改弹框关闭事件]
             */
            handleClose(done) {
                this.$confirm('是否保留更改？')
                .then(_ => {
                    done();
                    this.editJurisdition()
                })
                .catch(_ => {
                    done();
                });
            },
            /**
             * [editJurisdition 修改角色权限]
             */
            editJurisdition(){
                console.log(this.nowRoleId)
                let nowJur = this.editJurVal;
                this.$fetch(this.url+'editJurisdition',{'nowJurArr': JSON.stringify(nowJur),'nowRoleId': this.nowRoleId})
                .then(res => {
                    let msgType = '';
                    if(res.code == 1){
                        msgType = 'success'
                    }else{
                        msgType = 'error'
                    }
                    this.$message({
                        showClose: true,
                        message: res.msg,
                        type: msgType
                    });
                    this.editJurDialog = false;
                }).catch(err => {
                    console.log(err)
                })
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
