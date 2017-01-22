<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$galobj=new ta_galleryoperations();
$socialobj=new ta_socialoperations();
$msgobj=new ta_messageoperations();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/invest.php",0,'/');
}
else
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$userobj->userinit();
	$uid=$userobj->uid;
}

$assetobj=new ta_assetloader();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();
?>

<div id="template_content_body">
	<div class="row">
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Cookie Policy 
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
<pre style="white-space: pre-wrap;word-break: normal;">

Like most websites, this one uses cookies.

Cookies are small text files stored on your computer by your browser. They're used for many things, such as remembering whether you've visited the site before, so that you remain logged in - or to help us work out how many new website visitors we get each month. They contain information about the use of your computer but don't include personal information about you (they don't store your name, for instance).

This policy explains how cookies are used on Tech Ahoy websites in general - and, below, how you can control the cookies that may be used on this site (not all of them are used on every site).

About this Cookie policy

This Cookie Policy applies to all of our websites and our mobile applications ("the Website").

In this Cookie Policy, when we refer to any of our Websites, we mean any website or mobile application operated by or on behalf of Tech Ahoy or its subsidiaries and affiliates (collectively "Tech Ahoy"), regardless of how you access the network. This Cookie Policy forms part of and is incorporated into our Website Terms and Conditions

By accessing the Website, you agree that this Cookie Policy will apply whenever you access the Website on any device.

Any changes to this policy will be posted here. We reserve the right to vary this Cookie Policy from time to time and such changes shall become effective as soon as they are posted. Your continued use of the Website constitutes your agreement to all such changes.

Our use of cookies

We may collect information automatically when you visit the Website, using cookies.

The cookies allow us to identify your computer and find out details about your last visit.

You can choose, below, not to allow cookies. If you do, we can't guarantee that your experience with the Website will be as good as if you do allow cookies.

The information collected by cookies does not personally identify you; it includes general information about your computer settings, your connection to the Internet e.g. operating system and platform, IP address, your browsing patterns and timings of browsing on the Website and your location.

Most internet browsers accept cookies automatically, but you can change the settings of your browser to erase cookies or prevent automatic acceptance if you prefer.

These links explain how you can control cookies via your browser - remember that if you turn off cookies in your browser then these settings apply to all websites not just this one:

Internet Explorer http://support.microsoft.com/kb/278835 (this page links to further information for different versions of IE - the mobile version is at http:/ /www.microsoft.com/windowsphone/en-us/howto/wp7/web/changing-privacy-and-other-browser-settings.aspx).

Chrome: http://support.google.com/chrome/bin/answer.py?hl=en-GB&answer=95647

Safari: http://docs.info.apple.com/article.html?path=Safari/5.0/en/9277.html (or http://support.apple.com/kb/HT1677for mobile versions)

Firefox: http://support.mozilla.org/en-US/kb/ Enabling%20and%20disabling%20cookies

Blackberries: http://docs.blackberry.com/en/smartphone_users/deliverables/ 32004/Turn_off_cookies_in_the_browser_60_1072866_11.jsp

Android: http://support.google.com/mobile/bin/answer.py?hl=en&answer=169022

Opera: http://www.opera.com/browser/tutorials/security/privacy/

Types of cookie that may be used during your visit to the Website:

The following types of cookie are used on this site. We don't list every single cookie used by name - but for each type of cookie we tell you how you can control its use.

1. Personalisation cookies


These cookies are used to recognise repeat visitors to the Website and in conjunction with other information we hold to attempt to record specific browsing information (that is, about the way you arrive at the Website, pages you view, options you select, information you enter and the path you take through the Website). These are used to recommend content we think you'll be interested in based on what you're looked at before.

You can disable our personalisation cookie when you are logged out of Facebook. To disable it permanently, also remove the Mirror app from the settings page of your Facebook account.

Click here to disable the personalisation cookie.

 

2. Analytics cookies


These monitor how visitors move around the Website and how they reached it. This is used so that we can see total (not individual) figures on which types of content users enjoy most, for instance.

You can opt out of these if you want:

Google: https://tools.google.com/dlpage/gaoptout

 

3. Third-party service cookies


Social sharing, video and other services we offer are run by other companies. These companies may drop cookies on your computer when you use them on our site or if you are already logged in to them.

Here is a list of places where you can find out more about specific services that we may use and their use of cookies:

Facebook's data use policy: http://www.facebook.com/about/privacy/your-info-on-other 

4. Site management cookies


These are used to maintain your identity or session on the Website. For instance, where our websites run on more than one server, we use a cookie to ensure that you are sent information by one specific server (otherwise you may log in or out unexpectedly). We may use similar cookies when you vote in opinion polls to ensure that you can only vote once, and to ensure that you can use our commenting functionality when not logged in (to ensure you don't see comments you've reported as abusive, for instance, or don't vote comments up/down more than once).

These cookies cannot be turned off individually but you could change your browser setting to refuse all cookies (see above) if you do not wish to accept them.


</pre>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>