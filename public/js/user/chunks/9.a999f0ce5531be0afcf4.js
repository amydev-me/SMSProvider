webpackJsonp([9],{188:function(t,o,e){t.exports={components:{DeleteModal:function(t){return e.e(23).then(function(){var o=[e(202)];t.apply(null,o)}.bind(this)).catch(e.oe)}},data:function(){return{removeUrl:"/group/delete/",contacts:[],group:{groupName:null,description:null,id:null},edit_group:{groupName:null,description:null,id:null},group_id:null,selected_group:{},toggle_check:null,selected_contacts:[]}},methods:{showContactModal:function(){$("#contact_modal").modal("show")},editModal:function(){this.edit_group=Object.assign({},this.group),$("#goupmodal").modal("show")},getUrlParam:function(){$group_id=Helper.getUrlParameter("group_id"),$group_id&&(this.group_id=$group_id,this.getGroupAndContacts())},getGroupAndContacts:function(){var t=this;axios.get("/list/contacts/"+this.group_id).then(function(o){var e=o.data;t.group.groupName=e.groupName,t.group.description=e.description,t.group.id=e.id,t.contacts=e.contacts,t.selected_group={id:t.group.id,groupName:t.group.groupName}})},validateGroup:function(t){var o=this;this.$validator.validateAll(t).then(function(t){t&&o.submit()}).catch(function(t){Notification.warning("Opps!Something went wrong.")})},submit:function(){var t=this;axios.post("/group/update",this.edit_group).then(function(o){1==o.data.status?($("#goupmodal").modal("hide"),Notification.success("Success"),t.group=t.edit_group):Notification.error("Error occurs while creating data.")}).catch(function(t){401==t.response.status||419==t.response.status?window.location.href="/login":Notification.error("Error occured while deleting data.")})},showDeleteModal:function(){$("#deleteModal").modal("show")},successdelete:function(){window.location.href="/address-book"},showDeleteMultiContactModal:function(){$("#contact_multi_modal").modal("show")},checkContacts:function(t){if(t.target.checked){var o=[];this.contacts.forEach(function(t){o.push(t.id)}),this.selected_contacts=o}else this.selected_contacts=[]},promptDelete:function(){$("#delete_background").show(),$("#contact_delete_modal").modal("show")},confirmDelete:function(){var t=this;this.selected_contacts.length>0?axios.post("/group/delete-contacts",{contacts:this.selected_contacts}).then(function(o){1==o.data.status?(t.getGroupAndContacts(),Notification.success("Success"),$("#contact_delete_modal").modal("hide")):Notification.error("Error occurs while creating data.")}).catch(function(t){401==t.response.status||419==t.response.status?window.location.href="/login":Notification.error("Error occured while deleting data.")}):Notification.error("Select at least one contact.")}},mounted:function(){this.getUrlParam(),$("#contact_delete_modal").on("hidden.bs.modal",function(t){$("#delete_background").hide()})}}}});