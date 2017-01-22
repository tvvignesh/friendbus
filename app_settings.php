<ul class="list-group appbx_settings_lgp">

<li class="list-group-item">
Notifications
<span class="pull-right">
<input type="checkbox" checked data-toggle="toggle" data-on="<i class='fa fa-check appbx_togglebx'></i> ON" data-off="<i class='fa fa-times appbx_togglebx'></i> OFF" data-width="100" data-style="ios">
</span>
<div style="clear: both;"></div>
</li>

<li class="list-group-item">
Block Messages?
<span class="pull-right">
<input type="checkbox" data-toggle="toggle" data-on="<i class='fa fa-check appbx_togglebx'></i> Yes" data-off="<i class='fa fa-times appbx_togglebx'></i> No" data-width="100" data-style="ios">
</span>
<div style="clear: both;"></div>
</li>

<li class="list-group-item">
Share APP with Users
<span class="pull-right">
	<button class="btn btn-primary"><i class="fa fa-share"></i> Share</button>
</span>
<div style="clear: both;"></div>
</li>

<li class="list-group-item">
Labels
<span class="pull-right">
<select class="form-control appbx_settings_label" name="appbx_settings_label[]" multiple>
  	<option>&lt;span class="bg-danger appbx_settings_label"&gt;Work&lt;/span&gt;</option>
  	<option>&lt;span class="bg-primary appbx_settings_label"&gt;Team&lt;/span&gt;</option>
  	<option>&lt;span class="bg-success appbx_settings_label"&gt;My Label&lt;/span&gt;</option>
</select>
</span>
<div style="clear: both;"></div>
</li>
</ul>

<h4>Info this APP Uses</h4>
<ul class="list-group appbx_settings_lgp">
<li class="list-group-item">
Your Profile Picture <i class="fa fa-question-circle" title="Your picture"></i>
<span class="pull-right">
<input type="checkbox" data-toggle="toggle" data-on="<i class='fa fa-check appbx_togglebx'></i> Allow" data-off="<i class='fa fa-times appbx_togglebx'></i> Block" data-width="100" data-style="ios">
</span>
<div style="clear: both;"></div>
</li>

<li class="list-group-item">
Your Posts <i class="fa fa-question-circle" title="Your posts"></i>
<span class="pull-right">
<input type="checkbox" checked data-toggle="toggle" data-on="<i class='fa fa-check appbx_togglebx'></i> Allow" data-off="<i class='fa fa-times appbx_togglebx'></i> Block" data-width="100" data-style="ios">
</span>
<div style="clear: both;"></div>
</li>


<li class="list-group-item">
Your Contact List <i class="fa fa-question-circle" title="Your posts"></i>
<span class="pull-right">
<input type="checkbox" checked data-toggle="toggle" data-on="<i class='fa fa-check appbx_togglebx'></i> Allow" data-off="<i class='fa fa-times appbx_togglebx'></i> Block" data-width="100" data-style="ios">
</span>
<div style="clear: both;"></div>
</li>


</ul>

<script type="text/javascript">
var utilityobj=new JS_UTILITY();
utilityobj.multiselect($('.appbx_settings_label'),{
	buttonWidth: '150px',
	enableCaseInsensitiveFiltering: true,
	filterPlaceholder: 'Search',
	enableHTML:true
	});

utilityobj.toggle($('[data-toggle=toggle]'),{});

function applycbox()
{
	utilityobj.checkbox($('.multiselect-container input[type="checkbox"]'), {
		checkedClass: 'glyphicon glyphicon-ok'
	});
}

setTimeout(applycbox,100);
</script>