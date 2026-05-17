<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="bi bi-x-circle-fill me-2"></i>Reject Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-x-circle text-danger" style="font-size: 40px;"></i>
                        </div>
                    </div>

                    <p class="text-center mb-3">Please provide a reason for rejecting this registration.</p>

                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">
                            Rejection Reason <span class="text-danger">*</span>
                        </label>
                        <textarea id="rejectionReason"
                                  name="rejection_reason"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Provide a detailed reason for rejection (minimum 10 characters)..."
                                  required></textarea>
                        <div class="invalid-feedback" id="rejectionError">
                            Please provide at least 10 characters.
                        </div>
                        <small class="text-muted">This reason will be visible to the employee. Please be clear and constructive.</small>
                    </div>

                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <small>This action cannot be undone. The employee will be notified of the rejection.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger" id="confirmRejectBtn">
                        <i class="bi bi-x-circle me-1"></i>Reject Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
