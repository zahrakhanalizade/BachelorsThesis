var uploadFileIntoGroupFormComponent;

function showUploadFileIntoGroupForm($groupId){
    uploadFileIntoGroupFormComponent = OW.ajaxFloatBox('IISGROUPSPLUS_CMP_FileUploadFloatBox', {iconClass: 'ow_ic_add',groupId: $groupId})
}

function closeUploadFileIntoGroupForm(){
    if(uploadFileIntoGroupFormComponent){
        uploadFileIntoGroupFormComponent.close();
    }
}