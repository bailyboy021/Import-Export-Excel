<!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel Import/Export Excel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.dataTables.js"></script>
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
        
        <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.dataTables.css" rel="stylesheet">
        
        <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
        <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
        <style>
            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
                right: 30px !important; /* Adjust this value as needed */
            }

            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
                padding-right: 35px !important; /* Adjust this value to accommodate the clear button */
            }
        </style>
    </head>
    <body>
        <div class="container relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
            <main class="mt-6">
                <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                    <div class="row">
                        <div class="col-md-12 text-right">					
                            
                        </div>                        
                        <div class="col-md-12 mt-2">
                            <div class="card">                
                                <div class="card-header text-white bg-primary">
                                    <div class="row">	
                                        <div class="col-7 d-flex align-items-center">					
                                            Customer List
                                        </div>
                                        <div class="col-5 d-flex justify-content-end pr-0">	
                                            <button type="button" link="{{ route('addCustomers') }}" token="{{ csrf_token() }}" class="btn btn-sm btn-light me-1 add_activity" title="Create New Customer">Add</button>
                                            <button type="button" link="{{ route('importCustomers') }}" token="{{ csrf_token() }}" class="btn btn-sm btn-light me-1 import_customer" title="Import Customers">Import</button>
                                            <a href="#" id="button-export" class="btn btn-sm btn-light">Export</a>
                                        </div>
                                    </div>  
                                </div>                               
                                <div class="card-body" id="list_input">
                                    <div class="row g-3 align-items-center text-xs">                                         
                                        <div class="col-auto d-flex align-items-center ms-2">
                                            <label for="jenisKelamin" class="col-form-label me-2">Gender</label>
                                            <select class="form-control select2" name="jenisKelamin" id="jenisKelamin" style="width:100%">
                                                <option value=""></option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-auto d-flex align-items-center ms-2">
                                            <label for="customerCountry" class="col-form-label me-2">Country</label>
                                            <select class="form-control select2" name="customerCountry" id="customerCountry" style="width:100%">
                                                <option value=""></option>
                                                <option value="Indonesia">Indonesia</option>
                                                <option value="Palestine">Palestine</option>
                                                <option value="Turkey">Turkey</option>
                                                <option value="Italy">Italy</option>
                                                <option value="Japan">Japan</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" id="btn-filter-status" class="btn btn-primary btn-sm">Apply</button>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="mt-4 table-responsive">
                                            <table class="table table-bordered table-sm table-striped table_row table-hover display responsive nowrap" id="data-customers" style="cursor:pointer" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="all text-center">No.</th>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Phone Number</th>
                                                        <th class="text-center">POB</th>
                                                        <th class="text-center">DOB</th>
                                                        <th class="text-center">Gender</th>
                                                        <th class="text-center">Country</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('_modal')
            </main>

            <footer class="py-16 text-center text-sm text-black dark:text-white/70 my-3">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
        <script>
            $( '.select2' ).select2( {
                theme: 'bootstrap-5',
                allowClear: true,
                dropdownAutoWidth : true,
                placeholder: 'Select...',
            });

            $('body').on('click', '.add_activity', function(e)
            {
                let me= $(this),
                    url = me.attr('link'),
                    title = me.attr('title'),
                    token = me.attr('token');
                
                
                $('#content_title').text(title);
                $('#content_save').text(me.hasClass('edit') ? 'Update' : 'Submit');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: { '_token' :  token },
                    // dataType: 'html',
                    success: function(response)
                    {
                        $('#content_body').html(response);
                    },
                    beforeSend: function(msg)
                    {
                        $('#content_body').html("<div class='d-flex justify-content-center'><div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div></div>");
                    },
                    error: function (xhr, error, thrown)
                    {
                        console.log('gagal load modal untuk add customer : ',xhr)
                    }
                });

                var myModal = new bootstrap.Modal(document.getElementById('modal_add'), {
                    // backdrop: 'static',
                    keyboard: false
                });
                myModal.show();

            });

            $('body').on('click', '.import_customer', function(e)
            {
                let me= $(this),
                    url = me.attr('link'),
                    title = me.attr('title'),
                    token = me.attr('token');
                
                
                $('#content_title').text(title);
                $('#content_save').text(me.hasClass('edit') ? 'Update' : 'Submit');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: { '_token' :  token },
                    // dataType: 'html',
                    success: function(response)
                    {
                        $('#content_body').html(response);
                    },
                    beforeSend: function(msg)
                    {
                        $('#content_body').html("<div class='d-flex justify-content-center'><div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div></div>");
                    },
                    error: function (xhr, error, thrown)
                    {
                        console.log('gagal load modal untuk import customer : ',xhr)
                    }
                });

                var myModal = new bootstrap.Modal(document.getElementById('modal_add'), {
                    // backdrop: 'static',
                    keyboard: false
                });
                myModal.show();

            });

            $(function() 
            {
                let csrf_token = '{{ csrf_token() }}';
                let customers = $('#data-customers').DataTable({                    
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: 
                    {
                        url: '{{ route('getCustomers') }}',
                        data: function (d) {
                            d.gender = $('#jenisKelamin').val();
                            d.customerCountry = $('#customerCountry').val();
                            d._token = csrf_token;
                        },
                        method: 'post',
                        error: function (xhr, error, thrown) {
                            console.log('gagal load data customer:', xhr)
                        }		
                    },
                    columns: [
                        {   data: "id",
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            } 
                        },
                        {   data: 'name', name: 'name',"orderable":false },
                        {   data: 'email', name: 'email',"orderable":false },
                        {   data: 'phone', name: 'phone',"orderable":false },
                        {   data: 'birth_place', name: 'birth_place',"orderable":false },
                        {   data: 'birth_date', name: 'birth_date',"orderable":false },
                        {   data: 'gender', name: 'gender',"orderable":false },
                        {   data: 'country', name: 'country',"orderable":false },
                    ],
                    columnDefs: [{ 
                            "targets": [ 0, 1 ],
                            "orderable": false, 
                        },
                    ],
                            
                    order: [],
                    
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    language: {
                        "search" : "Search : ",
                        "searchPlaceholder" : "Type to search"
                    }
                    
                });
                    
                $('#data-customers tbody').on('click', 'tr', function () {
                    var data = customers.row( this ).data();
                    viewCustomers(data.id);
                });
                
                $('#btn-filter-status').click(function(){
                    $('#data-customers').DataTable().ajax.reload();
                });

                $('#modal_add').on('hidden.bs.modal', function (e) {
                    let selectElements = $('.select2', this);
                    
                    selectElements.each(function() {
                        if ($(this).data('select2')) { 
                            $(this).select2('destroy');
                        }
                    });

                    $('#content_body').html('');
                    $('.select2').select2({
                        theme: 'bootstrap-5',
                        allowClear: true,
                        dropdownAutoWidth : true,
                        placeholder: 'Select...',
                    });
                });
            });

            function viewCustomers(idCustomer)
            {
                var csrf_token = '{{ csrf_token() }}';
                $.ajax({
                    url:"{{ route('viewCustomers') }}",
                    method : 'post',
                    dataType : 'json',
                    data: {
                        'idCustomer' : idCustomer,
                        '_token' : csrf_token            
                    },
                    success: function(msg){
                        $('#content_body').html(msg.body);
                        $('#content_title').text(msg.title);
                    },
                    beforeSend: function(msg)
                    {
                        $('#content_body').html("<div class='d-flex justify-content-center'><div class='spinner-border text-primary' role='status'><span class='sr-only'>Loading...</span></div></div>");
                    },
                    error: function (xhr, error, thrown)
                    {
                        console.log('gagal load modal untuk edit status : ',xhr)
                    }
                });

                var myModal = new bootstrap.Modal(document.getElementById('modal_add'), {
                    // backdrop: 'static',
                    keyboard: false
                });
                myModal.show();
                
            }

            $('#button-export').click(function(e) {
                e.preventDefault();

                let baseUrl = "{{ route('exportCustomers') }}";
                let params = {};
                let gender = $('#jenisKelamin').val();
                let customerCountry = $('#customerCountry').val();

                // Add filter values to the params object if they exist
                if (gender) params.gender = gender;
                if (customerCountry) params.customerCountry = customerCountry;

                // Build the query string
                let queryString = $.param(params);

                // Construct the final URL
                let exportUrl = baseUrl + (queryString ? '?' + queryString : '');

                // Redirect to the export URL
                window.location.href = exportUrl;
            });
        </script>
    </body>
</html>