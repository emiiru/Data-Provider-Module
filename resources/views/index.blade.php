<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#add-modal"><span
                class="fa fa-plus"></span> Add Provider</button>
        <table class="table table-bordered" id="data-provider-datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    @include('modal.add')
    @include('modal.edit')
    @include('modal.view')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/fe810b359e.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(function() {
            var table = $('#data-provider-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('data-provider.index') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('body').on('click', '.btn-view', async function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                try {
                    const data = await $.ajax({
                        type: 'GET',
                        url: '/' + id,
                        dataType: 'json',
                    });

                    const response = await $.ajax({
                        type: 'GET',
                        url: data.url,
                        dataType: 'json',
                    });

                    const image = response.message ? response.message : response[0].url;

                    if (image && image !== null && image !== 'undefined') {
                        $('#viewModalLabel').text(data.name);
                        $('#view-modal img').attr('src', image);
                        $('#view-modal').modal('show');
                        $('#refresh-btn').attr('data-id', id);
                    } else {
                        swal("Provider encountered an error while showing the image", {
                            icon: "error",
                        });
                    }
                } catch (error) {
                    swal("Provider encountered an error while showing data", {
                        icon: "error",
                    });
                }
            });

            $('body').on('click', '#refresh-btn', async function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                try {
                    const data = await $.ajax({
                        type: 'GET',
                        url: '/' + id,
                        dataType: 'json',
                    });
                    const response = await $.ajax({
                        type: 'GET',
                        url: data.url,
                        dataType: 'json',
                    });

                    const image = response.message ? response.message : response[0].url;
                    if (image && image !== null && image !== 'undefined') {
                        $('#view-modal img').attr('src', image);
                    } else {
                        swal("Provider encountered an error while showing the image", {
                            icon: "error",
                        });
                    }
                } catch (error) {
                    swal("Provider encountered an error while showing data", {
                        icon: "error",
                    });
                }
            });




            $('body').on('click', '.btn-edit', async function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                try {
                    const data = await $.ajax({
                        type: 'GET',
                        url: '/' + id,
                        dataType: 'json',
                    });

                    console.log(data);
                    $("#edit-form input[name=id]").val(data.id);
                    $("#edit-form input[name=name]").val(data.name);
                    $("#edit-form input[name=url]").val(data.url);
                    $('#edit-modal').modal('show');
                } catch (error) {
                    swal("Provider encountered an error while showing data", {
                        icon: "error",
                    });
                }
            });


            $('body').on('click', '.btn-delete', async function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                const willDelete = await swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Provider!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                });

                if (willDelete) {
                    try {
                        const data = await $.ajax({
                            type: 'GET',
                            url: '/delete/' + id,
                            dataType: 'json',
                        });

                        if (data.status === 'deleted') {
                            swal("Provider has been deleted!", {
                                icon: "success",
                            });
                            table.draw();
                        }
                    } catch (error) {
                        swal("Provider not deleted", {
                            icon: "error",
                        });
                    }
                }
            });

            $('body').on('submit', '#add-form', function(e) {
                e.preventDefault();
                submitForm(this, "Provider has been added!", '#add-modal',
                    "{{ route('data-provider.store') }}");
            });

            $('body').on('submit', '#edit-form', function(e) {
                e.preventDefault();
                submitForm(this, "Provider has been updated!", '#edit-modal',
                    "{{ route('data-provider.update') }}");
            });

            function submitForm(form, successMessage, modalId, routeURL) {
                $.ajax({
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: new FormData(form),
                    url: routeURL,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            swal(successMessage, {
                                icon: "success",
                            });
                            $(modalId).modal('hide');
                            table.draw();
                            form.reset(); // Reset the form
                        }
                        console.log(data);
                    },
                    error: function(response) {
                        console.log(response.responseJSON.errors);
                    },
                });
            }
        });
    </script>
</body>

</html>
