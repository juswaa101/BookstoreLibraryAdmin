<!-- Modal -->
<div class="modal fade" id="addBookModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addBookForm">
                <div class="modal-body">
                    <label for="book_name" class="form-label"></label>Book Name</label>
                    <input type="text" class="form-control" placeholder="Book Name" name="book_name" id="book_name">
                    <div id="book_name_error" class="invalid-feedback"></div>

                    <label for="author" class="form-label mt-4"></label>Author</label>
                    <input type="text" class="form-control" placeholder="Author" name="author" id="author">
                    <div id="author_error" class="invalid-feedback"></div>

                    <label for="book_cover" class="form-label mt-4"></label>Book Cover (Max 4MB)</label>
                    <input type="file" class="form-control" placeholder="Book Cover" name="book_cover"
                        id="book_cover" accept="image/*">
                    <div id="book_cover_error" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="saveBookBtn">Save Book</button>
                </div>
            </form>
        </div>
    </div>
</div>
