# RetirementCalc

Login information:

    database: Retirement_Tool

    table: Login_info

    columns: id, username, password, full_name, email

ret_login.php: 

    change LINE 12 --> $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
    
    to --> $mysqli = NEW MySQLi('localhost', 'root', password', 'Retirement_Tool');
    
    change LINE 23 --> header("location: http://localhost/~sjakka/RetirementTool/retirement_user.php");
    
    to whatever the filepath is for retirement_user.php
    
    change LINE 73 --> <a href="http://localhost/~sjakka/RetirementTool/retirement_guest.php" style="margin-left:10;
    
    to whatever the filepath is for retirement_guest.php
    
create_account.php:

    change LINE 14 --> $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
    
    change LINE 34 --> header("location: http://localhost/~sjakka/RetirementTool/retirement_user.php");
    
    change LINE 87 --> <a href="http://localhost/~sjakka/RetirementTool/retirement_guest.php" style="margin-left:10; color:black;">

retirement_guest.php:
    
    change LINE 12 --> <a href="http://localhost/~sjakka/RetirementTool/ret_login.php" style="margin-left:10; color:white;">
    
retirement_user.php:

    change LINE 7 --> $mysqli = NEW MySQLi('localhost', 'root', 'jakka_sm', 'Retirement_Tool');
