<div class="row">
	<div class="col-md-12">	
		<form action="{{ route('import') }}" 
			method="POST" 
			enctype="multipart/form-data" 
			id="request_form">

			@csrf
			<div class="form-group row mb-3">
                <div class="col-12">
                    You can download the template
                    <a href='{{ asset("import/format_import_customer.xlsx") }}' target="_blank">
                    here
                    </a>
			    </div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-4">
					<label class="control-label">File</label>
				</div>
				<div class="col-8">
					<input type="file" name="file" id="file" class="form-control" placeholder="Select File" value="">
				</div>
			</div>
			<div class="text-right d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-sm" id="submit_btn">SUBMIT</button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#request_form').on('submit', function(e) {
        e.preventDefault();

        var form = $('#request_form'),
            url = form.attr('action'),
            modalAdd = bootstrap.Modal.getInstance(document.getElementById('modal_add'));

        form.find('.invalid-feedback').remove();
        form.find('.form-control').removeClass('is-invalid');

        $.ajax({
            url: url,
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(returnData) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    allowOutsideClick: false,
					timer: 1500
                }).then(function() {
                    modalAdd.hide();
                    $('#data-customers').DataTable().ajax.reload(); // Reload DataTable
                });
            },
            error: function(xhr) {
                let res = xhr.responseJSON;				
                if ($.isEmptyObject(res) == false) {
                    $.each(res.errors, function(key, value) {
                        $('#' + key).closest('.form-control').addClass('is-invalid');
                    });
                }
            }
        });
    });
});
</script>
