<template>
	<div id="contanir" v-cloak>
		<!-- 页面头部操作框 -->
		<el-row type="flex" push="3" class="row-bg">
		  <el-col :span="3">
		  	<el-button type="primary"  @click="addDialogForm =true , dialogTitle = '添加员工信息'" >添加员工</el-button>
		  </el-col>
			<el-col :span="3">
				<el-popconfirm title="是否批量操作？删除后不可恢复" @onConfirm="batchDeleteStaff">
		  		<el-button type="danger" slot="reference"  :disabled= "delBtnDisabled">批量删除</el-button>
				</el-popconfirm>
		  </el-col>
		  <el-col :span="12">
				<el-select v-model="selectVal" placeholder="请选择" style="width:100px">
					<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value"></el-option>
				</el-select>
				<el-input placeholder="请输入内容" v-model="selectInfo" class="input-with-select"></el-input>
				<el-button icon="el-icon-search" @click="getStaff(allPages.nowPage,selectVal,selectInfo)"></el-button>
		  </el-col>
		</el-row>
		<!-- 员工添加弹出框 -->
		<el-dialog :title="dialogTitle" @close="closeFun" center :visible.sync="addDialogForm">
		  <el-form :model="form" status-icon :rules="rules" ref="MyForm" class="demo-ruleForm">
		    <el-form-item label="员工账号" prop="account" :label-width="formLabelWidth">
		      <el-input v-model="form.account" required="true" placeholder="请输入账号"></el-input>
		    </el-form-item>
		    <el-form-item label="员工密码" prop="password" :label-width="formLabelWidth">
		    	<el-input placeholder="请输入密码" v-model="form.password" show-password></el-input>
		    </el-form-item>
		    <el-form-item label="昵称" prop="name" :label-width="formLabelWidth">
		      <el-input v-model="form.name" placeholder="请输入昵称"></el-input>
		    </el-form-item>
		    <el-form-item label="手机号" prop="phone" :label-width="formLabelWidth">
		    	<el-input type="text" placeholder="请输入手机号" v-model="form.phone" maxlength="11" show-word-limit></el-input>
		    </el-form-item>
		    <el-form-item label="员工职位" prop="checkRole" :label-width="formLabelWidth" >
		      <el-select v-model="form.checkRole" placeholder="请选择员工职位" @focus="getRoleArr">
						<template v-for="(item , index) in roleArr"  >
		        	<el-option :key="index" v-if="item.role_name == '超级管理员'" disabled :value="item.role_name"></el-option>
							<el-option v-else :key="index" :label="item.role_name" :value="item.role_name"></el-option>
						</template>
					</el-select>
		    </el-form-item>
		    <el-form-item label="性别" prop="sex" :label-width="formLabelWidth">
		    	<el-radio v-model="form.sex" label="男">男</el-radio>
		  		<el-radio v-model="form.sex" label="女">女</el-radio>
		    </el-form-item>
		  </el-form>
		  <div slot="footer" class="dialog-footer">
		    <el-button @click="addDialogForm = false">取 消</el-button>
		    <el-button type="primary" :plain="true" @click="addStaff(form)">确 定</el-button>
		  </div>
		</el-dialog>
		<!-- 员工修改弹出框 -->
		<el-dialog :title="dialogTitle" @close="closeFun" center :visible.sync="editDialogForm">
		  <el-form :model="form" status-icon :rules="rules" ref="MyForm" class="demo-ruleForm">
				<el-upload	class="avatar-uploader" style="text-align:center"	action="https://jsonplaceholder.typicode.com/posts/"	:show-file-list="false"	:on-success="uploadSuccess"	:before-upload="beforeUploadImg">
					<img v-if="form.head_img" :src="form.head_img" class="avatar">
					<i v-else class="el-icon-plus avatar-uploader-icon"></i>
				</el-upload>
		    <el-form-item label="员工账号" prop="account" :label-width="formLabelWidth">
					<span>{{form.account}}</span>
		      <!-- <el-input v-model="form.account" required="true" placeholder="请输入账号"></el-input> -->
		    </el-form-item>
		    <el-form-item label="昵称" prop="name" :label-width="formLabelWidth">
		      <el-input v-model="form.name" placeholder="请输入昵称"></el-input>
		    </el-form-item>
		    <el-form-item label="手机号" prop="phone" :label-width="formLabelWidth">
		    	<el-input type="text" placeholder="请输入手机号" v-model="form.phone" maxlength="11" show-word-limit></el-input>
		    </el-form-item>
		    <el-form-item label="员工职位" prop="role_name" :label-width="formLabelWidth" >
		      <el-select v-model="form.role_name" placeholder="请选择员工职位" @focus="getRoleArr">
						<template v-for="(item , index) in roleArr"  >
		        	<el-option :key="index" v-if="item.role_name == '超级管理员'" disabled :value="item.role_name"></el-option>
							<el-option v-else :key="index" :label="item.role_name" :value="item.role_name"></el-option>
						</template>
					</el-select>
		    </el-form-item>
		    <el-form-item label="性别" prop="sex" :label-width="formLabelWidth">
		    	<el-radio v-model="form.sex" label="男">男</el-radio>
		  		<el-radio v-model="form.sex" label="女">女</el-radio>
		    </el-form-item>
		  </el-form>
		  <div slot="footer" class="dialog-footer">
		    <el-button @click="addDialogForm = false">取 消</el-button>
		    <el-button type="primary" :plain="true" @click="enterEdit(form)">确 定</el-button>
		  </div>
		</el-dialog>
		<!-- 员工信息展示区 -->
	 	<el-table ref="multipleTable" @selection-change="handleSelectionChange" highlight-current-row border style="width:100%" :data="staffArr" tooltip-effect="dark">
			<el-table-column align="center" type="selection"></el-table-column>
			<el-table-column align="center" prop="id" sortable label="ID">
			</el-table-column>
			<el-table-column align="center" label="账号">
				<template slot-scope="scope">
					<span>{{scope.row.account}}</span>
				</template>
			</el-table-column>
			<el-table-column align="center" label="昵称">
				<template slot-scope="scope">
					<span>{{scope.row.name}}</span>
				</template>
			</el-table-column>
			<el-table-column align="center" label="头像">
				<template slot-scope="scope">
					<el-image :src="scope.row.head_img" style="width:80px;border-radius:50%"></el-image>
				</template>
			</el-table-column>
			<el-table-column align="center" label="手机号" width="120px">
				<template slot-scope="scope">
					<span>{{scope.row.phone}}</span>
				</template>
			</el-table-column>
			<el-table-column align="center" label="职位">
				<template slot-scope="scope">
					<span>{{scope.row.role_name}}</span>
				</template>
			</el-table-column>
			<el-table-column align="center" label="性别">
				<template slot-scope="scope">
					<span>{{scope.row.sex}}</span>
				</template>
			</el-table-column>
			<el-table-column align="center" label="状态">
				<template slot-scope="scope">
					<span v-if="scope.row.state == 1">解锁</span>
					<span v-else-if="scope.row.state == 0">锁定</span>
				</template>
			</el-table-column>
			<el-table-column align="center" label="操作" width="240px">
				<template slot-scope="scope" v-if="scope.row.role_name != '超级管理员'" disabled >
					<el-button type="warning" icon="el-icon-lock" v-if="scope.row.state == 1" @click="editState(scope.row.id,scope.row)" circle></el-button>
					<el-button type="primary" icon="el-icon-unlock" v-else-if="scope.row.state == 0" @click="editState(scope.row.id,scope.row)" circle></el-button>
					<el-button type="primary" icon="el-icon-edit" @click="editStaff(scope.row)" circle></el-button>
					<el-popconfirm title="确认删除该用户？" icon="el-icon-info" iconColor="red" @onConfirm="delStaff(scope.row.id)">
						<el-button type="danger" slot="reference" icon="el-icon-delete" circle></el-button>
					</el-popconfirm>
					<el-popconfirm title="是否重置密码？" @onConfirm="resetPsw(scope.row.id)">
						<el-button type="danger" slot="reference" icon="el-icon-refresh" circle></el-button>
					</el-popconfirm>
				</template>
			</el-table-column>
		</el-table>
	  <el-pagination background style="text-align:center" @current-change="getStaff" layout="prev, pager, next" :page-size="8" :total="allPages.total"></el-pagination>
	</div>
