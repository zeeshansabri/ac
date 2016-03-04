<?php
class Mailer extends CI_Model {

	function __construct() {
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
		$this->load->library('email');
	}

	function configuration(){
		
			$config['protocol'] = 'sendmail';
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			
			return $config;
	}
	
	function sendinvoice($Name, $Email, $CompanyName, $Code, $InvID){
	
			$config = $this->configuration();
			
			$this->email->initialize($config);
			
			$this->email->from('info@mmsservices.com', 'MMS Services Web Team');
			$this->email->to("zeeshan@mmsservices.co.uk"); 

			$this->email->subject('Invoice - New Invoice has been added to your account');
			
			$html = '<head>
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
        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least"><div class="logo-center" style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 243px" src="' . PATH . '/upload/media/logo.png" alt="" width="243" height="53" /></div></td></tr>
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
                              
            <h1 style="Margin-top: 0;color: #565656;font-weight: 700;font-size: 36px;Margin-bottom: 18px;font-family: sans-serif;line-height: 42px">Invoice</h1>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Dear '. $Name .',</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> A new invoice has been created for ' . $CompanyName . '. Please click the link below to view the invoice.</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> 
            <a href="'. base_url() .'invoice/view_invoice/'.$InvID.'?Code='.$Code.'" target="_blank">Invoice: ' . $InvID . '</a><br />
            if the link above doesn\'t work then copy and paste the following link to your browser.<br>
            ' . base_url() . 'invoice/view_invoice/'.$InvID.'?Code='.$Code.'
            </p><br>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 24px">Best Regards<br />
MMS Services Web Team<br />
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
			
			$this->email->message($html);
			
			$this->email->send();  
			
	}
	
	function requestrate($Country, $Network, $RouteType, $Prefix, $Email, $CompanyName){
	
			$config = $this->configuration();
			
			$this->email->initialize($config);
			
			$this->email->from('info@mmsservices.co.uk', 'MMS Services Web Team');
			$this->email->to("zeeshan@mmsservices.co.uk"); 

			$this->email->subject('Rate Request - A new request has been placed by ' . $CompanyName);
			
			$html = '<head>
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
        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least"><div class="logo-center" style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 243px" src="' . PATH . '/upload/media/logo.png" alt="" width="243" /></div></td></tr>
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
                              
            <h1 style="Margin-top: 0;color: #565656;font-weight: 700;font-size: 36px;Margin-bottom: 18px;font-family: sans-serif;line-height: 42px">New Request</h1>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Dear Admin,</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> A new request for rate has been placed by ' . $CompanyName . '. Please contact the user to discuss the rates. Their request details are</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> 
            Company Name: ' . $CompanyName . '<br />
            Country: ' . $Country . '<br />
            Network: ' . $Network . '<br />
            Route Type: ' . $RouteType . '<br />
            Prefix: ' . $Prefix . '<br />
            
            <br>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 24px">Best Regards<br />
MMS Services Web Team<br />
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
			
			$this->email->message($html);
			
			$this->email->send();  
			
	}
	
	function neworderadmin($CompanyName, $OrderNumber){
	
			$config = $this->configuration();
			
			$this->email->initialize($config);
			
			$this->email->from('info@mmsservices.co.uk', 'MMS Services Web Team');
			$this->email->to("zeeshan@mmsservices.co.uk"); 

			$this->email->subject('New Order - A new order has been placed by ' . $CompanyName);
			
			$html = '<head>
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
        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least"><div class="logo-center" style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 243px" src="' . PATH . '/upload/media/logo.png" alt="" width="243" /></div></td></tr>
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
                              
            <h1 style="Margin-top: 0;color: #565656;font-weight: 700;font-size: 36px;Margin-bottom: 18px;font-family: sans-serif;line-height: 42px">New Order</h1>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Dear Admin,</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> A new order has been placed for ' . $CompanyName . '. Please login to the admin panel to review order details.</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> 
            Company Name: ' . $CompanyName . '</a><br />
            Order Number: ' . $OrderNumber . '</a><br />
            
            <br>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 24px">Best Regards<br />
MMS Services Web Team<br />
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
			
			$this->email->message($html);
			
			$this->email->send();  
			
	}
	
