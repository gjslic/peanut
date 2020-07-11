import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
	
	state:{
		UserToken:'',
		brandNum:0,
		priceNum:0,
	},
	mutations:{
		setUserToken(state,getUserToken){
			state.UserToken = getUserToken
		}
	},
	
})