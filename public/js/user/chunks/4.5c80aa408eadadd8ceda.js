webpackJsonp([4],{196:function(t,e){t.exports={data:function(){return{setting:{newsletter_alert:null,credit_email_alert:null,credit_sms_alert:null,minimum_credit:null},newsletter_alert:null,credit_email_alert:null,credit_sms_alert:null}},methods:{getUserSetting:function(){var t=this;axios.get("/user/get-setting").then(function(e){var i=e.data;1==i.newsletter_alert?t.newsletter_alert=!0:t.newsletter_alert=!1,1==i.credit_email_alert?t.credit_email_alert=!0:t.credit_email_alert=!1,1==i.credit_sms_alert?t.credit_sms_alert=!0:t.credit_sms_alert=!1,t.setting.newsletter_alert=i.newsletter_alert,t.setting.credit_email_alert=i.credit_email_alert,t.setting.credit_sms_alert=i.credit_sms_alert,t.setting.minimum_credit=i.minimum_credit})},toggleNewsletter:function(t){this.newsletter_alert=t.value,this.newsletter_alert?this.setting.newsletter_alert=1:this.setting.newsletter_alert=0},toggleEmailAlert:function(t){this.credit_email_alert=t.value,this.credit_email_alert?this.setting.credit_email_alert=1:this.setting.credit_email_alert=0},toggleSmsAlert:function(t){this.credit_sms_alert=t.value,this.credit_sms_alert?this.setting.credit_sms_alert=1:this.setting.credit_sms_alert=0},saveSetting:function(){axios.post("/user/setting/change",this.setting).then(function(t){var e=t.data;if(1==e.status)Notification.success("Success");else{var i="";for(var r in e.message)e.message.hasOwnProperty(r)&&(i+=e.message[r]+"<br/>");Notification.error(i)}}).catch(function(t){401==t.response.status||419==t.response.status?window.location.href="/login":Notification.error("Error occured while creating data.")})}},mounted:function(){this.getUserSetting()}}}});