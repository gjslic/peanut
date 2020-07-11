<!-- @tlh公告管理 -->
<template>
  <div>
    <!-- 添加按钮 -->
    <el-row style="margin-bottom:20px">
      <el-button type="primary"  @click="addDialogForm =true">添加</el-button>
    </el-row>


    <!-- 添加弹出框 -->
    <el-dialog title="添加公告" :visible.sync="addDialogForm">
        <el-form :model="form">
        <el-form-item label="发布者" :label-width="formLabelWidth">
            <el-input v-model="form.publish" disabled='disabled' maxlength="10"></el-input>
        </el-form-item>
        <el-form-item label="公告描述" :label-width="formLabelWidth">
            <el-input v-model="form.noticeCont" placeholder="请输入20个字以内的公告描述，支持中英文数字"  maxlength="20" onkeyup="value=value.replace(/[^\w\u4E00-\u9FA5]/g, '')">公告描述</el-input>
        </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
        <el-button @click="addDialogForm = false">取 消</el-button>
        <el-button type="primary" @click="addRole">确 定</el-button>
        </div>
    </el-dialog>


    <!-- 编辑弹出框 -->
    <el-dialog title="编辑公告" :visible.sync="updateDialogForm">
        <el-form :model="updateForm">
        <el-form-item label="公告ID" :label-width="formLabelWidth">
            <el-input v-model="updateForm.id" placeholder="ID" disabled='disabled'></el-input>
        </el-form-item>
        <el-form-item label="发布者" :label-width="formLabelWidth">
            <el-input v-model="updateForm.nowPublish" placeholder="" maxlength="10" disabled='disabled'></el-input>
        </el-form-item>
        <el-form-item label="公告描述" :label-width="formLabelWidth">
            <el-input v-model="updateForm.nowNoticeCont" placeholder="请输入20个字以内的中文公告描述，支持中英文数字" maxlength="20" onkeyup="value=value.replace(/[^\w\u4E00-\u9FA5]/g, '')">公告描述</el-input>
        </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
        <el-button @click="updateDialogForm = false">取 消</el-button>
        <el-button type="primary" @click="updateRole">确 定</el-button>
        </div>
    </el-dialog>


    <!-- 公告数据渲染 -->
    <el-table :data="tableData.slice((currentPage-1)*pagesize,currentPage*pagesize)" style="width: 100%">
      <el-table-column prop="id" label="ID" width="180"></el-table-column>
      <el-table-column prop="notice_Publisher" label="发布者" width="180"></el-table-column>
      <el-table-column prop="notice_cont" label="公告描述"></el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="block">
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage"
        :page-size="pagesize"
        :page-sizes="[4, 8, 16, 32]"
        layout="total, sizes, prev, pager, next, jumper"
        :total="tableData.length"
      ></el-pagination>
    </div>
  </div>
</template>

<script>
import {request} from "../../network/request";//引入axios请求
import {getData} from '../../network/home'//引入axios封装的函数
import {sendParam} from '../../network/home'//引入axios封装的函数
export default {
    data() {
        return {
            tableData: [],
            addDialogForm: false,  //添加弹出框初始状态
            formLabelWidth: '80px',
            form: {
                publish: JSON.parse(localStorage.getItem("staffAcc")),  
                noticeCont: '' 
            },
            updateDialogForm: false,
            updateForm: {
                id:'',
                nowPublish: JSON.parse(localStorage.getItem("staffAcc")),  
                nowNoticeCont: '' 
            },
            currentPage: 1, // 初始页
            pagesize: 4 // 每页的数据
        }
    },
    mounted(){
        this.getInfo()    
    },
    methods: {
        //获取公告
        getInfo(){
            sendParam('anotice/index/index','')
            .then(res => {            
                this.tableData = res.data.data;
            })
            .catch(err => {  
                
            })
        },
        //添加公告
        addRole(){
            if (!this.form.noticeCont) {
                this.$message.error("请输入公告内容");
                return;
            }
            let url = "anotice/index/add";
            let data = {
                publish:this.form.publish,
                noticeCont:this.form.noticeCont
            };
            sendParam(url,data).then(res => {
                if(res.data.code==1){
                    this.$message.success(res.data.msg);
                        this.tableData = res.data.data;
                }else{
                    this.$message.error(res.data.msg);
                }
            }).catch(err => {  
                
            })
        },
        //修改公告按钮
        handleEdit(index, row) {
            this.updateDialogForm = true;
            this.updateForm.id = row.id;
            this.updateForm.nowPublish = row.notice_Publisher;
            this.updateForm.nowNoticeCont = row.notice_cont;      
        },
        //确认修改公告
        updateRole(){
            if (!this.updateForm.nowNoticeCont) {
                this.$message.error("请输入公告内容");
                return;
            }
            let url = "anotice/index/update";
            let data = {
                id:this.updateForm.id,
                notice_Publisher:this.updateForm.nowPublish,
                notice_cont:this.updateForm.nowNoticeCont
            };
            sendParam(url,data).then(res => {
                if(res.data.code==1){
                    this.$message.success(res.data.msg);
                        this.tableData = res.data.data;
                }else{
                    this.$message.error(res.data.msg);
                }
            }).catch(err => {  
                
            })
        },
        //删除公告
        handleDelete(index, row) {
            this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
            })
            .then(() => {
            this.deleteRole(row);
            }).catch(() => {
            this.$message({
                type: 'info',
                message: '已取消删除'
            });          
            });
        },
        // 封装删除公告函数
        deleteRole(row){
            let url = "anotice/index/delete";
            let data = {
                id:row.id,
                notice_Publisher:row.notice_Publisher,
                notice_cont:row.notice_cont
            };
            sendParam(url,data).then(res => {
                if(res.data.code==1){
                    this.$message.success(res.data.msg);
                        this.tableData = res.data.data;
                }else{
                    this.$message.error(res.data.msg);
                }
            }).catch(err => {  
                
            })
        },
        // 初始页currentPage、初始每页数据数pagesize和数据data
        handleSizeChange: function(size) {
            this.pagesize = size; //每页下拉显示数据
        },
        handleCurrentChange(currentPage) {
            this.currentPage = currentPage; //点击第几页
        },









    }
};
</script>

<style scope>
.el-select .el-input {
  width: 130px;
}
.input-with-select .el-input-group__prepend {
  background-color: #fff;
}
.block {
  margin: 10px 0;
}
.user_avator {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}
</style>