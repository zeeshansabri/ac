<?

$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "mail.netflux.uk.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "webhost@netflux.uk.com";  // SMTP username
$mail->Password = "w38h05t"; // SMTP password

$mail->From = "webhost@netflux.uk.com";
$mail->FromName = "Web Host";
$mail->AddAddress("$from", "$fname");
$mail->AddReplyTo("webhost@netflux.uk.com", "NetFlux Web Team");

/* $mail->WordWrap = 50;                              // set word wrap to 50 characters */
$mail->IsHTML(true);                                  // set email format to HTML


	$mail->Subject =  "Thankyou";
	
	$mail->Body = '';
	
	$mail->Body .= '<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <center class="wrapper" style="display: table;table-layout: fixed;width: 100%;min-width: 620px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;background-color: #fbfbfb">
    	<table class="gmail" style="border-collapse: collapse;border-spacing: 0;width: 650px;min-width: 650px"><tbody><tr><td style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px">&nbsp;</td></tr></tbody></table>
      <table class="preheader centered" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto">
        <tbody><tr>
          <td style="padding: 0;vertical-align: top">
            <table style="border-collapse: collapse;border-spacing: 0;width: 602px">
              <tbody>
            </tbody></table>
          </td>
        </tr>
      </tbody></table>
      <table class="header centered" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;width: 602px">
        <tbody><tr><td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #e9e9e9;width: 1px">&nbsp;</td></tr>
        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least"><div class="logo-center" style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 243px" src="http://mmsservices.co.uk/netflux/php/email/images/netflux_logo.png" alt="" width="243" height="53" /></div></td></tr>
      </tbody></table>
      
          <table class="border" style="border-collapse: collapse;border-spacing: 0;font-size: 1px;line-height: 1px;background-color: #e9e9e9;Margin-left: auto;Margin-right: auto" width="602">
            <tbody><tr><td style="padding: 0;vertical-align: top">&#8203;</td></tr>
          </tbody></table>
        
          <table class="centered" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto">
            <tbody><tr>
              <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #e9e9e9;width: 1px">&#8203;</td>
              <td style="padding: 0;vertical-align: top">
                <table class="one-col" style="border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;width: 600px;background-color: #ffffff;font-size: 14px;table-layout: fixed">
                  <tbody><tr>
                    <td class="column" style="padding: 0;vertical-align: top;text-align: left">
                      <div><div class="column-top" style="font-size: 32px;line-height: 32px">&nbsp;</div></div>
                        <table class="contents" style="border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%">
                          <tbody><tr>
                            <td class="padded" style="padding: 0;vertical-align: top;padding-left: 32px;padding-right: 32px;word-break: break-word;word-wrap: break-word">
                              
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Dear ' . $fname . ',</p><p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">
             Thanks for your enquiry. We\'ll look into the details and endeavour to get back to you as quickly as we can.<br />
In the meantime, please rest assured our team are on the case and will respond to your enquiry in full as soon as possible. .</p>
            
			<p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 24px">
			Best Regards<br />
			NetFlux Web Team<br />
			&nbsp;</p>
          
                            </td>
                          </tr>
                        </tbody></table>
                      
                      <div class="column-bottom" style="font-size: 8px;line-height: 8px">&nbsp;</div>
                    </td>
                  </tr>
                </tbody></table>
              </td>
              <td class="border" style="padding: 0;vertical-align: top;font-size: 1px;line-height: 1px;background-color: #e9e9e9;width: 1px">&#8203;</td>
            </tr>
          </tbody></table>
        
          <table class="border" style="border-collapse: collapse;border-spacing: 0;font-size: 1px;line-height: 1px;background-color: #e9e9e9;Margin-left: auto;Margin-right: auto" width="602">
            <tbody><tr><td style="padding: 0;vertical-align: top">&#8203;</td></tr>
          </tbody></table>
        
      <div class="spacer" style="font-size: 1px;line-height: 32px;width: 100%">&nbsp;</div>
      
    </center>
  
</body></html>';
	
	
	if(!$mail->Send()){
		$Result = "Error";
	} else {
		$Result = "Sent";
	}	
?>