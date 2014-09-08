Nifty Newsletter
=========

Just a *nifty* Laravel Newsletter Application. [Demonstration] [demo] ``` demo@email-newsletterinfo / D3mopass!```


Pre-requisite
-----------
  - [Mailgun account] [mailgun] 


Features
-----------
  - Mail-lists for grouping different subscribers
  - Tags for grouping different emails
  - Email tracking 
  - Angular JS/Google Charts email stats
  - PDF creation from sent emails ([Simple PDF API] [pdf])
  - Handling of unsubscribes 
  - User management

Installation
-----------
  - Clone the repo into your development environment 
  - Create a database and edit the ```app/config/database.php``` to add these details
  - Install the migrations (```php artisan migrate```) or import the provided ```database.sql```
  - Add your Mailgun private key and domain to ```app/config/packages/bogardo/mailgun/config/php``` You can make additional settings here if you desire
  - Visit the frontend of your site and follow instructions from there.

Finally
-----------
  - Make sure your addresses are clean otherwise mailgun will suspend your domain
  - Please report bugs/feature requests 
  - And please feel free to [contribute to my piggy-bank for a new cup of coffee] [paypal] :)


License
----

MIT


[mailgun]:http://www.mailgun.com/
[demo]:http://demo.email-newsletter.info/
[pdf]:http://simplehtmltopdf.com/
[paypal]:https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K9XM6BYDCS4GW