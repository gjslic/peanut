<!-- @tlh角色管理 -->
<template>
  <div>
    <!-- 添加按钮 -->
    <el-row style="margin-bottom:20px">
      <el-button type="primary" @click="addDialogForm =true">添加</el-button>
    </el-row>


    <!-- 员工添加弹出框 -->
    <el-dialog title="添加角色" :visible.sync="addDialogForm">
      <el-form :model="form">
        <el-form-item label="角色名称" :label-width="formLabelWidth">
          <el-input v-model="form.roleName" placeholder="请输入10个字符以内的角色名字，支持中英文数字" maxlength="10" onkeyup="value=value.replace(/[^\w\u4E00-\u9FA5]/g, '')"></el-input>
        </el-form-item>
        <el-form-item label="角色描述" :label-width="formLabelWidth">
          <el-input v-model="form.roleDesc" placeholder="请输入20个字符以内的角色描述，支持中英文数字" maxlength="20" onkeyup="value=value.replace(/[^\w\u4E00-\u9FA5]/g, '')">角色描述</el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addDialogForm = false">取 消</el-button>
        <el-button type="primary" @click="addRole">确 定</el-button>
      </div>
    </el-dialog>


    <!-- 角色编辑弹出框 -->
    <el-dialog title="编辑角色" :visible.sync="updateDialogForm">
      <el-form :model="updateForm">
        <el-form-item label="角色ID" :label-width="formLabelWidth">
          <el-input v-model="updateForm.id" placeholder="ID" disabled="disabled"></el-input>
        </el-form-item>
        <el-form-item label="角色名称" :label-width="formLabelWidth">
          <el-input v-model="updateForm.nowRoleName" placeholder="请输入10个字以内的中文角色名字" maxlength="10"></el-input>
        </el-form-item>
        <el-form-item label="角色描述" :label-width="formLabelWidth">
          <el-input
            v-model="updateForm.nowRoleDesc"
            placeholder="请输入20个字以内的中文角色描述，支持中英文数字"
            maxlength="20"
            onkeyup="value=value.replace(/[^\w\u4E00-\u9FA5]/g, '')"
          >角色描述</el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="updateDialogForm = false">取 消</el-button>
        <el-button type="primary" @click="updateRole">确 定</el-button>
      </div>
    </el-dialog>


    <!-- 角色数据渲染 -->
    <el-table :data="tableData" style="width: 100%">
      <el-table-column prop="id" label="ID" width="180"></el-table-column>
      <el-table-column prop="role_name" label="角色名字" width="180"></el-table-column>
      <el-table-column prop="desc" label="角色描述"></el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>


<script>
import { request } from "../../network/request"; //引入axios请求
import { getData } from "../../network/home"; //引入axios封装的函数
import { sendParam } from "../../network/home"; //引入axios封装的函数
export default {
  data() {
    return {
      tableData: [],
      addDialogForm: false, //添加弹出框初始状态
      formLabelWidth: "80px",
      form: {
        roleName: "",
        roleDesc: ""
      },
      updateDialogForm: false,
      updateForm: {
        id: "",
        nowRoleName: "",
        nowRoleDesc: ""
      }
    };
  },
  mounted() {
    this.getRole();
  },
  methods: {
    //获取角色
    getRole() {
      sendParam("arole/index/index", "")
        .then(res => {
          this.tableData = res.data.data;
        })
        .catch(err => {});
    },
    //添加角色
    addRole() {
    if (!this.form.roleName) {
        this.$message.error("请输入角色名称");
        return;
    }
    if (!this.form.roleDesc) {
        this.$message.error("请输入角色描述");
        return;
    }
    let url = "arole/index/add";
    let data = {
    roleName: this.form.roleName,
    roleDesc: this.form.roleDesc
    };
    sendParam(url, data)
        .then(res => {
          if (res.data.code == 1) {
            this.$message.success(res.data.msg);
            this.tableData = res.data.data;
          } else {
            this.$message.error(res.data.msg);
          }
        })
        .catch(err => {});
    },
    //修改角色按钮
    handleEdit(index, row) {
      this.updateDialogForm = true;
      this.updateForm.id = row.id;
      this.updateForm.nowRoleName = row.role_name;
      this.updateForm.nowRoleDesc = row.desc;
    },
    //确认修改角色
    updateRole() {
    if (!this.updateForm.nowRoleName) {
        this.$message.error("请输入角色名称");
        return;
    }
    if (!this.updateForm.nowRoleDesc) {
        this.$message.error("请输入角色描述");
        return;
    }
    let url = "arole/index/update";
    let data = {
        id: this.updateForm.id,
        role_name: this.updateForm.nowRoleName,
        desc: this.updateForm.nowRoleDesc
    };
    sendParam(url, data)
        .then(res => {
          if (res.data.code == 1) {
            this.$message.success(res.data.msg);
            this.tableData = res.data.data;
          } else {
            this.$message.error(res.data.msg);
          }
        })
        .catch(err => {});
    },
    //删除角色
    handleDelete(index, row) {
      this.$confirm("此操作将永久删除该文件, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.deleteRole(row);
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },
    // 封装删除角色函数
    deleteRole(row) {
      let url = "arole/index/delete";
      let data = {
        id: row.id,
        role_name: row.role_name,
        desc: row.desc
      };
      sendParam(url, data)
        .then(res => {
          if (res.data.code == 1) {
            this.$message.success(res.data.msg);
            this.tableData = res.data.data;
          } else {
            this.$message.error(res.data.msg);
          }
        })
        .catch(err => {});
    }
  }
};
</script>