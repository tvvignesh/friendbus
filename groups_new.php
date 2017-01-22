<form id="gp-newform">
<b>Note:</b> You may modify any of this setting anytime in the future
<br><br>
	Name of the Group: <input type="text" class="form-control" name="gp-name">
	Group Description: <textarea class="form-control" name="gp-desc" rows="4"></textarea>
	Visibility: 
	<select class="form-control" name="gp-vis">
		<option value="1">Public</option>
		<option value="2">Secret</option>
		<option value="3">Closed</option>
	</select>
	<br>
	Approval Method: 
	<select class="form-control" name="gp-approval">
		<option value="1">Automatically approve all requests</option>
		<option value="2">Require Admin's approval</option>
		<option value="3">Require approval from any existing member</option>
		<option value="4">Disapprove all requests</option>
	</select>
	<br>
	<button class="btn btn-primary ajax-btn" data-mkey="gp_create" data-sform="#gp-newform" data-eltarget=".gpnew-status" data-dtype="json" data-ddemand="json">Create Group</button> (You can start adding members once the group is created)
</form>

<span class="gpnew-status"></span>