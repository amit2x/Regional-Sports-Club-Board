<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="approveModalLabel">
                    <i class="bi bi-check-circle-fill me-2"></i>Approve Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approveForm">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-check-circle text-success" style="font-size: 40px;"></i>
                        </div>
                    </div>

                    <p class="text-center mb-3">Are you sure you want to approve this registration?</p>

                    <div class="mb-3">
                        <label for="approveComments" class="form-label">Comments (Optional)</label>
                        <textarea id="approveComments"
                                  name="comments"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Add any comments or notes about this approval..."></textarea>
                        <small class="text-muted">These comments will be recorded in the approval history.</small>
                    </div>

                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>
                            <small>Approving this registration will confirm the participant's enrollment in the event.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success" id="confirmApproveBtn">
                        <i class="bi bi-check-lg me-1"></i>Approve Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