	function neworderuser($CompanyName, $OrderNumber, $Email){
	
			$config = $this->configuration();
			
			$this->email->initialize($config);
			
			$this->email->from('info@mmsservices.co.uk', 'MMS Services Web Team');
			$this->email->to("zeeshan@mmsservices.co.uk"); 

			$this->email->subject('Thank you for your order - ' . $OrderNumber);
			
			$html = '<head>
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
        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least"><div class="logo-center" style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 243px" src="' . PATH . '/upload/media/logo.png" alt="" width="243" /></div></td></tr>
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
                              
            <h1 style="Margin-top: 0;color: #565656;font-weight: 700;font-size: 36px;Margin-bottom: 18px;font-family: sans-serif;line-height: 42px">New Order</h1>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Dear '. $CompanyName .',</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> Thank you for placing order with us. Please wait while our NOC team process your order, we will send you another email once your order is delivered to your server.</p>
            
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> In the mean time if you have any enquiry please get in touch with one of our team members. 
            </p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> 
            
            Your Order Reference: ' . $OrderNumber . '<br />
            
            <br>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 24px">Best Regards<br />
MMS Services Web Team<br />
&nbsp;</p>
          
                            </td>
                          </tr>
                          <td style="color:#b0b0b0" align="center">Powered by <a style="color: #acacac" href="http://mmsservices.co.uk"> MMS Services</a></td>
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
			
			$this->email->message($html);
			
			$this->email->send();  
			
	}
	
	function sendreferences($email, $body){
	
		$config = $this->configuration();
		
			$TO = $email;
			
			$this->email->initialize($config);
			
			$this->email->from('info@mmsservices.co.uk', 'MMS Services');
			$this->email->to($TO); 

			$this->email->subject('Reference verfication');
			
			$html = '<head>
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
			        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least"><div class="logo-center" style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 243px" src="' . PATH . '/upload/media/logo.png" alt="" width="243" height="53" /></div></td></tr>
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
			                              
			            <h1 style="Margin-top: 0;color: #565656;font-weight: 700;font-size: 36px;Margin-bottom: 18px;font-family: sans-serif;line-height: 42px">Reference</h1>'
			            
			            . $body .
			            
			            '
			&nbsp;</p>
			          
			                            </td>
			                          </tr>
			                          <td style="color:#b0b0b0" align="center">Powered by <a style="color: #acacac" href="http://mmsservices.co.uk"> MMS Services</a></td>
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
			
			$this->email->message($html);
			
			$this->email->send();  
			
	}
	
	function applyemail($email, $Name, $PageURL){
	
		$config = $this->configuration();
		
			$TO = $email;
			
			$this->email->initialize($config);
			
			$this->email->from('info@laojee.com', 'Lao Web Team');
			$this->email->to($TO); 

			$this->email->subject('Thank you - New Job Apply');
			
			$html = '<table align="left" width="100%">
			</tbody>
				<tr>
					<td style="background: #f4f4f4; padding-left:10px;" colspan="3"><br>
					Dear ' . $Name . ', <br>
					<br>
						Thanks for applying for this position.  Your CV has been directly sent to the Employer from our HRB [Human Resource Bank] Electronic databank.  Normally Employers take 3 working week to review, evaluate and finalize a candidate.  We hope that you will get a positive reply from the Employer, <br> <br>
						
						Direct link to the job is: ' . base_url() . 'jobs/detail/' . $PageURL . '
						
						However, If you do not get a response from them, kindly assume that your CV was not considered at this time.  We encourage you to please keep on applying for other jobs which are suiting to your profession, qualification and experience. <br><br>
						
						Kindly do not forget to update your CV [in our database] every 6 months [once a year] with current Job title, company and the change in salary, etc.. <br>
						<br><br>
						</td>
					</tr>
						
						<tr>
							<td style="background: #f4f4f4; padding-left:10px; padding-bottom:10px;" colspan="3">
								<br>
								Best Wishes<br>
								LaoJee Team <br>
								+92 333 123 456<br>
								info@laojee.comcom<br>
								www.LaoJee.com<br>
								<br>
							</td>
						</tr>
						<td style="color:#b0b0b0" align="center">Powered by <a style="color: #acacac" href="http://mmsservices.co.uk"> MMS Services</a></td>
					</tbody>
				</table>
						';
			
			$this->email->message($html);
			
			$this->email->send();  
			
	}
	
