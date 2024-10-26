@extends('layouts.app')

@section('title', 'Bookstore Library Admin')

@section('css')
    {{-- Datatable Bootstrap 5 CSS --}}
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    {{-- Datatable Button CSS --}}
    <link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.min.css" rel="stylesheet">

    {{-- Datatable 3.0.3 Responsive CSS --}}
    <link href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container p-5">
        <div class="row mb-5">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <form id="csvFileForm">
                        <label for="csvFile" class="form-label">Import Books (CSV Only)</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv"><br />
                            <button type="button" class="btn btn-primary" id="importCsv">
                                <i class="fa fa-upload"></i> Import CSV
                            </button>
                        </div>
                        <span class="text-danger" id="csvFile_error"></span>
                    </form>
                </div>

                <div class="div">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBookModal">
                        <i class="fa fa-plus"></i> Add Book
                    </button>
                </div>
            </div>

            <div class="col-md-12 mt-5">
                <div class="table-responsive">
                    <table class="table table-bordered w-100" id="bookTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Book Name</th>
                                <th>Author</th>
                                <th>Book Cover</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('book.modal.add')
    @include('book.modal.edit')
    @include('book.modal.show')
@endsection

@section('js')
    {{-- Datatable 2.1.8 JS --}}
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

    {{-- Datatable Bootstrap 5 JS --}}
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>

    {{-- Datatable Button JS --}}
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>

    {{-- Datatable 3.0.3 Responsive JS --}}
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            // Setup CSRF Token for AJAX Request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Setup Book Datatable
            let bookTable = $('#bookTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('books.api') }}',
                    type: 'GET'
                },
                columns: [{
                        data: null,
                        render: function(data, type, full, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, {
                        data: 'book_name',
                        name: 'book_name'
                    },
                    {
                        data: 'author',
                        name: 'author'
                    },
                    {
                        data: 'book_cover',
                        name: 'book_cover',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                layout: {
                    topStart: {
                        pageLength: [
                            10, 25, 50, 100, -1,
                        ],
                        buttons: [
                            'copy', 'csv', // pdf, print
                        ]
                    },
                    topEnd: {
                        search: {
                            placeholder: 'Search'
                        }
                    }
                },
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
            });

            // Save Book
            $('#saveBookBtn').on('click', function() {
                let formData = new FormData($('#addBookForm')[0]);

                // Call AJAX Request to save book
                $.ajax({
                    url: '{{ route('books.store') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function() {
                        // Clear validation error messages and classes
                        $('#book_name_error').text('');
                        $('#author_error').text('');
                        $('#book_cover_error').text('');

                        $('#book_name').removeClass('is-invalid');
                        $('#author').removeClass('is-invalid');
                        $('#book_cover').removeClass('is-invalid');

                        // Update to loading spinner and disable button
                        $('#saveBookBtn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
                        ).attr('disabled', true);
                    },
                    complete: function() {
                        // Revert to original button text and enable button
                        $('#saveBookBtn').html('Save Book').attr('disabled', false);
                    },
                    success: function(response) {
                        // Display success message
                        $('#addBookModal').modal('hide');
                        $('#addBookForm')[0].reset();
                        bookTable.ajax.reload();
                    },
                    error: function(xhr) {
                        // Display validation error messages and classes
                        if (xhr.status == 422) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}_error`).text(value);
                            });
                        }

                        // Display error message
                        if (xhr.status == 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Book not saved! Please try again.'
                            });
                        }
                    }
                });
            });

            // Show Book
            $('#bookTable').on('click', '.view-book', function() {
                let bookId = $(this).data('id');
                let url = '{{ route('books.show', ':book') }}'
                    .replace(':book', bookId);

                // Call AJAX Request to show book
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        // Get book details
                        let book = response.data;

                        // Display book details
                        $('#show_book_name').val(book.book_name);
                        $('#show_author').val(book.author);

                        // Check /storage/ if blank inside then show placeholder, by default it has value of /storage/
                        if (book.book_cover == '/storage/' || !book.book_cover) {
                            $('#show_book_cover').attr('src',
                                'https://via.placeholder.com/200');
                        } else {
                            $('#show_book_cover').attr('src', book.book_cover);
                        }

                        $('#showBookModal').modal('show');
                    },
                    error: function(xhr) {
                        // Display error message
                        if (xhr.status == 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Book not found! Please try again.'
                            });
                        }
                    }
                });
            });

            // Edit Book
            $('#bookTable').on('click', '.edit-book', function() {
                let bookId = $(this).data('id');
                let url = '{{ route('books.show', ':book') }}'
                    .replace(':book', bookId);

                // Call AJAX Request to edit book
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        // Get book details
                        let book = response.data;

                        // Display book details
                        $('#edit_book_id').val(book.id);
                        $('#edit_book_name').val(book.book_name);
                        $('#edit_author').val(book.author);

                        // Check /storage/ if blank inside then show placeholder, by default it has value of /storage/
                        if (book.book_cover == '/storage/' || !book.book_cover) {
                            $('#edit_book_cover_preview').attr('src',
                                'https://via.placeholder.com/200');
                        } else {
                            $('#edit_book_cover_preview').attr('src', book.book_cover)
                        }

                        $('#updateBookModal').modal('show');
                    },
                    error: function(xhr) {
                        // Display error message
                        if (xhr.status == 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Book not found! Please try again.'
                            });
                        }
                    }
                });
            });

            // Update Book
            $('#updateBookBtn').on('click', function() {
                // Get form data
                let formData = new FormData($('#updateBookForm')[0]);

                // Get update book URL
                let url = '{{ route('books.update', ':book') }}'
                    .replace(':book', $('#edit_book_id').val());

                // Use method spoofing to update book
                formData.append('_method', 'PUT');

                // Call AJAX Request to update book
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function() {
                        // Clear validation error messages and classes
                        $('#edit_book_name_error').text('');
                        $('#edit_author_error').text('');
                        $('#edit_book_cover_error').text('');

                        $('#edit_book_name').removeClass('is-invalid');
                        $('#edit_author').removeClass('is-invalid');
                        $('#edit_book_cover').removeClass('is-invalid');

                        // Update to loading spinner and disable button
                        $('#updateBookBtn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
                        ).attr('disabled', true);
                    },
                    complete: function() {
                        // Update to update book and enable button
                        $('#updateBookBtn').html(
                            'Update Book'
                        ).attr('disabled', false);
                    },
                    success: function(response) {
                        // Display success message
                        $('#updateBookModal').modal('hide');
                        $('#updateBookForm')[0].reset();
                        bookTable.ajax.reload();
                    },
                    error: function(xhr) {
                        // Display validation error messages and classes
                        if (xhr.status == 422) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $(`#edit_${key}`).addClass('is-invalid');
                                $(`#edit_${key}_error`).text(value);
                            });
                        }

                        // Display error message
                        if (xhr.status == 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Book not updated! Please try again.'
                            });
                        }
                    }
                });
            });

            // Delete Book
            $('#bookTable').on('click', '.delete-book', function() {
                let bookId = $(this).data('id');
                let url = '{{ route('books.destroy', ':book') }}'
                    .replace(':book', bookId);

                // Call AJAX Request to delete book
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this book!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(response) {
                                // Display success message
                                bookTable.ajax.reload();
                            },
                            error: function(xhr) {
                                // Display error message
                                if (xhr.status == 500) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Book not deleted! Please try again.'
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // Change Book Cover Preview
            $('#edit_book_cover').on('change', function() {
                let reader = new FileReader();

                reader.onload = (e) => {
                    $('#edit_book_cover_preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            });

            // Import CSV
            $('#importCsv').on('click', function() {
                // Get CSV File
                let formData = new FormData($('#csvFileForm')[0]);

                // Call AJAX Request to import CSV
                $.ajax({
                    url: '{{ route('books.import') }}',
                    data: formData,
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        // Clear validation error messages and classes
                        $('#csvFile_error').text('');
                        $('#csvFile').removeClass('is-invalid');

                        // Update to loading spinner and disable button
                        $('#importCsv').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importing...'
                        ).attr('disabled', true);
                    },
                    complete: function() {
                        // Revert to original button text and enable button
                        $('#importCsv').html('Import CSV').attr('disabled', false);
                    },
                    success: function(response) {
                        // Display success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Books imported successfully!'
                        });

                        // Clear uploaded CSV file
                        $('#csvFile').val('');

                        bookTable.ajax.reload();
                    },
                    error: function(xhr) {
                        // Display validation error messages and classes
                        if (xhr.status == 422) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}_error`).text(value);
                            });
                        }

                        // Display error message
                        if (xhr.status == 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Books not imported! Please try again.'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
