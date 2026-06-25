<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm" method="POST">
                @csrf
                <input type="hidden" name="id" id="expenseId">
                <input type="hidden" name="status" id="statusInput">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                    <div class="mb-3">
                        <label class="form-label">
                            Description (Optional)
                        </label>
                        <textarea class="form-control" name="description" id="modalDescription" rows="4" placeholder="Enter description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="alertContainer"></div>
