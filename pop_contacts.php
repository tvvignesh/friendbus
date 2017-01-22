<div class="modal fade" id="contacts_newbook" tabindex="-1" role="dialog" aria-labelledby="contacts_newbook_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="contacts_newbook_label">Add a New Contactbook</h4>
      </div>
      <div class="modal-body">
      	<form>
	        <b>Name of the Contact Book:</b> <input type="text" class="form-control" name="label">
	        <b>Description:</b> <textarea rows="3" class="form-control" name="notes"></textarea>
	        <!-- <b>Thumb Image for this Contact Book:</b> 
	        <input type="file" class="form-control" name="cbk_img">-->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary ajax-btn" data-mkey="contacts_newbook" data-eltarget="-1" data-dtype="json" data-sform="#contacts_newbook form:first">Save changes</button>
        <span class="status_progress"></span>
      </div>
    </div>
  </div>
</div>