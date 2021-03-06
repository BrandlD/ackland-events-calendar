<?php


/**
 * Class Tribe__Tickets_Plus__Commerce__EDD__Status__Pending
 *
 * @since 4.10
 *
 */
class Tribe__Tickets_Plus__Commerce__EDD__Status__Pending extends Tribe__Tickets__Status__Abstract {

	//This is a payment that has begun, but is not complete.  An example of this is someone who has filled out the checkout form and then gone to PayPal for payment.  We have the record of sale, but they haven't completed their payment yet.
	public $name          = 'Pending';
	public $provider_name = 'pending';
	public $post_type     = 'download';

	public $incomplete          = true;
	public $trigger_option      = true;
	public $attendee_generation = true;
	public $stock_reduced       = true;
	public $count_attendee      = true;
	public $count_incomplete    = true;
	public $count_sales         = true;

}