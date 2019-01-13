$(document).ready(function () {
    let imageValidate = $('.uploadField .imageValidate');
    imageValidate.click(function () {
        let uploadField = $(this).closest('.uploadField');
        let uploader = $(uploadField).find('.imageUploader');
        let hotlinking = $(uploadField).find('.imageHotlink');
        if (uploader.prop('files') && uploader.prop('files')[0]) {
            let file = uploader.prop('files')[0];
            let regexp = new RegExp('^image/.*$');
            if (regexp.test(file.type)) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    uploadField.find('.imagePreview').attr('src', e.target.result).css('display', 'block');
                    uploadField.find('.previewDelete').css('display', 'inline-block');
                    uploadField.find('.previewChanger').text('Modifier l\'image');
                };
                reader.readAsDataURL(file);
            } else {
                uploader.val('').change();
            }
        } else if(hotlinking.val() && hotlinking.val() !== '') {
            let url = hotlinking.val();
            let imagePreview = uploadField.find('.imagePreview');
            imagePreview.on('load', function () {
                $(this).css('display', 'block');
                uploadField.find('.previewDelete').css('display', 'inline-block');
                uploadField.find('.previewChanger').text('Modifier l\'image');
            }).on('error', function () {
                if(imagePreview.attr('src') !== '') {
                    hotlinking.val('').change();
                    imagePreview.attr('src', '');
                    $(this).css('display', 'none');
                    uploadField.find('.previewDelete').css('display', 'none');
                    uploadField.find('.previewChanger').text('Ajouter une image');
                }
            }).attr('src', url);
        }
    });
    let uploader = $('.uploadField .imageUploader');
    uploader.change(function () {
        if($(this).val() && $(this).val() !== '') {
            $(this).closest('.imageUploaderField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'none');
        } else {
            $(this).closest('.imageUploaderField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'block');
        }
    });
    let reset = $('.uploadField .imageReset');
    reset.click(function (e) {
        e.preventDefault();
        $(this).closest('.uploadField').find('.imageHotlink, .imageUploader').val('').change();
    });
    let deleteButton = $('.previewDelete');
    deleteButton.click(function () {
        let uploadField = $(this).closest('.uploadField');
        uploadField.find('.imageHotlink, .imageUploader').val('').change();
        uploadField.find('.imagePreview').attr('src', '').css('display', 'none');
        uploadField.find('.previewChanger').text('Ajouter une image');
        $(this).css('display', 'none');
    });
    let hotlinking = $('.uploadField .imageHotlink');
    hotlinking.change(function () {
        if($(this).val() && $(this).val() !== '') {
            $(this).closest('.imageHotlinkField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'none');
        } else {
            $(this).closest('.imageHotlinkField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'block');
        }
    });
    hotlinking.keyup(function () {
        if($(this).val() && $(this).val() !== '') {
            $(this).closest('.imageHotlinkField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'none');
        } else {
            $(this).closest('.imageHotlinkField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'block');
        }
    });
});