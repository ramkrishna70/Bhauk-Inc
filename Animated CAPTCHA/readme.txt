AnimatedCaptcha v1.1 >> 5-31-07

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA



***********************
**                   **
**     Features      **
**                   **
***********************

- Bold letters & numbers on a white background ... Easy for anyone to read and comprehend
- Layered Captcha image, very hard for robots to decipher
- Random frames number and time elapsed, so if they do decipher the animated gif AnimatedCaptcha will still keep them guessing
- Math oriented so it's language independent
- Questions are simple enough for a 10 year old to answer
- No co-dependencies on the GD library
- PHP 4 or 5 
- Optional Object Oriented Programming
- All browsers (except text based ones) can render a .gif flawlessly
- All answers are positive whole numbers (no decimals or fractions)
- 125 different question combinations by default
    
    

***********************
**                   **
**       Files       **
**                   **
***********************

readme.txt - this file
example.php - working implementation of AnimatedCaptcha
index.php - Displays a randomly generated animated gif & sets session variables
OOP4.php - Object-Oriented version of AnimatedCaptcha for PHP4
OOP5.php - Object-Oriented version of AnimatedCaptcha for PHP5
GIFEncoder.class.php - class for stitching frames together to create animation effect
/frames/ - folder containing all possible images
    - solve.gif ... 1st frame
    - equals.gif ... last frame
    - plus.gif, minus.gif, times.gif ... operators (+,-,*)
    - 0-9.gif ... an image for each possible number
    
    

***********************
**                   **
**   Installation    **
**                   **
***********************

1> Upload the AnimatedCaptcha folder (including sub-folders) to your host in a location visible to the web. Erase the version number at the end of the folder name.
2> Navigate to /AnimatedCaptcha/example.php and try the the script out
3> Implement into your existing site and enjoy a spam free environment :]
    
    

***********************
**                   **
**   Common Hacks    **
**                   **
***********************

1> Implement AnimatedCaptcha using an Object Oriented programming paradigm - OOP4.php & OOP5.php work flawlessly right out of the box.  Goto line 48 in example.php and uncomment your choice of implementations to switch to OOP.
2> Change the way the animation looks - create new frames using The GIMP or Photoshop and upload over the existing images in /frames/.  GIF FORMAT ONLY!!!
3> Increase / Decrease the time elapsed between frames - Seek out $time [ ] array in index.php, OOP4.php or OOP5.php and make your changes. 100 = 1 second.
4> Increase the number of answers - Search out rand(1, 9) in index.php {~line 21 & 22} and change them to your desired settings {ex. rand(0, 99) for random numbers between 0-99}.
5> Only use +/- (no multiplication) - Search out rand(0, 2) in index.php {~line 23} and change to rand(0, 1).