</template>
<script>
	import {request} from "../../network/request";
	export default {
		name: 'StaffManage',
		data() {
			/**
			 * [账号正则：accReg => 6-10位英文+数字，英文开头]
			 * [密码正则：pswReg => 6-16位英文+数字,必须包含大小写字母和数字]
			 * [手机号正则：telReg => 13,15,17,18开头，11位数字]
			 * [昵称正则：nickNameReg => 中文、英文、数字包括下划线]
			 */
			let accReg = /^[a-zA-Z][a-zA-Z0-9_]{6,10}$/; 
			let pswReg = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/;
			let telReg = /^1[3578]\d{9}$/;
			let nickNameReg = /^[\u4E00-\u9FA5A-Za-z0-9_]{1,6}$/;
			// 账号正则校验
			let checkAcc = (rule, value, callback) => {
        if (!value) {
          return callback(new Error('账号不可为空'));
        }
        setTimeout(() => {
          if (value.match(accReg) == null) {
            callback(new Error('账号规则：6-10位英文+数字，英文开头'));
          } else {
              callback();
          }
        }, 1000);
			};
			// 密码正则校验
      let checkPsw = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'));
        } else {
          if (value.match(pswReg) == null) {
            callback(new Error('密码规则：8-10位英文+数字,必须包含大小写字母和数字'))
          }
          callback();
        }
			};
			// 昵称正则校验
			let checkName = (rule , value , callback) => {
				if(value === ''){
					callback(new Error('请创建昵称'));
				}else{
					if (value.match(nickNameReg) == null) {
            callback(new Error('昵称规则：中文、英文、数字包括下划线'))
          }
          callback();
				}
			}
			// 手机号正则校验
			let checkTel = (rule , value , callback) => {
				if(!value){
					callback(new Error('请输入手机号'));
				}else{
					if (value.match(telReg) == null) {
            callback(new Error('手机号规则：13,15,17,18开头，11位数字'))
          }
          callback();
				}
			};
			// 角色判断不为空
			let checkRole = (rule , value , callback) => {
				console.log(this.form.checkRole)
				if(this.form.checkRole === ''){
					callback(new Error('请选择职位'));
				}else{
          callback();
				}
			};
			// 性别判断是否选中
			let checkSex = (rule , value , callback) => {
				if(value === ''){
					callback(new Error('请选择性别'));
				}else{
          callback();
				}
			};
      return {
				url: 'http://localhost/th5/public/admin_staff/staff/',
				rules: {
          name: [
            { validator: checkName, trigger: 'blur' }
          ],
          account: [
            { validator: checkAcc, trigger: 'blur' }
          ],
          password: [
            { validator: checkPsw, trigger: 'blur' }
					],
					phone: [
            { validator: checkTel, trigger: 'blur' }
					],
					checkRole: [
            { validator: checkRole, trigger: 'blur' }
					],
					sex: [
            { validator: checkSex, trigger: 'blur' }
					]
				},
				delBtnDisabled: true,
				dialogTitle: '', // 弹框标题
      	selectInfo: '', //条件
      	selectVal: '', //条件搜索
				roleArr: [], // 职位列表
				staffArr:[],  //员工列表
				selectRow: [], //当前选中行
				addDialogForm: false,  //添加弹出框初始状态
				editDialogForm:false,  //修改弹出框初始状态
				// 添加框绑定值
				form: {
          name: '',    //昵称 
          account: '', // 添加账号
          password: '',// 添加密码
          phone: '',     // 添加电话
					head_img: 'https://dss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=1091405991,859863778&fm=26&gp=0.jpg', //图片路径
					role_name: '',
					checkRole: '',
          sex: '男', // 性别
          delivery: false,
				},
				formLabelWidth: '120px',
				// 搜索指引
				options: [
					{
						value: '1',
						label: '账号'
					}, {
						value: '2',
						label: '昵称'
					}, {
						value: '3',
						label: '手机号'
					}, {
						value: '4',
						label: '职位'
					}
				],
				// 分页
				allPages: {
					nowPage: 1,
					allPage: 0,
					total: 0
				}
      };
    },
    // 获取员工列表
    mounted() {
			let that = this;
			that.getStaff(1,'','');
    },
		methods: {
			/**
			 * [handleSelectionChange 复选框状态改变]
			 */
			handleSelectionChange(val){
				let arr = [];
				val.forEach((val, index) => {
					this.staffArr.forEach((v, i) => {// id 是每一行的数据id
						if(val.id == v.id){
							arr.push(val.id);
						}
					})
				})
				this.selectRow = arr;
				if(this.selectRow.length > 0){
					this.delBtnDisabled = false;
				}else{
					this.delBtnDisabled = true;
				}
			},
		
			/**
			 *[getStaff 获取员工列表]
			 */ 
			getStaff(num,searchType = '',searchInfo = ''){
				this.allPages.nowPage = num;
				this.staffArr = [];
				this.$post(this.url+'getStaffArr',{
					'type':searchType,
					'keyWord': searchInfo,
					'nowPage': num
				}).then(res => {
					this.allPages.total = res.count;
					this.staffArr = res.data;
				}).catch(err => {
					console.log(err)
				})
			},
			
			/**
			 * [addStaff 添加员工]
			 */
			addStaff(form){
				console.log(form)
				console.log(this.form.checkRole)
				this.$refs.MyForm.validate((valid) => {
          if (valid) {
						this.$post(this.url+'addStaff',{
							data: form
						}).then(res => {
							let infoType = '';
							if(res.code == 1){
								infoType = 'success';
								this.form = {};
								this.addDialogForm = false;
							}else{
								infoType = 'error'
							}
							this.$message({
								showClose: true,
								message: res.msg,
								type: infoType
							});
							this.getStaff(this.allPages.nowPage);
						}).catch(err => {
							console.log(err)
						})
          } else {
            return false;
          }
        });
			},
			/**
			 * [enterEdit 修改员工信息]
			 */
			enterEdit(form){
				this.$refs.MyForm.validate((valid) => {
          if (valid) {
						this.$post(this.url+'editStaff',{
							data: form
						}).then(res => {
							let infoType = '';
							if(res.code == 1){
								infoType = 'success';
								this.editDialogForm = false;
								this.form = {};
							}else{
								infoType = 'error'
							}
							this.$message({
								showClose: true,
								message: res.msg,
								type: infoType
							});
							this.getStaff(this.allPages.nowPage);
						}).catch(err => {
							console.log(err)
						})
          } else {
            return false;
          }
        });
			},
			/**
			 * [getRoleArr 获取角色列表]
			 */
      getRoleArr() {
				let that = this;
				that.$post(that.url+'getRoleArr',{
				}).then(res => {
					that.roleArr = res.data
				}).catch(err => {
					console.log(err)
				})
			},
			/**
			 * [editState 修改状态]
			 */
			editState(id,row) {
				if(row.state == 1){
					row.state = 0;
				}else{
					row.state = 1
				}
				let that = this;
				that.$post(that.url+'editState',{
					'id': row.id,
					'state': row.state
				}).then(res => {
					let msgType = '';
					if(res.code == 1){
						msgType = res.data == 1 ? 'success' : 'error';
					}else{
						msgType = 'error';
					}
					that.$message({
						showClose: true,
						message: res.msg,
						type: msgType
					});
					that.getStaff(this.allPages.nowPage);
				}).catch(err => {
					console.log(err)
				})
			},
			/**
			 * [editStaff 修改员工信息弹出框]
			 */
			editStaff(row){
				let that = this;
				that.form = row;
				that.dialogTitle = '修改员工信息';
				that.editDialogForm = true;
			},
			/**
			 * [delStaff 删除员工信息]
			 */ 
			delStaff(id){
				this.$post(this.url+'delStaff',{
					data: id
				}).then(res => {
					let msgType = res.code == 1 ? 'success' : 'error';
					this.$message({
						showClose: true,
						message: res.msg,
						type: msgType
					});
					this.getStaff(1);
				}).catch(err => {
					console.log(err)
				})
			},
			/**
			 * [batchDeleteStaff 批量删除]
			 */
			batchDeleteStaff(){
				let delArr = JSON.stringify(this.selectRow);
				this.$fetch(this.url+'batchDeleteStaff',{delArr})
				.then(res => {
					let msgType = res.code == 1 ? 'success' : 'error';
					this.$message({
						showClose: true,
						message: res.msg,
						type: msgType
					});
					this.getStaff(1);
					console.log(res)
				}).catch(err => {
					console.log(err)
				})
			},
			/**
			 * [resetPsw 重置密码]
			 */
			resetPsw(id){
				this.$fetch(this.url+'resetPsw',{
					data: id
				}).then( res =>{
					let msgType = res.code == 1 ? 'success' : 'error';
					let msgInfo = '';
					if(res.data){
						msgInfo = res.msg + ',初始密码为：' + res.data
					}else{
						msgInfo = res.msg
					}
					this.$message({
						showClose: true,
						message: msgInfo,
						type: msgType
					});
				}).catch(err =>{
					console.log(err)
				})
			},
			/**
			 * [closeFun dialog关闭事件]
			 */
			closeFun(form){
				this.form = {};
				this.$refs.MyForm.resetFields();
			},
			/**
			 * [uploadSuccess 上传成功]
			 */
			uploadSuccess(res, file) {
				console.log(res)
				console.log(file)
        this.form.head_img = URL.createObjectURL(file.raw);
      },
			/**
			 * [beforeUploadImg 上传头像定义规则 ]
			 */
      beforeUploadImg(file) {
        const isJPG = file.type === 'image/jpeg';
        const isLt2M = file.size / 1024 / 1024 < 2;
        if (!isJPG) {
          this.$message.error('上传头像图片只能是 JPG 格式!');
        }
        if (!isLt2M) {
          this.$message.error('上传头像图片大小不能超过 2MB!');
        }
        return isJPG && isLt2M;
			}
    }
	} 
</script>
<style scoped>
	[v-cloak]{
		display: none;
	}
	.el-input {
		position: relative;
		font-size: 14px;
		display: inline-block;
		width: 50%;
	}
	.avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 80px;
    height: 80px;
    line-height: 80px;
    text-align: center;
  }
  .avatar {
    width: 80px;
    height: 80px;
    display: block;
  }
</style>