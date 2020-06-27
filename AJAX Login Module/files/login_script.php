<?php
/**
 * Ajax Login Module v1.0
 *
 * Ajax Login Module is a simple AJAX login page that is very easy to 
 * plug into your existing php application with no need for further configuration and coding.
 *
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2009, Christopher M. Natan
 * @link          http://phpstring.co.cc/phpclasses/modules/ajax-login-module/
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 *
 */
?>

<script type="text/javascript">
       $(document).ready(function() { 
           formLogin();
           function	formLogin() {
                $("#container").fadeIn();
				var options = { 
                    target       :  '.<?php echo $this->target_element; ?>',
                    timeout      :    <?php echo $this->timeout;?>,    
                    beforeSubmit :   request,  
                    success      :   response  
                }; 
               $('.<?php echo $this->form_element;?>').submit(function() {  $(this).ajaxSubmit(options); return false;});   
                function request(formData, jqForm, options) { 
                    valid = true;
                    $('.<?php echo $this->wait_element; ?>').hide();
                    var label = "<span class='ajax_spinner'><img src='files/ispinner.gif'/><?php echo $this->wait_text;?></span>";
                    $(".<?php echo $this->wait_element; ?>").after(label);
                    $('.<?php echo $this->notify_element; ?>').hide();						
                    if(valid) {
                      return true;
                    } else { 
                     $('.<?php echo $this->wait_element; ?>').show();
					 $('.ajax_spinner').fadeOut();
					 $(".ajax_spinner").remove();
					 $('.<?php echo $this->notify_element; ?>').fadeIn(); 
                      return false;
                    } 
                } 
                function response(responseText, statusText) {
				   $('.<?php echo $this->wait_element; ?>').show();
                   $('.ajax_spinner').fadeOut();
				   $(".ajax_spinner").remove();	
				 }
            }		
        }); 		
 </script>