# auto-invoice-mailer-v1
This application is made for local use. For ease of use try "PHP Desktop".

--- HOW TO USE ---
1. Set location of xlsx file and invoice directory in path.php.
2. Xlsx file is used as a client database.
    - column "e-mail address" - e-mail address where invoice will be sent to;
    - column "invoice identificator" - invoice file MUST start with this ID. For example - invoice identificator "john_willow", invoice file name                 "john_willow_542gfaX_2022_1.pdf";
    - column "send" - set it to "1" if you want to send an invoice to that client.
3. Open mail.php and edit your e-mail text as well as set:
    - $mail->Host       = '';
    - $mail->Username   = '';
    - $mail->Password   = '';

--- HOW DOES IT WORK ---

First you need to create a client database in xlsx format. 
To send an invoice you have to:
    
    - set xlsx file and invoice directory locations in path.php;
    - set "1" in the "send" column in xlsx file;
    - place invoice file in the invoice directory;
    - invoice file must start with "invoice identificator";
    
You won't be able to confirm sending if there is no invoice file or there is more than 1 file found.
You can check log file for sent or failed mails.
Log file can be cleared.
    
