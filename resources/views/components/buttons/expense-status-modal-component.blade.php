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
                        <textarea class="form-control" name="description" rows="4" placeholder="Enter description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('statusForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        document.querySelectorAll('.statusBtn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const status = this.dataset.status;
                const url = this.dataset.url;
                document.getElementById('expenseId').value = id;
                document.getElementById('statusInput').value = status;
                form.action = url;
                if (status === 'approved') {
                    document.getElementById('modalTitle').innerText = 'Approve Expense';
                    document.getElementById('modalMessage').innerText =
                        'Are you sure you want to approve this expense?';
                } else {
                    document.getElementById('modalTitle').innerText = 'Reject Expense';
                    document.getElementById('modalMessage').innerText =
                        'Are you sure you want to reject this expense?';
                }
            });
        });
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: new FormData(this)
                });
                const data = await response.json();
                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit';
            }
        });

    });
</script>
