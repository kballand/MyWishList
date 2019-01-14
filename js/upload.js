$(document).ready(function () {
    let uploader = $('.uploadField .imageUploader');
    uploader.change(function () {
        let $this = $(this);
        let uploadField = $this.closest('.uploadField');
        if ($this.prop('files') && $this.prop('files')[0]) {
            let file = $this.prop('files')[0];
            let regexp = new RegExp('^image/.*$');
            if (regexp.test(file.type)) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    uploadField.find('.imagePreview').attr('src', e.target.result).css('display', 'block');
                    uploadField.find('.previewDelete').css('display', 'inline-block');
                    uploadField.find('.previewChanger').text('Modifier l\'image');
                    $this.closest('.imageUploaderField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'none');
                };
                reader.onerror = function () {
                    $this.val('').change();
                };
                reader.readAsDataURL(file);
                return;
            }
        }
        $this.val('');
        uploadField.find('.imagePreview').attr('src', '').css('display', 'none');
        uploadField.find('.previewDelete').css('display', 'none');
        uploadField.find('.previewChanger').text('Ajouter une image');
        $this.closest('.imageUploaderField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'block');
    });
    let deleteButton = $('.previewDelete');
    deleteButton.click(function () {
        let uploadField = $(this).closest('.uploadField');
        uploadField.find('.imageHotlink, .imageUploader').val('').change().keyup();
        uploadField.find('.imagePreview').attr('src', '').css('display', 'none');
        uploadField.find('.previewChanger').text('Ajouter une image');
        $(this).css('display', 'none');
    });
    let hotlink = $('.uploadField .imageHotlink');
    hotlink.change(function () {
        if($(this).val() && $(this).val() !== "") {
            let url = $(this).val();
            let uploadField = $(this).closest('.uploadField');
            let $this = $(this);
            let img = new Image();
            img.onload = function () {
                uploadField.find('.imagePreview').attr('src', url).css('display', 'block');
                uploadField.find('.previewDelete').css('display', 'inline-block');
                uploadField.find('.previewChanger').text('Modifier l\'image');
            };
            img.onerror = function () {
                url = window.location.origin + '/img/' + url;
                img.onerror = function () {
                    $this.val('').keyup();
                };
                img.src = url;
            };
            img.src = url;
        }
    });
    hotlink.keyup(function () {
        if ($(this).val() && $(this).val() !== '') {
            $(this).closest('.imageHotlinkField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'none');
        } else {
            let uploadField = $(this).closest('.uploadField');
            $(this).closest('.imageHotlinkField').siblings('.imageHotlinkField, .imageUploaderField').css('display', 'block');
            uploadField.find('.imagePreview').attr('src', '').css('display', 'none');
            uploadField.find('.previewDelete').css('display', 'none');
            uploadField.find('.previewChanger').text('Ajouter une image');
        }
    });
});