<div class="row">
	<div class="col-12">	
		<form action="{{ $model->exists ? route('updateCustomers', $model->id) : route('storeCustomers') }}" 
			method="POST" 
			enctype="multipart/form-data" 
			id="request_form">

			@csrf
			@if($model->exists)
				@method('PUT')
			@endif
			<input type="hidden" id="customerId" name="customerId" value="{{ $model->exists? $model->id : '0'}}">		
			<div class="form-group row mb-2">
				<div class="col-4">
					<label class="control-label">Full Name</label>
				</div>
				<div class="col-8">
					<input type="text" name="name" id="name" class="form-control" placeholder="Full Name" value="{{ $model->name }}">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-4">
					<label class="control-label">Email</label>
				</div>
				<div class="col-8">
					<input type="text" name="email" id="email" class="form-control" placeholder="email" value="{{ $model->email }}">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-4">
					<label class="control-label">Phone Number</label>
				</div>
				<div class="col-8">
					<input type="text" name="phone" id="phone" class="form-control" placeholder="code-number : example 62-8987654321" value="{{ $model->phone }}">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-4">
					<label class="control-label">POB</label>
				</div>
				<div class="col-8">
					<input type="text" name="pob" id="pob" class="form-control" placeholder="Place Of Birth" value="{{ $model->birth_place }}">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-4">
					<label class="control-label">DOB</label>
				</div>
				<div class="col-8">
				<input type="text" name="dob" id="dob" class="form-control datetimepicker-input" value="{{ $model->birth_date ? date('d-m-Y', strtotime($model->birth_date)) : '' }}" placeholder="dd-mm-yyyy" data-target="#tanggal"/>
				</div>
			</div>
			<div class="form-group row mb-2 mt-0">
				<div class="col-4">
					<label class="control-label">Gender</label>
				</div>
				<div class="col-8">
					<select class="form-control select2" name="gender" id="gender" style="width:100%">
						<option value=""></option>
						<option value="Male" @if($model->gender == 'Male') selected @endif>Male</option>
						<option value="Female" @if($model->gender == 'Female') selected @endif>Female</option>
					</select>
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-4">
					<label class="control-label">Country</label>
				</div>
				<div class="col-8">
					<select class="form-control select2" name="country" id="country" style="width:100%">
						<option value=""></option>
						<option value="Indonesia" @if($model->country == 'Indonesia') selected @endif>Indonesia</option>
						<option value="Palestine" @if($model->country == 'Palestine') selected @endif>Palestine</option>
						<option value="Turkey" @if($model->country == 'Turkey') selected @endif>Turkey</option>
						<option value="Italy" @if($model->country == 'Italy') selected @endif>Italy</option>
						<option value="Japan" @if($model->country == 'Japan') selected @endif>Japan</option>
					</select>
				</div>
			</div>
			<div class="text-right d-flex justify-content-end">
				@if($model->exists)
					<button type="submit" class="btn btn-primary btn-sm me-2" id="updated_btn">SAVE</button>
					<button type="button" class="btn btn-sm btn-danger" onclick="btn_delete({{ $model->id }});">DELETE</button>
				@else
					<button type="submit" class="btn btn-primary btn-sm" id="submit_btn">CREATE</button>
				@endif
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$( '.select2' ).select2( {
		theme: 'bootstrap-5',
		placeholder: 'Select...',
	});

    $('.datetimepicker-input').datepicker({
		uiLibrary: 'bootstrap5'
	});

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
                    $.each(res.error, function(key, value) {
                        $('#' + key).closest('.form-control').addClass('is-invalid');
                    });
                }
            }
        });
    });
});

function btn_delete(id)
{
	var csrf_token = '{{ csrf_token() }}';
	var modalAdd = bootstrap.Modal.getInstance(document.getElementById('modal_add'));
	Swal.fire({
		icon: 'warning',
		title: 'Are you sure ?',
		allowOutsideClick: false
	}).then((result) => {
		if (result.value)
		{
			$.ajax({
				url:"{{ route('deleteCustomers') }}",
				data: {id:id, '_token' : csrf_token},
				method : 'delete',
				success: function(msg){
					Swal.fire({
						icon : 'success',
						timer: 1500
					}).then(function() {
						modalAdd.hide();
						$('#data-customers').DataTable().ajax.reload();
					});
				}
			});
		}
	});
}
</script>
