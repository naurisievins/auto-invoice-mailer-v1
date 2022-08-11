# auto-invoice-mailer-v1
This application is made for local use. For ease of use try "PHP Desktop".

1. Set location of xlsx file and invoice directory in path.php.
2. Xlsx file is used as a client database.
    - column "e-mail address" - e-mail address where invoice will be sent to
    - column "invoice identificator" - invoice file MUST start with this ID. For example - invoice identificator "john_willow", invoice file name                 "john_willow_542gfaX_2022_1.pdf";
    - column "send" - set it to "1" if you want to send an invoice to that client.
3. Edit mail.php and set:
    - $mail->Host       = '';
    - $mail->Username   = '';
    - $mail->Password   = '';
as well as set your e-mail message there.
