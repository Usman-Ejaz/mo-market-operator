<!-- New Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" id="deleteForm">
                {{ csrf_field() }}
                {{ method_field("DELETE") }}
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="heading">
                        Delete record?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="">
                        {{ __('messages.record_delete') }}
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" id="confirmDelete">Yes! delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
