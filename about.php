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
	setcookie("returnpath",HOST_SERVER."/who.php",0,'/');
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
					Who we Are, What we do, Why we do it & How we do it
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					We are an organization named Tech Ahoy which works on using the power of Technology by all means to ensure that the world can be made a better place to live for each and every living thing.
					<br><br>
					This might looks like big words, but we really beleive in this. 
					<br><br>
					As a first step to it, we have launched Friendbus which is aimed at bringing the world closer, making the internet as open as possible by providing great source of information by connecting each and every individual to the right person and right information they seek anywhere in the world.
					<br><br>
					Looks like nothing new? You may say that well, there are already many organizations doing this. So what do you have that makes this special?
					<br>
					Well, you asked the right question. 
					<br><br>
					
					
<pre style="white-space: pre-wrap;word-break: normal;">
<b>We make sure you control your own privacy and not us</b>
1. We don't have any predefined privacy policy. Infact, you define your own by choosing what you want to share, what you want us to share and how you want yourself to be seen by others

<b>We make sure that you are always productive</b>
1. If you don’t want yourself to be interrupted, we won’t. We will store all notifications, messages or SMS you may receive and we will deliver it to you only when you are ready to see them. So, you can choose when you want to spend your time on social activities. You can even schedule these things.
2. We make sure you stay in tune with your interests by delivering the right Feeds or updates to you instead of spamming you with stuff you may not like.
3. You can schedule posts or messages which are to be sent at a later time and it will automatically be sent at that time

<b>Better way to organize stuff</b>
We provide a feature called lists which you can use to organize anything like creating circles having people, organizing movies you have watched, going to watch, music you want to hear, your wishlists, anything. Name it. All these things are properly categorized according to your needs.

<b>You can always share with people whom you think would be the best audience</b>
1. You can choose your own audience for your posts instead of just being able to specify the people or set of people or location where your post has to be visible. Apart from providing this functionality, we also help you in sharing this with people of specific interests, age group, gender, language, occupation, education and many other criteria available at your disposal.

<b>Entertainment with Productivity</b>
1. You can listen to music while you chat or browse through, you can even browse through you friend’s playlists thus making the entire experience social
2. What if the aim in your life is to become a professional gamer? We will make sure we help you do that by providing status from your Steam/similar accounts when connected and also give you tips depending on your gameplay
3. Social APPS of the next level so that developers can also make use of the APIs we provide, involve people and take part in this revolution.

<b>Backups & Retrieval</b>
We have made sure that your information is always yours and thus you are always accessible to it online or offline by providing backups for every data you have online. You can choose to download backups of anything (any data, files, lists etc.) and you can browse through and also use it to recover any deleted info by uploading online when needed.

<b>Mobiles, Handhelds & other Platforms</b>
We are working continuously to make sure this product is available in maximum platforms as possible with high compatibility and also not compromising on the cutting edge features for people using the latest technologies. Our designs are responsive and thus can be used in screens with any resolution. And since the platform is made in a browser, you can use it in any operating system of your choice.
We do know that with the world changing towards mobile first usage, we are taking incorporation of mobile designs very seriously.

<b>The new world for Entrepreneurs & Businessmen from very small to very large</b>
What if we say, your product is not about just buying or selling or providing services but also about creating a big impact on people, the right people at the right time and also generating good cash flow to help you expand bigger and better. What if you can do all this at a huge pace? What if you can have a platform where you can choose the audience you want to have for your products, the right audience. What if you can have various ways to express your product? What if you can do this all economically and to your own budget?

Well, we have ways for that too. We take advertising and branding to the next level not by  providing ads or content to people who may not be interested in your product by helping you choose the right audience depending on various criteria. We also help you improve the audience of your product by optimizing the AD with your consultation and delivering it in a way which people would like to see. 

And all this done in an unobstructive manner. The new generation of ADS. You won't have big banners or pop ups or flash or obstructive content but we will make sure the ADS fit in at the right place. Infact, you can choose where the ADS must fit. You can make all the customizations you want, deliver it to the people you want, monitor the campaign.
If you are not interested in continuing the campaign anymore and you have money left on your online prepaid budget, you can immediately get the remaining money back too. And what if you have 24/7 support for all this!

Well, when I say we provide all these features, what about the features the existing Social Networking websites already provide?

Of Course we provide them as well. Posts, comments & replies, realtime messaging & chat, video & audio conferencing, gallery where you can upload photos, video, audio, documents and whatever you wish, groups where you can add people to have all sorts of discussions, feeds where you can have real time updates about your network, search which you can always use to search for anyone, any place or anything, facility to tag pictures, syncing data across devices, and lots more. You just have to explore this new world.

<b>So, you say you provide all this stuff. How can you guarantee that me and my data are always secure online?</b>
We seriously take security since everyone and everything is important to us. We have thus made sure that we encrypt all the sensitive info like passwords, the data is always stored in our servers and not on any cloud based solutions, the data is never shared without your concern and after all, you get to have your own privacy policy and we strictly adhere to that. If you find anyone trying to misuse the entire Social Networking experience by posting any abusive or illegal content, creating any physical or mental stress, spreading negativity, spamming stuff, making fake profiles or anything which affects the society, all you have to do is bring it to our attention by using the Report button you have corresponding to the content/person and we will make sure everything is monitored properly and taken care of.

<b>Now, how can you take part in this revolution?</b>
We are highly dependant on all of you and without you, there is no us. We highly respect and love every user we have and also the ones we don’t have. If you would like to join hands with us in this revolution, you can do so by any or all of these ways:

1. Inviting people to use our platform which will help us to create a big network of people
2. Sending Feedbacks, Suggestions or Bug Reports to anything you feel about this platform
3. Reporting abusive content so that we will be able to keep the platform always better for you.
4. Being active while you can so that we will be able to generate a good repository of rich and useful information consumable for everyone with the content you provide.
5. Working with us in development of our products in the role which you are interested in and suited for.
6. And of course we do rely on investors and advertisers to help us take our company forward at an accelerated and stable pace.

<b>So, are all the features readily available now?</b>
Since there is only 1 individual working in this currently (myself), it takes a some time to get every feature ready. Every user will receive all the updates on a daily basis and I will also be making regular tutorials on how to use every feature. So, everything is set on your side.

<b>Who I am</b>
If I have to give out my official status, I am the Founder of Tech Ahoy and Friendbus.
If I have to give my personal status, I am just a normal guy like every one else with a good taste for Technology, music, entrepreneurship, online gaming (AOE,DOTA 2 and more..), someone who plays amateur badminton now and then, a movie critic who has watched thousands of movies, a crazy Avatar Fan (animated series) and a lot more. 
I just cant explain myself in words here. I am much more :)

You can get in touch with me at:
<b>Friendbus</b> ()
<b>Facebook</b> (http://www.facebook.com/tvvignesh)
<b>Google Plus</b> (https://plus.google.com/+tvvignesh)
<b>Linked In</b> (http://in.linkedin.com/in/tvvignesh)
<b>Twitter</b> (https://twitter.com/techahoy)
<b>Email</b> (vigneshviswam@gmail.com   &  tvvignesh@techahoy.in)
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