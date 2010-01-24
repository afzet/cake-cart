
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE accounts (
  id int(10) NOT NULL AUTO_INCREMENT,
  website varchar(255) DEFAULT NULL,
  username varchar(100) DEFAULT NULL,
  acct_password varchar(100) DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE affiliates (
  id int(10) NOT NULL AUTO_INCREMENT,
  customer_code varchar(15) DEFAULT NULL,
  aff_code varchar(255) DEFAULT NULL,
  password_hashed varchar(255) DEFAULT NULL,
  contact_name varchar(100) DEFAULT NULL,
  check_name varchar(100) DEFAULT NULL,
  email varchar(255) DEFAULT NULL,
  email_confirm varchar(255) DEFAULT NULL,
  address varchar(255) DEFAULT NULL,
  city varchar(255) DEFAULT NULL,
  state varchar(255) DEFAULT NULL,
  zip varchar(255) DEFAULT NULL,
  country varchar(100) DEFAULT 'USA',
  phone varchar(255) DEFAULT NULL,
  mobile varchar(255) DEFAULT NULL,
  website varchar(255) DEFAULT NULL,
  date_of_birth date DEFAULT NULL,
  ssn varchar(255) DEFAULT NULL,
  tax_id varchar(255) DEFAULT NULL,
  verification varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE blocks (
  id int(10) NOT NULL AUTO_INCREMENT,
  `full` varchar(255) DEFAULT NULL,
  city varchar(255) DEFAULT NULL,
  state varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE categories (
  id int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  parent_name varchar(100) DEFAULT NULL,
  parent_id varchar(10) NOT NULL DEFAULT '0',
  `status` int(1) DEFAULT '1',
  created datetime NOT NULL,
  modified datetime NOT NULL,
  PRIMARY KEY (id),
  KEY parent_id (parent_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE contacts (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  address text NOT NULL,
  zip varchar(255) NOT NULL,
  city varchar(255) NOT NULL,
  country varchar(255) NOT NULL,
  phone varchar(20) NOT NULL,
  email varchar(255) NOT NULL,
  message text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE countries (
  id int(10) NOT NULL DEFAULT '0',
  country varchar(255) DEFAULT NULL,
  country_code tinytext,
  stc int(1) DEFAULT NULL,
  xr int(1) DEFAULT NULL,
  created datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE faqs (
  id int(10) NOT NULL AUTO_INCREMENT,
  faq_category_id mediumint(10) NOT NULL,
  title varchar(200) NOT NULL,
  body text NOT NULL,
  created datetime NOT NULL,
  modified datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE faq_categories (
  id int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` int(1) DEFAULT '1',
  created datetime NOT NULL,
  modified datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE groups (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  created datetime NOT NULL,
  modified datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE images (
  id int(10) NOT NULL AUTO_INCREMENT,
  width int(11) DEFAULT NULL,
  height int(11) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE newsletter_groups (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE newsletter_groups_mails (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  newsletter_mail_id int(10) unsigned NOT NULL,
  newsletter_group_id int(10) unsigned NOT NULL,
  PRIMARY KEY (id),
  KEY fk (newsletter_mail_id,newsletter_group_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE newsletter_groups_subscriptions (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  newsletter_subscription_id int(10) unsigned NOT NULL,
  newsletter_group_id int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY Foreign_Keys (newsletter_subscription_id,newsletter_group_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE newsletter_mails (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(100) DEFAULT NULL,
  from_email varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  content text,
  read_confirmation_code varchar(100) DEFAULT NULL,
  last_sent_subscription_id int(10) unsigned NOT NULL DEFAULT '0',
  sent int(10) unsigned NOT NULL DEFAULT '0',
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE newsletter_mail_views (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  newsletter_mail_id int(10) unsigned DEFAULT NULL,
  ip varchar(100) DEFAULT NULL,
  created datetime DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk (newsletter_mail_id,ip)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE newsletter_subscriptions (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  email varchar(250) DEFAULT NULL,
  opt_out_date datetime DEFAULT NULL,
  confirmation_code varchar(250) DEFAULT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE orders (
  id char(36) NOT NULL,
  notify_version varchar(64) DEFAULT NULL COMMENT 'IPN Version Number',
  verify_sign varchar(127) DEFAULT NULL COMMENT 'Encrypted string used to verify the authenticityof the tansaction',
  test_ipn int(11) DEFAULT NULL,
  address_city varchar(40) DEFAULT NULL COMMENT 'City of customers address',
  address_country varchar(64) DEFAULT NULL COMMENT 'Country of customers address',
  address_country_code varchar(2) DEFAULT NULL COMMENT 'Two character ISO 3166 country code',
  address_name varchar(128) DEFAULT NULL COMMENT 'Name used with address (included when customer provides a Gift address)',
  address_state varchar(40) DEFAULT NULL COMMENT 'State of customer address',
  address_status varchar(20) DEFAULT NULL COMMENT 'confirmed/unconfirmed',
  address_street varchar(200) DEFAULT NULL COMMENT 'Customer''s street address',
  address_zip varchar(20) DEFAULT NULL COMMENT 'Zip code of customer''s address',
  first_name varchar(64) DEFAULT NULL COMMENT 'Customer''s first name',
  last_name varchar(64) DEFAULT NULL COMMENT 'Customer''s last name',
  payer_business_name varchar(127) DEFAULT NULL COMMENT 'Customer''s company name, if customer represents a business',
  payer_email varchar(127) DEFAULT NULL COMMENT 'Customer''s primary email address. Use this email to provide any credits',
  payer_id varchar(13) DEFAULT NULL COMMENT 'Unique customer ID.',
  payer_status varchar(20) DEFAULT NULL COMMENT 'verified/unverified',
  contact_phone varchar(20) DEFAULT NULL COMMENT 'Customer''s telephone number.',
  residence_country varchar(2) DEFAULT NULL COMMENT 'Two-Character ISO 3166 country code',
  business varchar(127) DEFAULT NULL COMMENT 'Email address or account ID of the payment recipient (that is, the merchant). Equivalent to the values of receiver_email (If payment is sent to primary account) and business set in the Website Payment HTML.',
  item_name varchar(127) DEFAULT NULL COMMENT 'Item name as passed by you, the merchant. Or, if not passed by you, as entered by your customer. If this is a shopping cart transaction, Paypal will append the number of the item (e.g., item_name_1,item_name_2, and so forth).',
  item_number varchar(127) DEFAULT NULL COMMENT 'Pass-through variable for you to track purchases. It will get passed back to you at the completion of the payment. If omitted, no variable will be passed back to you.',
  quantity varchar(127) DEFAULT NULL COMMENT 'Quantity as entered by your customer or as passed by you, the merchant. If this is a shopping cart transaction, PayPal appends the number of the item (e.g., quantity1,quantity2).',
  receiver_email varchar(127) DEFAULT NULL COMMENT 'Primary email address of the payment recipient (that is, the merchant). If the payment is sent to a non-primary email address on your PayPal account, the receiver_email is still your primary email.',
  receiver_id varchar(13) DEFAULT NULL COMMENT 'Unique account ID of the payment recipient (i.e., the merchant). This is the same as the recipients referral ID.',
  custom varchar(255) DEFAULT NULL COMMENT 'Custom value as passed by you, the merchant. These are pass-through variables that are never presented to your customer.',
  invoice varchar(127) DEFAULT NULL COMMENT 'Pass through variable you can use to identify your invoice number for this purchase. If omitted, no variable is passed back.',
  memo varchar(255) DEFAULT NULL COMMENT 'Memo as entered by your customer in PayPal Website Payments note field.',
  option_name1 varchar(64) DEFAULT NULL COMMENT 'Option name 1 as requested by you',
  option_name2 varchar(64) DEFAULT NULL COMMENT 'Option 2 name as requested by you',
  option_selection1 varchar(200) DEFAULT NULL COMMENT 'Option 1 choice as entered by your customer',
  option_selection2 varchar(200) DEFAULT NULL COMMENT 'Option 2 choice as entered by your customer',
  tax decimal(10,2) DEFAULT NULL COMMENT 'Amount of tax charged on payment',
  auth_id varchar(19) DEFAULT NULL COMMENT 'Authorization identification number',
  auth_exp varchar(28) DEFAULT NULL COMMENT 'Authorization expiration date and time, in the following format: HH:MM:SS DD Mmm YY, YYYY PST',
  auth_amount int(11) DEFAULT NULL COMMENT 'Authorization amount',
  auth_status varchar(20) DEFAULT NULL COMMENT 'Status of authorization',
  num_cart_items int(11) DEFAULT NULL COMMENT 'If this is a PayPal shopping cart transaction, number of items in the cart',
  parent_txn_id varchar(19) DEFAULT NULL COMMENT 'In the case of a refund, reversal, or cancelled reversal, this variable contains the txn_id of the original transaction, while txn_id contains a new ID for the new transaction.',
  payment_date varchar(28) DEFAULT NULL COMMENT 'Time/date stamp generated by PayPal, in the following format: HH:MM:SS DD Mmm YY, YYYY PST',
  payment_status varchar(20) DEFAULT NULL COMMENT 'Payment status of the payment',
  payment_type varchar(10) DEFAULT NULL COMMENT 'echeck/instant',
  pending_reason varchar(20) DEFAULT NULL COMMENT 'This variable is only set if payment_status=pending',
  reason_code varchar(20) DEFAULT NULL COMMENT 'This variable is only set if payment_status=reversed',
  remaining_settle int(11) DEFAULT NULL COMMENT 'Remaining amount that can be captured with Authorization and Capture',
  shipping_method varchar(64) DEFAULT NULL COMMENT 'The name of a shipping method from the shipping calculations section of the merchants account profile. The buyer selected the named shipping method for this transaction',
  shipping decimal(10,2) DEFAULT NULL COMMENT 'Shipping charges associated with this transaction. Format unsigned, no currency symbol, two decimal places',
  transaction_entity varchar(20) DEFAULT NULL COMMENT 'Authorization and capture transaction entity',
  txn_id varchar(19) DEFAULT '' COMMENT 'A unique transaction ID generated by PayPal',
  txn_type varchar(20) DEFAULT NULL COMMENT 'cart/express_checkout/send-money/virtual-terminal/web-accept',
  exchange_rate decimal(10,2) DEFAULT NULL COMMENT 'Exchange rate used if a currency conversion occured',
  mc_currency varchar(3) DEFAULT NULL COMMENT 'Three character country code. For payment IPN notifications, this is the currency of the payment, for non-payment subscription IPN notifications, this is the currency of the subscription.',
  mc_fee decimal(10,2) DEFAULT NULL COMMENT 'Transaction fee associated with the payment, mc_gross minus mc_fee equals the amount deposited into the receiver_email account. Equivalent to payment_fee for USD payments. If this amount is negative, it signifies a refund or reversal, and either ofthose p',
  mc_gross decimal(10,2) DEFAULT NULL COMMENT 'Full amount of the customer''s payment',
  mc_handling decimal(10,2) DEFAULT NULL COMMENT 'Total handling charge associated with the transaction',
  mc_shipping decimal(10,2) DEFAULT NULL COMMENT 'Total shipping amount associated with the transaction',
  payment_fee decimal(10,2) DEFAULT NULL COMMENT 'USD transaction fee associated with the payment',
  payment_gross decimal(10,2) DEFAULT NULL COMMENT 'Full USD amount of the customers payment transaction, before payment_fee is subtracted',
  settle_amount decimal(10,2) DEFAULT NULL COMMENT 'Amount that is deposited into the account''s primary balance after a currency conversion',
  settle_currency varchar(3) DEFAULT NULL COMMENT 'Currency of settle amount. Three digit currency code',
  auction_buyer_id varchar(64) DEFAULT NULL COMMENT 'The customer''s auction ID.',
  auction_closing_date varchar(28) DEFAULT NULL COMMENT 'The auction''s close date. In the format: HH:MM:SS DD Mmm YY, YYYY PSD',
  auction_multi_item int(11) DEFAULT NULL COMMENT 'The number of items purchased in multi-item auction payments',
  for_auction varchar(10) DEFAULT NULL COMMENT 'This is an auction payment - payments made using Pay for eBay Items or Smart Logos - as well as send money/money request payments with the type eBay items or Auction Goods(non-eBay)',
  subscr_date varchar(28) DEFAULT NULL COMMENT 'Start date or cancellation date depending on whether txn_type is subcr_signup or subscr_cancel',
  subscr_effective varchar(28) DEFAULT NULL COMMENT 'Date when a subscription modification becomes effective',
  period1 varchar(10) DEFAULT NULL COMMENT '(Optional) Trial subscription interval in days, weeks, months, years (example a 4 day interval is 4 D',
  period2 varchar(10) DEFAULT NULL COMMENT '(Optional) Trial period',
  period3 varchar(10) DEFAULT NULL COMMENT 'Regular subscription interval in days, weeks, months, years',
  amount1 decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for Trial period 1 for USD',
  amount2 decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for Trial period 2 for USD',
  amount3 decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for regular subscription  period 1 for USD',
  mc_amount1 decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for trial period 1 regardless of currency',
  mc_amount2 decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for trial period 2 regardless of currency',
  mc_amount3 decimal(10,2) DEFAULT NULL COMMENT 'Amount of payment for regular subscription period regardless of currency',
  recurring varchar(1) DEFAULT NULL COMMENT 'Indicates whether rate recurs (1 is yes, blank is no)',
  reattempt varchar(1) DEFAULT NULL COMMENT 'Indicates whether reattempts should occur on payment failure (1 is yes, blank is no)',
  retry_at varchar(28) DEFAULT NULL COMMENT 'Date PayPal will retry a failed subscription payment',
  recur_times int(11) DEFAULT NULL COMMENT 'The number of payment installations that will occur at the regular rate',
  username varchar(64) DEFAULT NULL COMMENT '(Optional) Username generated by PayPal and given to subscriber to access the subscription',
  `password` varchar(24) DEFAULT NULL COMMENT '(Optional) Password generated by PayPal and given to subscriber to access the subscription (Encrypted)',
  subscr_id varchar(19) DEFAULT NULL COMMENT 'ID generated by PayPal for the subscriber',
  case_id varchar(28) DEFAULT NULL COMMENT 'Case identification number',
  case_type varchar(28) DEFAULT NULL COMMENT 'complaint/chargeback',
  case_creation_date varchar(28) DEFAULT NULL COMMENT 'Date/Time the case was registered',
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE order_items (
  id varchar(36) NOT NULL,
  order_id varchar(36) NOT NULL,
  item_name varchar(127) DEFAULT NULL,
  item_number varchar(127) DEFAULT NULL,
  quantity varchar(127) DEFAULT NULL,
  mc_gross float(10,2) DEFAULT NULL,
  mc_shipping float(10,2) DEFAULT NULL,
  mc_handling float(10,2) DEFAULT NULL,
  tax float(10,2) DEFAULT NULL,
  created datetime NOT NULL,
  modified datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE posts (
  id int(10) NOT NULL AUTO_INCREMENT,
  title varchar(255) DEFAULT NULL,
  body text,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE presses (
  id int(10) NOT NULL AUTO_INCREMENT,
  title varchar(500) DEFAULT NULL,
  outlet varchar(500) DEFAULT NULL,
  link varchar(500) DEFAULT NULL,
  excerpt text,
  created datetime DEFAULT NULL,
  modifed datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE products (
  id int(10) NOT NULL AUTO_INCREMENT,
  category_id varchar(255) DEFAULT NULL,
  category_name varchar(255) DEFAULT NULL,
  product_code varchar(255) DEFAULT NULL,
  product_price decimal(7,2) DEFAULT NULL,
  product_cost decimal(7,2) DEFAULT NULL,
  product_suggested decimal(7,2) DEFAULT NULL,
  product_name varchar(255) DEFAULT NULL,
  new_name varchar(255) DEFAULT NULL,
  xr_cost decimal(7,2) DEFAULT NULL,
  product_desc text,
  product_list text,
  product_length varchar(255) DEFAULT NULL,
  product_width varchar(255) DEFAULT NULL,
  product_circum varchar(255) DEFAULT NULL,
  product_size varchar(255) DEFAULT NULL,
  product_color varchar(255) DEFAULT NULL,
  product_power varchar(255) DEFAULT NULL,
  product_vibe varchar(255) DEFAULT NULL,
  product_gender varchar(255) DEFAULT NULL,
  product_material varchar(255) DEFAULT NULL,
  product_shape varchar(255) DEFAULT NULL,
  product_features varchar(255) DEFAULT NULL,
  product_body varchar(255) DEFAULT NULL,
  pm_image varchar(255) DEFAULT NULL,
  product_image varchar(255) DEFAULT NULL,
  product_thumb varchar(255) DEFAULT NULL,
  product_image2 varchar(255) DEFAULT NULL,
  product_thumb2 varchar(255) DEFAULT NULL,
  product_flavor varchar(255) DEFAULT NULL,
  product_star varchar(255) DEFAULT NULL,
  product_vendor varchar(255) DEFAULT NULL,
  product_manf varchar(255) DEFAULT NULL,
  product_upc varchar(255) DEFAULT NULL,
  product_weight varchar(255) DEFAULT NULL,
  viewed int(20) DEFAULT NULL,
  out_of_stock varchar(255) DEFAULT '0',
  created datetime DEFAULT NULL,
  modified varchar(255) DEFAULT NULL,
  PRIMARY KEY (id),
  FULLTEXT KEY product_name (product_name,product_desc,product_list,product_code,product_star)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE promos (
  id int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) DEFAULT NULL,
  `type` varchar(11) DEFAULT NULL,
  amount decimal(8,2) DEFAULT NULL,
  min_purchase int(11) DEFAULT NULL,
  limit_max varchar(3) DEFAULT NULL,
  limit_user varchar(3) DEFAULT NULL,
  used int(5) DEFAULT NULL,
  date_start varchar(100) DEFAULT NULL,
  date_end varchar(100) DEFAULT NULL,
  created date DEFAULT NULL,
  modified date DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE ratings (
  id int(10) NOT NULL AUTO_INCREMENT,
  product_id varchar(10) NOT NULL,
  model varchar(255) DEFAULT NULL,
  rating varchar(255) DEFAULT NULL,
  `comment` text,
  `from` varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE settings (
  id int(10) NOT NULL AUTO_INCREMENT,
  category varchar(100) DEFAULT NULL,
  `key` varchar(100) NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE users (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  fname varchar(50) NOT NULL,
  lname varchar(50) NOT NULL,
  username varchar(50) DEFAULT NULL,
  email varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  group_id int(10) unsigned NOT NULL,
  active tinyint(1) unsigned NOT NULL,
  created datetime DEFAULT NULL,
  modified datetime DEFAULT NULL,
  PRIMARY KEY (id),
  KEY group_id (group_id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE weights (
  product_code varchar(255) DEFAULT NULL,
  weight varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
