<?php

namespace Web\Http\Controllers\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BankMail extends Mailable
{
	use Queueable, SerializesModels;
	private $filename;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$subject = "Invoice From TripleSMS";
		$title = '';
		return $this->view('blank')
			->from('info@triplesms.com', $title)
			->subject($subject)
			->to('amy@lfuturedev.com')
			->attach($this->filename);
	}
}