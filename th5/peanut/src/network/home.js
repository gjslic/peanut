import {request} from './request'

export function getData(url){

  return request({
    url: url
  })

}

export function sendParam(url ,data){
  return request({
    method:'post',
    url: url,
    data: data,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
		},

  })


}