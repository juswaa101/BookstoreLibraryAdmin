<!-- Modal -->
<div class="modal fade" id="updateBookModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit Book</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateBookForm">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <input type="hidden" name="id" id="edit_book_id">
                    <label for="book_preview" class="form-label">Book Preview</label> <br/>
                    <img src="" id="edit_book_cover_preview" class="img-fluid" alt="Book Cover">
                    <br/>

                    <label for="book_name" class="form-label mt-3">Book Name</label>
                    <input type="text" class="form-control" placeholder="Book Name" name="book_name"
                        id="edit_book_name">
                    <div id="edit_book_name_error" class="invalid-feedback"></div>

                    <label for="author" class="form-label mt-4">Author</label>
                    <input type="text" class="form-control" placeholder="Author" name="author" id="edit_author">
                    <div id="edit_author_error" class="invalid-feedback"></div>

                    <label for="book_cover" class="form-label mt-4">Book Cover (Max 4MB)</label>
                    <input type="file" class="form-control" placeholder="Book Cover" name="book_cover"
                        id="edit_book_cover" accept="image/*">
                    <div id="edit_book_cover_error" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="updateBookBtn">Update Book</button>
                </div>
            </form>
        </div>
    </div>
</div>
