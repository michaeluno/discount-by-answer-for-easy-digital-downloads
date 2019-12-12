=== Discount by Answer for Easy Digital Downloads ===
Contributors:       Michael Uno, miunosoft
Donate link:        http://en.michaeluno.jp/donate
Tags:               EDD, Easy Digital Downloads, Promotion, Discount, Discounts, Survey, Fields, Marketing, Campaign, Addon, Add-on
Requires at least:  4.1
Requires PHP:       5.2.4
Tested up to:       5.3.1
Stable tag:         1.0.1
License:            GPLv2 or later
License URI:        http://www.gnu.org/licenses/gpl-2.0.html

Lets your customers answer your requests and issue discount codes in return.

== Description ==

<h4>Give Your Customer Incentives to Contribute to Your Work</h4>

Do you want to give discounts to those who contribute? But it is cumbersome for both of you and your customer to negotiate through conversations with each other, especially when you don't know how many of them you have to talk to.

Some are slow to respond and don't reply quickly. Some may be so demanding. Not everybody has a good manner. Dealing with those is oftentimes tiresome, stressful, time-consuming, and exhausting, which eventually makes you weary.

<h4>Don't be worn out by talking to too many people</h4>

What if there is a form that automatically does that?

This plugin lets you create a small form which you can arrange, displayed in the checkout page along with the native discount code field, which most customers will notice when purchasing.

Let's say you have a web page that lists contributors. Then, create a campaign with a request form with this plugin. It has an option to accept answers from text of a web page. Now then your contributors can enter their name in your form and get a discount code right away!

<h4>Improve Your Service through a Survey</h4>
Also, you can create a survey form and collect answers from your valuable customers to improve your service and give them discounts in return!

<h4>Find Out The Most Effective Campaign With Conversion Rates</h4>
After you create campaigns, conversion rates will be listed so that you know which one works best for you!

= Supported Languages =
* English
  
== Installation ==

1. Upload **`discount-by-answer-for-easy-digital-downloads.php`** and other files compressed in the zip folder to the **`/wp-content/plugins/`** directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Create a base discount code first which campaign discount codes will be based on.
1. Go to **Dashboard** -> **Downloads** -> **Discount Campaigns** -> **Add New Campaign**
1. Select a base discount code you just created and click **Add**.
1. Try to purchase a download and proceed to the checkout page. There you should see a link to open the form. There your customer can fill the form fields which you define in the campaign settings.


== Frequently asked questions ==
<h4>How do I show a campaign form for a particular product?</h4>
Select associated products in the settings of the discount code you pick as the base (Dashboard -> Downloads -> Discount Codes -> Edit (of your choosing) ).

<h4>Is it possible to display multiple campaign forms at the same time?</h4>
Yes, possible. Just create multiple campaigns.

<h4>What happens if someone tries to get multiple discount codes with random answers?</h4>
Although discount codes will be issued as many as answers are accepted, if someone makes a purchase with a campaign discount code, he/she will be no longer able to use any of discount codes of the same campaign.

However, as an exception, site administrators with the `manage_options` capability are not limited to.

<h4>Is it possible to disallow answers with certain keywords?</h4>
Yes, you can do that with settings.

<h4>Is it possible to disallow answers with already answered keywords?</h4>
Yes, with [Pro](http://en.michaeluno.jp/discount-by-answer-for-easy-digital-downloads/discount-by-answer-pro-for-easy-digital-downloads/).

<h4>Are you open to feature requests?</h4>
Yes, definitely! Please post your ideas on the [support forum](https://wordpress.org/support/plugin/discount-by-answer-for-easy-digital-downloads/).

== Other Notes ==
If you are a developer and want to submit issues, visit the [GitHub repository](https://github.com/michaeluno/discount-by-answer-for-easy-digital-downloads).

== Screenshots ==

1. **Campaigns**
2. **Request Field Settings**
3. **Answers**
4. **Answer Details**
5. **Link to Open Fields in Checkout**
6. **Answer Fields in Checkout**

== Changelog ==

= 1.0.1 - 2019/12/13 =
- Fixed a bug that the discount start and expiration date (time) was not properly set due to applying the GMT time zone.

= 1.0.0 - 2019/02/03 =
- Released.