<?php 

session_start();

/* 
AnimCaptcha v1.1 >> 5-31-07

This Animated Gif Captcha system is brought to you courtesy of ...
josh@betteradv.com                                    ==> Josh Storz
http://www.querythe.net/Animated-Gif-Captcha/         ==> Download Current Version

OOP (PHP 4 & 5) Interface by ...
krakjoe@krakjoe.info                                  ==> J Watkins

The GIFEncoder class was written by ...
http://gifs.hu                                        ==>  László Zsidi
http://www.phpclasses.org/browse/package/3163.html    ==>  Download Current Version 

This file is part of QueryThe.Net's AnimatedCaptcha Package.

    QueryThe.Net's AnimatedCaptcha is free software; you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    QueryThe.Net's AnimatedCaptcha is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with QueryThe.Net's AnimatedCaptcha; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA */

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

  <head>
    <meta http-equiv="content-type" content="text/html; charset=windows-1250">
    <title>Animated Gif Captcha</title>
  </head>
  
  <body>

    <table width="300" align="center">
      <tr>
        <td>
<!-- Start Copy Here -->
          <? 
          // start AnimatedCaptcha verification process
          if (isset($_POST['AnimCaptcha']))
            {
              if (is_numeric($_POST['AnimCaptcha']))
                {
                  if ( $_POST['AnimCaptcha'] == $_SESSION['answer'])
                    {
                      echo "<p><b>Your answer is correct!</b></p><p><a href=''>Try again</a></p>" ;
                    }
                  else
                    {
                      echo "<p><b>Incorrect answer!<br />The correct answer is ... " . $_SESSION['answer'] . "</b></p><p><a href=''>Try again</a></p>" ;
                    }
                }
              else
                {
                  echo "<p><b>Enter Numbers or (+/-) only.  No alphabetical characters accepted.</b></p><p><a href=''>Try again</a></p>" ;
                }
            }
            // end AnimatedCaptcha verification process
          else
            { 
            // AnimatedCaptcha build a self submitting form
            ?>
              <p>
                <!-- Uncoment below if you would prefer using an OOP solution -->
                <img src="index.php" style="border: 1px dashed silver;">
                <!--<img src="OOP5.php" style="border: 1px dashed silver;">-->
                <!--<img src="OOP4.php" style="border: 1px dashed silver;">-->
            
                <form action="" method="post">
                  <input type="text" style="width:160px;" name="AnimCaptcha">
                  <input type="submit" value="Answer Now!"><br />
                  
                  <span style="font-size:9px;"><a href="">New question</a></span>
                </form><br /><br />
              </p>
          <? // end AnimatedCaptcha build a self submitting form
             } ?>
<!-- End Copy Here -->
        </td>
      </tr>
    </table>

  </body>
</html>
