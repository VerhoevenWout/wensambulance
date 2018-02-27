=== Plugin Name ===
Contributors: ndijkstra
Tags: mollie,doneren,donate,ideal,mistercash,bancontact,bitcoin,creditcard,paypal,sofort,belfius,overboeking,recurring,incasso,debit,herhaalbetalingen,sepa,subscriptions,kbc,cbc
Requires at least: 3.0.1
Tested up to: 4.9.1
Stable tag: 2.4.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is both suitable for one-time donations and for periodic payments. All payment methods of Mollie are integrated into the plugin.

== Description ==

Are you looking for a simple donation plugin for a charity or for example the local football club? This plugin is both suitable for one-time donations and for periodic payments. All payment methods of Mollie are integrated into the plugin. The plugin is also available in several languages: Dutch, English, German and French.

= Features =

Although the plugin is very easy to install, it contains enough options:

* Donations can be found in the WordPress admin panel.
* Donors can enter their details.
* You can specify different projects, so donors can choose which project they want to donate.
* You can choose which data donors should provide for the donation.
* You can set a redirect page yourself.
* You can specify by default the choice of the donor.
* You can style the form as desired.

= Recurring payments =

In addition to one-time donations, this plugin is also useful for collecting periodic amounts. This works on the basis of Mollie's "Subscription API". This system makes it possible, for example, to collect a certain amount monthly, quarterly or annually by credit card or by SEPA Direct Debit.

Please take a look at [Mollie Forms](https://wordpress.org/plugins/mollie-forms/) which contains more features to create forms with payments.

== Frequently Asked Questions ==

= Can I use shortcodes? =

Yes! The following shortcodes are available:

* [doneren_met_mollie] To display the form
* [doneren_met_mollie_total] To display the total raised money
* [doneren_met_mollie_goal goal="1000" text="Goal reached!"] Countdown to your goal. Goal must be higher then 0 and the text will be displayed when the goal is reached

== Installation ==

1. Install "Doneren met Mollie" in Wordpress
1. Activate plugin in Wordpress
1. Create an account at Mollie.com and copy the Live API-key
1. Go to the settings page in "Doneren met Mollie" and fill in the Live API-key
1. Place the shortcode [doneren_met_mollie] on a page to show the form
1. You can also use the optional shortcode [doneren_met_mollie_total] to show the total raised money

== Changelog ==

= 2.4.10 - 10/01/2018 =
* Updated Mollie API Client to v1.9.4

= 2.4.9 - 20/12/2017 =
* Bugfixes

= 2.4.8 - 30/11/2017 =
* Added shortcode [doneren_met_mollie_goal]. See the FAQ for more info
* Bugfixes

= 2.4.7 - 29/11/2017 =
* Bugfixes

= 2.4.6 - 27/11/2017 =
* Translation bugfixes

= 2.4.5 - 06/11/2017 =
* Use translations from wordpress.org

= 2.4.4 =
* Update Mollie API Client to v1.9.1
* Deleting donations is now possible
* Bug fixed with messages in export

= 2.4.3 =
* Variable {interval} possible in description, for the interval of the payment
* Visible in the export if the payment is recurring

= 2.4.2 =
* Use home url instead of site url

= 2.4.1 =
* Project is now visible in projects table
* Setting default interval possible

= 2.4.0 =
* Metadata is also used for first payments
* Set rights for donations and subscriptions
* Visible in donations if payment is recurring

= 2.3.3 =
* Bug fixes

= 2.3.2 =
* Bug fix when redirecting to page in some Wordpress installations

= 2.3.1 =
* Address fields didn't show, this is now fixed

= 2.3.0 =
* [doneren_met_mollie_total] shortcode added to display the total raised money
* At list display the first option is selected by default

= 2.2.4 =
* Bug fixes

= 2.2.3 =
* Bug fixes

= 2.2.2 =
* Fix when Wordpress is used in a sub directory

= 2.2.1 =
* Fixed bug with cancelling subscriptions
* Default payment description set

= 2.2.0 =
* Recurring wasn't possible after change at Mollie API, this is now solved
* Export donations to CSV possible

= 2.1.7 =
* First amount for recrring payment is now the amount of the first period instead of €0,01

= 2.1.6 =
* Email address possible in description with variable {email}
* Update Mollie API client to v1.7.1
* Bug fixes

= 2.1.5 =
* Bug fixes

= 2.1.4 =
* Search function for donations added
* Webhook system updated
* Translation bug fixes
* Several bug fixes

= 2.1.3 =
* Database problems solved

= 2.1.2 =
* Problem solved with updating fields when recurring is active

= 2.1.1 =
* Webhook issues resolved
* Selection menu interval not visible if recurring not enabled
* Fixed problem with translation options payment methods
* Message field now also has full width
* If a recurring payment, only available verification methods are visible

= 2.1.0 =
* Recurring Payments now available!
* Possibility to set the minimum amount to be donated

= 2.0.1 =
* Plugins now also translated into French and German!

= 2.0.0 =
* Settings made more clear
* Free entry and drop-down amount at the same time
* Variables are included in the description
* Choose the display of payment methods
* Possible to add projects
* Added more fields
* Make fields active and / or mandatory
* Add more classes possible
* Translated into Dutch and English
* Code improved
* Bugs resolved


== Upgrade Notice ==

= 2.0.0 =
Herhaalbetalingen (recurring payments) zijn nu beschikbaar!

== Screenshots ==

1. Donaties zichtbaar in admin
2. Meer informatie over de donatie en donateur
3. Algemene instellingen
4. Formulier instellingen
5. Classes instellen
6. Mollie instellingen
7. Abonnementen (doorlopende donaties)
8. Recurring instellingen