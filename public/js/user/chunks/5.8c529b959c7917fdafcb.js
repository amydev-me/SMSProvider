webpackJsonp([5],{195:function(s,r){s.exports={data:function(){return{isedit:!1,user:{email:null,mobile:null,fullName:null,company:null,address:null},oldPassword:null,newPassword:null,confirmPassword:null,email:null,loading:!1}},methods:{getUserProfile:function(){var s=this;axios.get("/user/get-profile").then(function(r){var o=r.data;s.user=o})},clickedit:function(){this.isedit=!0},validateData:function(){var s=this;this.$validator.validateAll().then(function(r){r&&s.$refs.form.submit()}).catch(function(s){Notification.warning("Opps!Something went wrong.")})},showPasswordModal:function(){this.cleanData(),$("#userPasswordModal").modal("show")},cleanData:function(){$("#old_password_error").val(""),$("#new_password_error").val(""),$("#confirm_password_error").val(""),this.oldPassword=null,this.newPassword=null,this.confirmPassword=null},changePassword:function(){this.validatePasswords()&&this.saveNewPassword()},validatePasswords:function(){var s=!1;return null==this.oldPassword||""==this.oldPassword?($("#old_password_error").text("Old Password is required"),$("#old_password_error").show(),s=!0):$("#old_password_error").hide(),null==this.newPassword||""==this.newPassword?($("#new_password_error").text("New Password is required"),$("#new_password_error").show(),s=!0):this.oldPassword==this.newPassword?($("#new_password_error").text("Old Password and New Password must not be same"),$("#new_password_error").show(),s=!0):$("#new_password_error").hide(),null==this.confirmPassword||""==this.confirmPassword?($("#confirm_password_error").text("Confirm Password is required"),$("#confirm_password_error").show(),s=!0):this.newPassword!=this.confirmPassword?($("#confirm_password_error").text("New Password and Confirm Password must be same"),$("#confirm_password_error").show(),s=!0):$("#confirm_password_error").hide(),!s},saveNewPassword:function(){var s=this,r={old_password:this.oldPassword,new_password:this.newPassword,confirm_password:this.confirmPassword};axios.post("/user/password/change",r).then(function(r){var o=r.data;1==o.status?($("#userPasswordModal").modal("hide"),Notification.success("Success")):s.showErrors(o.message)}).catch(function(s){401==s.response.status||419==s.response.status?window.location.href="/login":Notification.error("Error occured while creating data.")})},showEmailChange:function(){this.email=null,this.loading=!1,$("#userEmailModal").modal("show")},changeEmail:function(){var s=this;if(null==this.email||""==this.email)return $("#email_error").text("Email is required."),$("#email_error").show(),!1;this.loading=!0,axios.post("/user/email/change",{email:this.email}).then(function(r){var o=r.data;1==o.status?($("#userEmailModal").modal("hide"),Notification.success("Please click the verification link sent to your new email.")):(s.showErrors(o.message),s.loading=!1)}).catch(function(r){401==r.response.status||419==r.response.status?window.location.href="/login":Notification.error("Error occured while requesting data."),s.loading=!1})},showErrors:function(s){var r="";for(var o in s)s.hasOwnProperty(o)&&(r+=s[o]+"<br/>");Notification.error(r)}},mounted:function(){this.getUserProfile()}}}});