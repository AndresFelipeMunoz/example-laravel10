<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel 10 Ajax DataTables CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Laravel 10 Ajax DataTables CRUD</h2>
                </div>
               <div class="mb-2">
                    <a type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Create Employee
                    </a>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{$message}}</p>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-bordered" id="ajax-crud-datatable">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form  id="employeeForm" name="employeeForm" class="form-horizontal">
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="name" class="col-sm-12 control-label">Name</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" maxlength="50" required>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-12 control-label">Email</label>
                <div class="col-sm-12">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" maxlength="50" required>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-12 control-label">Address</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" maxlength="50" required>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10 mt-4">
                <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>
  <!-- jQuery (debe estar cargado antes que cualquier otro script) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <!-- Bootstrap JS (Opcional si usas Bootstrap) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables JS (cargar después de jQuery) -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <script type="text/javascript">
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#ajax-crud-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employees.index')}}",
            columns: [
                    {data: 'id', name:'id'}, 
                    {data: 'name', name: 'name'},
                    {data: 'email', name:'email'}, 
                    {data: 'address', name:'address'}, 
                    {data: 'created_at', name:'created_at'}, 
                    {data: 'action', name:'action', orderable: false}, 
            ],
            order:[[0,'asc']]
        });
        
        window.editFunc = function(id){
            $.ajax({
                type:"POST",
                url: "{{ route('edit') }}",
                data:{ id: id },
                dataType: 'json',
                success: function(res){
                    console.log(res);
                    $('#exampleModalLabel').html("Edit Employee");
                    $('#exampleModal').modal('show');
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#address').val(res.address);
                    $('#email').val(res.email);
                }
            });
        }

         window.deleteFunc = function(id){
            if(confirm("Delete Record?") == true){
                var id = id;

                $.ajax({
                    type:"POST",
                    url: "{{route('delete') }}",
                    data: { id:id },
                    dataType:'json',
                    success: function(res){
                        var oTable = $('#ajax-crud-datatable').dataTable();
                        oTable.fnDraw(false);
                    }
                });
            }
        }
    $('#employeeForm').on('submit', function (e) {
        e.preventDefault();  // Evita que el formulario se envíe de forma tradicional
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "{{ route('store') }}",  // Especifica la URL del controlador
            data: formData,
            processData: false,  // Evitar que jQuery procese los datos
            contentType: false,  // Configurar el tipo de contenido en false
            success: (data) =>{
                console.log(data);
                $('#exampleModal').modal('hide');
                $("btn-save").html('Submit');
                $("btn-save").attr("disabled",false);
                $('#ajax-crud-datatable').DataTable().ajax.reload();
            },
            error: function (error) {
                alert('Error al guardar los datos.');
            }
        });
    });
 }); 
</script>
</body>
</html>