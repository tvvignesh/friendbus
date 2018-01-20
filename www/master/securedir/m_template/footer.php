<?php 
	if(isset($GLOBALS["footer_elem"]))
	{
		return "";
	}
	$GLOBALS["footer_elem"]=1;
?>
 <footer class="hidden-xs hidden-sm">
    <div class="footer" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3  col-md-3 col-sm-12 col-xs-12">
                    <h3>ABOUT US</h3>
                    <ul>
                        <li> <a href="/about.php">Who,What,How & Why</a> </li>
                        <li> <a href="/history.php">Our History</a> </li>
                        <li> <a href="/news.php">In the News</a> </li>
                        <li> <a href="/dash_announcements.php">Announcements</a> </li>
                    </ul>
                </div>
                <div class="col-lg-3  col-md-3 col-sm-12 col-xs-12">
                    <h3>LEGAL STUFF</h3>
                    <ul>
                        <li> <a href="/privacy.php">Privacy Policy</a> </li>
                        <li> <a href="/terms.php">Terms & Conditions</a> </li>
                        <li> <a href="/cookiepolicy.php">Cookie Policy</a> </li>
                        <li> <a href="/copyright.php">Copyright Policies</a> </li>
                        <li> <a href="/trademarks.php">Registration & Trademarks</a> </li>
                    </ul>
                </div>
                <div class="col-lg-2  col-md-2 col-sm-12 col-xs-12">
                    <h3>REACH US</h3>
                    <ul>
                        <li> <a href="/contact.php">Contact Us</a> </li>
                        <li> <a href="/careers.php">Careers</a> </li>
                        <li> <a href="/ads.php">Advertising</a> </li>
                        <li> <a href="/invest.php">Investing</a> </li>
                        <li> <a href="/help.php">Help & FAQ</a> </li>
                    </ul>
                </div>
                
                <div class="col-lg-3  col-md-3 col-sm-12 col-xs-12" align="center">
                    <h3>IN THE WEB</h3>
                   <!-- <ul>
                        <li>
                            <div class="input-append newsletter-box text-center">
                                <input type="text" class="form-control" placeholder="Your Email Address">
                                <button class="btn bg-gray text-primary" type="button">Keep me Updated<i class="fa fa-long-arrow-right"> </i> </button>
                            </div>
                        </li>
                    </ul>-->
                    <ul class="social">
                        <li style="margin-left:20%;"> <a href="https://www.facebook.com/techahoy"> <i class="fa fa-facebook"> </i> </a> </li>
                        <li> <a href="https://twitter.com/techahoy"> <i class="fa fa-twitter"> </i> </a> </li>
                        <li> <a href="https://plus.google.com/+TechahoyIn/"> <i class="fa fa-google-plus"> </i> </a> </li>                        
                    </ul>
                </div>
            </div>
            <!--/.row--> 
        </div>
        <!--/.container--> 
    </div>
    <!--/.footer-->
    
    <div class="footer-bottom">
        <div class="container">
            <p class="pull-left"> Copyright &#169; Tech Ahoy 2016. All right reserved. </p>
            <!-- <div class="pull-right">
                <ul class="nav nav-pills payments">
                	<li><i class="fa fa-cc-visa"></i></li>
                    <li><i class="fa fa-cc-mastercard"></i></li>
                    <li><i class="fa fa-cc-amex"></i></li>
                    <li><i class="fa fa-cc-paypal"></i></li>
                </ul> 
            </div>-->
        </div>
    </div>
    <!--/.footer-bottom--> 
</footer>


<?php 
$assetobj=new ta_assetloader();
$utilityobj=new ta_utilitymaster();
$logobj=new ta_logs();
if(!isset($userobj))$userobj=new ta_userinfo();
$comobj=new ta_communication();

$assetobj->load_js_final();
$utilityobj->enablebufferoutput();
$utilityobj->outputbuffercont();

if($userobj->checklogin())
{
	$userobj->userinit();
	$comobj->socket_add_user($userobj->uid);
	//$comobj->socket_sendnotif("test msg..");
}

$logobj->store_templogs("TOTAL QUERIES:".$GLOBALS["dbr_total"]."  Q:".$GLOBALS["dbr_query"]."  D:".$GLOBALS["dbr_del"]."  U:".$GLOBALS["dbr_update"]."  I:".$GLOBALS["dbr_insert"]);
?>
