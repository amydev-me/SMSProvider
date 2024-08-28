'use strict';
module.exports= class ServerErrors{

  constructor(){
    this.svr_errors ={};
  }

  get(field){
    if(this.svr_errors[field]){
      return this.svr_errors[field][0];
    }
  }

  has(field){
    return this.svr_errors.hasOwnProperty(field);
  }

  record(errors){
    this.svr_errors = errors;
  }

  any(){
    return Object.keys(this.svr_errors).length > 0;
  }
}