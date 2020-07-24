# dreamworld

Config.php is empty for security reasons

You may run the test at http://willysites.com/dreamworld/index.php

I put an additional way to get a list of the existent emails http://willysites.com/dreamworld/index.php?op=list

To create the table and generate the test accounts you want to use create.php but because this is a cpanel web hosting there is no way to run it. I ran it locally in my computer.

Thanks for the oportunity to apply for this position.

GOAL

Make an HTML form, submit form data to PHP, validate the data, insert into a database, and return a success response to client

SPECS

Write plain PHP, HTML, and CSS-- no libraries, frameworks, or JavaScript
Output an HTML document including email, password, confirm password, birthday, and submit button
Center the form on the page vertically and horizontally
Make it pretty (hint: bonus points if you use Dream Singles color scheme, logo, and a picture)
Make a list of (10 or more) email addresses
Validate:
    email address must not exist in list  
    birthday must be 18+  
    password matches confirm password at least 8 characters and contains numbers and letters
When input is invalid return a response to let the user know what inputs to correct
Otherwise, input is valid create a schema for a users table if not exists with the above fields and save it to your new Mysql table using PDO
Return a response to the user updating them on the status of their request 

Guillermo Williamson
