<div id="modal-edit-feed" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit feed</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="editFeedUrl">Url</label>
                        <input type="text" class="form-control" id="editFeedUrl" name="url"/>
                        <div class="invalid-feedback"></div>
                        <input type="hidden" name="feed_id" value=""/>
                    </div>
                    <button type="button" class="btn btn-primary js-update-feed">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>