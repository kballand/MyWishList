$(document).ready(function () {
   let uploader = $('.uploadField .imageUploader');
   uploader.change(function () {
       if(this.files && this.files[0]) {
           let regexp = new RegExp('^image/.*$');
           if(regexp.test(this.files[0].type)) {
               let curr = this;
               let reader = new FileReader();
               reader.onload = function(e) {
                   $(curr).prevAll('.imagePreview').attr('src', e.target.result).css('display', 'block');
                   $(curr).prevAll('.previewDelete').css('display', 'inline-block');
                   $(curr).prevAll('.previewChanger').text('Modifier l\'image');
               };
               reader.readAsDataURL(this.files[0]);
           }
       }
   });
   let deleteButton = $('.previewDelete');
   deleteButton.click(function () {
       $(this).nextAll('.imageUploader').val('');
       $(this).prevAll('.imagePreview').attr('src', '').css('display', 'none');
       $(this).nextAll('.previewChanger').text('Ajouter une image');
       $(this).css('display', 'none');
   });
});