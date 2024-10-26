<!-- Modal -->
<div class="modal fade" id="showBookModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Book Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <img src="" id="show_book_cover" class="img-fluid" alt="Book Cover">
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <label for="show_book_name" class="form-label">Book Name</label>
                        <input type="text" class="form-control" placeholder="Book Name" name="book_name"
                            id="show_book_name" readonly>
                    </div>
                    <div class="col-md-12 mt-4">
                        <label for="show_author" class="form-label">Author</label>
                        <input type="text" class="form-control" placeholder="Author" name="author" id="show_author"
                            readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
