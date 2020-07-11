import axios from 'axios'

export function request(config){
  // 1.创建实例
  const instance = axios.create({
    baseURL: 'http://127.0.0.1/th5/public',
    timeout: 5000 
  })
  // 设置post请求头
  instance.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
  // 3.发送网络请求
  return instance(config);
}

/**
 * 封装get方法
 * @param url
 * @param data
 * @returns {Promise}
 */

export function fetch(url,params={}){
  return new Promise((resolve,reject) => {
    axios.get(url,{
      params:params
    })
    .then(response => {
      resolve(response.data);
    })
    .catch(err => {
      reject(err)
    })
  })
}


/**
 * 封装post请求
 * @param url
 * @param data
 * @returns {Promise}
 */

export function post(url,data = {}){
  return new Promise((resolve,reject) => {
    axios.post(url,data)
    .then(response => {
      resolve(response.data);
    },err => {
      reject(err)
    })
  })
}
/**
 * 封装patch请求
 * @param url
 * @param data
 * @returns {Promise}
 */

export function patch(url,data = {}){
  return new Promise((resolve,reject) => {
    axios.patch(url,data)
    .then(response => {
      resolve(response.data);
    },err => {
      reject(err)
    })
  })
}

 /**
 * 封装put请求
 * @param url
 * @param data
 * @returns {Promise}
 */

export function put(url,data = {}){
  return new Promise((resolve,reject) => {
    axios.put(url,data)
    .then(response => {
      resolve(response.data);
    },err => {
      reject(err)
    })
  })

}