	function contactform($From, $To, $Name, $Message, $Subject, $Phone = NULL){
	
		$config = $this->configuration();
			
			$this->email->initialize($config);
			
			$this->email->from($From, $Name);
			$this->email->to($To); 

			$this->email->subject('Contact Form - '. $Subject);
			
			$html = '<head>
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
        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least"><div class="logo-center" style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 243px" src="' . PATH . '/upload/media/logo-jobolo.png" alt="" width="243" /></div></td></tr>
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
                              
            <h1 style="Margin-top: 0;color: #565656;font-weight: 700;font-size: 36px;Margin-bottom: 18px;font-family: sans-serif;line-height: 42px">Contact</h1>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Dear Admin,</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> ' . $Name . ' has sent you a message using contact form. Their details are: </p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> 
            Name: ' . $Name . '<br />
            Email: ' . $From . '<br />
            Message: ' . $Message . '<br />
            Phone: ' . $Phone . '<br />
            <br>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 24px">Best Regards<br />
Jobolo Web Team<br />
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
			
			$this->email->message($html);
			
			if($this->email->send()){
				return 'success';
			}   
			
	}
	
	function contactformThankyou($To, $From, $Name, $Message){
	
		$config = $this->configuration();
			
			$this->email->initialize($config);
			
			$this->email->from($From, 'MMS Services Web Team');
			$this->email->to($To); 

			$this->email->subject('Thank you ');
			
			$html = '<head>
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
        <tr><td class="logo" style="padding: 32px 0;vertical-align: top;mso-line-height-rule: at-least"><div class="logo-center" style="font-size: 26px;font-weight: 700;letter-spacing: -0.02em;line-height: 32px;color: #41637e;font-family: sans-serif;text-align: center" align="center" id="emb-email-header"><img style="border: 0;-ms-interpolation-mode: bicubic;display: block;Margin-left: auto;Margin-right: auto;max-width: 243px" src="' . PATH . '/upload/media/logo-jobolo.png" alt="" width="243" height="auto" /></div></td></tr>
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
                              
            <h1 style="Margin-top: 0;color: #565656;font-weight: 700;font-size: 36px;Margin-bottom: 18px;font-family: sans-serif;line-height: 42px">Thank You</h1>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px">Dear ' . $Name . ',</p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 25px"> Thank you for your message. One of our team member will be in touch with you. </p>
            
            <p style="Margin-top: 0;color: #565656;font-family: Georgia,serif;font-size: 16px;line-height: 25px;Margin-bottom: 24px">Best Regards<br />
Jobolo Web Team<br />
&nbsp;</p>
          
                            </td>
                          </tr>
                          <td style="color:#b0b0b0" align="center">Powered by <a style="color: #acacac" href="http://mmsservices.co.uk"> MMS Services</a></td>
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
			
			$this->email->message($html);
			
			$this->email->send();   
			
	}
	
	function forgotemail($Password, $email){
	
		$config = $this->configuration();
		
			$TO = $this->input->post('email');
			
			$this->email->initialize($config);
			
			$this->email->from('info@laojee.com', 'Lao Web Team');
			$this->email->to($TO); 

			$this->email->subject('Forgot Password');
			
			$html = '<table align="left" width="100%">
			</tbody>
				<tr>
					<td style="background: #f4f4f4; padding-left:10px;" colspan="3"><br>
					Dear, <br>
					<br>
						We have received a request of forgot password, we have created a new password for you. <br>
						We recommend you keep this e-mail to store your credentials. <br>
						<br><br>
						</td>
					</tr>
					
				 <tr style="background: #f4f4f4; color: #050c64;">
							<td align="left" style="padding-left:10px;"><h3> E-mail address: ' . $email . '</h3></td>
							<td align="center"><h3>Password: ' . $Password . '</h3></td>
						</tr>
						
						<tr>
							<td style="background: #f4f4f4; padding-left:10px; padding-bottom:10px;" colspan="3">
								<br>
								We recommend you to login to your account and change password to something more memorable.<br>
								Best Wishes<br>
								LaoJee Team <br>
								+92 333 123 456<br>
								info@laojee.comcom<br>
								www.LaoJee.com<br>
								<br>
							</td>
						</tr>
					</tbody>
				</table>
						';
			
			$this->email->message($html);
			
			$this->email->send();  
			
	}	
	
}
?>
