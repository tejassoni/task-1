// allows only numbers
function isNumber(event) {
    event = (event) ? event : window.event;
    var charCode = (event.which) ? event.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

// allows only string
function isString(event) {
    return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))
}


// File Image Preview Starts
// onchange image display type=file id=formFile
formFile.onchange = evt => {
    const [file] = formFile.files
    if (file) {
        // img tag id=formFile_src
        formFile_src.src = URL.createObjectURL(file)
        $('.img_close').show();
    }
}

// after image preview hide
$('.img_close').hide();
$(document).on('click', '.img_close', function () {
    $("#formFile").val('');
    $("#formFile_src").attr('src', '#');
    $(this).hide();
});
// File Image Preview Ends
