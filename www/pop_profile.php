<div id="pop_prof_phone" class="popover_html">Your Contact Number</div>

<div id="pop_prof_mobile" class="popover_html">Your Mobile Number</div>

<div id="pop_prof_email" class="popover_html">Your Email Address</div>

<div class="modal fade" id="prof_edu_add" tabindex="-1" role="dialog" aria-labelledby="prof_edu_add_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="prof_edu_add_label">Add a New Educational Qualification</h4>
      </div>
      <div class="modal-body">
      	<form>
	        <b>Degree/Qualification:</b> <input type="text" class="form-control" name="degree">
	        <b>Institution:</b> <input type="text" class="form-control" name="institution">
	        <b>Category:</b> 
	        <select name="edutype" class="form-control">
	        	<option value="1">Engineering</option>
	        	<option value="2">Arts & Architecture</option>
	        	<option value="3">Medicine</option>
	        	<option value="4">Research</option>
	        </select>
	        <b>Start Time:</b> <input type="date" class="form-control" name="stime">
	        <b>End Time:</b> <input type="date" class="form-control" name="etime">
	        <b>Notes:</b> <textarea class="form-control" rows="3" name="notes"></textarea>
	        <b>Website of the Institution:</b> <input type="url" class="form-control" name="insturl">
	        <b>Location of the Institution:</b> <input type="text" class="form-control" name="locid">
	        <!-- <b>Your Batchmates:</b> <input type="text" class="form-control" name="listid">-->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary profile_newdata" data-elemtype="prof_edu">Save changes</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="prof_work_add" tabindex="-1" role="dialog" aria-labelledby="prof_work_add_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="prof_work_add_label">Add a New Work</h4>
      </div>
      <div class="modal-body">
      	<form>
	        <b>Role/Position:</b> <input type="text" class="form-control" name="role">
	        <b>Company/Organization:</b> <input type="text" class="form-control" name="institution">
	        <b>Category:</b> 
	        <select name="worktype" class="form-control">
	        	<option value="1">Computers & Internet</option>
	        	<option value="2">Electronics & Electrical</option>
	        	<option value="3">Printing & Media</option>
	        	<option value="4">Banking</option>
	        </select>
	        <b>Start Time:</b> <input type="date" class="form-control" name="stime">
	        <b>End Time:</b> <input type="date" class="form-control" name="etime">
	        <b>Notes:</b> <textarea class="form-control" rows="3" name="notes"></textarea>
	        <b>Website of the Organization:</b> <input type="url" class="form-control" name="insturl">
	        <b>Location of the Organization:</b> <input type="text" class="form-control" name="locid">
	        <!-- <b>Your Colleagues:</b> <input type="text" class="form-control" name="listid">-->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary profile_newdata" data-elemtype="prof_work">Save changes</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="prof_skill_add" tabindex="-1" role="dialog" aria-labelledby="prof_skill_add_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="prof_skill_add_label">Add a New Skill</h4>
      </div>
      <div class="modal-body">
      	<form>
	        <b>Your Skill:</b> <input type="text" id="prof_skill_addip" class="form-control" name="skill">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary profile_newdata" data-elemtype="prof_skill">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="prof_achievement_add" tabindex="-1" role="dialog" aria-labelledby="prof_achievement_add_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="prof_achievement_add_label">Add a New Achievement</h4>
      </div>
      <div class="modal-body">
      	<form>
	        <b>Your Achievement:</b> <input type="text" class="form-control" name="achievement">
	        <b>Description regarding your Achievement:</b> <textarea class="form-control" rows="3" name="desc"></textarea>
	        <b>Time of Achievement:</b> <input type="text" class="form-control" name="achievetime">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary profile_newdata" data-elemtype="prof_achievement">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="prof_social_add" tabindex="-1" role="dialog" aria-labelledby="prof_social_add_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="prof_social_add_label">Add a New Profile/Website</h4>
      </div>
      <div class="modal-body">
      	<form>
	        <b>URL of the Profile/Website:</b> <input type="url" class="form-control" name="weburl">
	        <b>Label for the Profile/Website:</b> <input type="url" class="form-control" name="label">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary profile_newdata" data-elemtype="prof_socialadd">Save changes</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="prof_profpicupload" tabindex="-1" role="dialog" aria-labelledby="prof_profpicupload_label">
  <div class="modal-dialog" role="document">
  <form action="profile.php" method="post" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="prof_profpicupload_label">Upload Your Profile Picture</h4>
      </div>
      <div class="modal-body">
	        <b>Select your Picture:</b> <input type="file" class="form-control" name="profpic">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="profpic_submit" data-elemtype="prof_profpic">Upload</button>
      </div>
    </div>
    </form>
  </div>
</div>