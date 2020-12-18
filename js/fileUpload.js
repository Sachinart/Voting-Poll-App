
function uploadFile(containerId)
{
	
	container = document.getElementById(containerId);
	container.style.display = "none";
	var rtnMsg = '';
	var success = '0';

	if($("#file_to_upload").val() != "")
    {
        var file_data = $('#file_to_upload').prop('files')[0];
        var form_data = new FormData();

        form_data.append('file', file_data);

        $.ajax({
            url: 'includes/uploadFile.php', // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(out){
                // get server responce here
                //alert(out);
                // clear file field
				var arrResponse = JSON.parse(out);
				rtnMsg = arrResponse.rtnMsg;
				success = arrResponse.success;
				},
				error: function(jqXHR, exception) {
					if (jqXHR.status === 0) {
						rtnMsg = 'Not connect.\n Verify Network.';
					} else if (jqXHR.status == 404) {
						rtnMsg = 'Requested page not found. [404]';
					} else if (jqXHR.status == 500) {
						rtnMsg = 'Internal Server Error [500].';
					} else if (exception === 'parsererror') {
						rtnMsg = 'Requested JSON parse failed.';
					} else if (exception === 'timeout') {
						rtnMsg = 'Time out error.';
					} else if (exception === 'abort') {
						rtnMsg = 'Ajax request aborted.';
					} else {
						rtnMsg = 'Uncaught Error.\n' + jqXHR.responseText;
					}
					success = '0';
					showMsg();
				}
        }).done(function() {
				$("#file_to_upload").val("");
				showMsg();
		});
    }
    else
    {
		success = '0';
		rtnMsg = 'Please select file!';
        showMsg();
    }
	
	//Nested function. because in case of error, it is not going in done function.
	function showMsg(){

		if(success === '1'){
			container.innerHTML = '<strong>Success! </strong>' + rtnMsg;
			container.classList.remove("alert-danger");
			container.classList.add("alert-success");
		}
		else {
			container.innerHTML = '<strong>Error! </strong>' + rtnMsg;
			container.classList.remove("alert-success");
			container.classList.add("alert-danger");
		}
		container.style.display = "block";
	}
}