@extends('layouts.app-master')

@section('terms', 'active')

@section('description', "Terms of Service used by TripleSMS")

@section('title', "Terms & Conditions | TripleSMS")

@section('style')
<style>
	.container {
		font-family: Arial, Helvetica, sans-serif;
		line-height: 1.5em;
	}

	h1 {
		margin-top: 4rem;
	}

	h4 {
		margin-top: 2rem;
	}

	ul, ol {
		padding-left: 50px;
	}

	li {
		margin-bottom: 0.5rem;
	}
</style>
@endsection

@section('content')
<div class="container">
	<h1 class="text-center">Terms and Conditions</h1>

	{!! $term->text !!}

	<!-- <h4>Acknowledgement/Consent</h4>

	<ul>
		<li>
			By accessing, viewing, logging-in to, creating an account or using the Website, Software, Services, the content provided thereon or their functionality, or by requesting and registering for the Services, you acknowledge and agree to be bound by these Terms.
		</li>

		<li>
			You understand as well that by creating an account on the Website, you give the consent on behalf of the Client and confirm acknowledgment of the terms of processing of personal data as stipulated in the Privacy Policy.
		</li>

		<li>
			You also acknowledge that under applicable law, some personal data can be processed without your consent and that TripleSMS reserves the right to undertake such processing when appropriate.
		</li>

		<li>
			You confirm and guarantee that at all times while using the Website, Software and Services you have all the necessary consents and authorizations for processing of all personal data that you submit to TripleSMS. You oblige to inform TripleSMS immediately of the withdrawal of the consent for processing of personal data submitted to TripleSMS, of the expiry of legal grounds for processing, modification, inaccuracy or change to the submitted personal data.
		</li>

		<li>
			When using Services for direct marketing, you are responsible for complying with all the legal requirements in connection with direct marketing and data subjects’ rights. TripleSMS is only providing the platform for sending messages, but you are solely responsible for the content of your messages sent using the Services. You understand that there are different legal rules for direct marketing in different countries. When you use Services for direct marketing, you must comply with all requirements for direct marketing of the country, where the receiver of the direct marketing message is residing. For instance, in EU countries, you are obliged to send with direct marketing a message with the information on how the data subject can waive from direct marketing and there are also certain requirements for the content of commercial messages.
		</li>

		<li>
			If you do not agree with these Terms, you may not use the Website, Software or the Services and must discontinue use immediately.
		</li>

		<li>
			Your continued access to the Website, Software and/or use of the Services, as described above, signifies your acceptance of the Terms.
		</li>
	</ul>

	<h4>Refund Policy</h4>

	<div>
		Before starting any business with us we advise you to test our services.as we are offering a free account for testing.We do not offer any refund of money at any stage or at any condition once the transaction has done unless TripleSMS.com agreed.
	</div>

	<h4>Use of Information Submitted</h4>

	<ul>
		<li>
			Email ID provided by the user at the time of Signup should be true to his/her knowledge and the provided Email ID should be in working condition.
		</li>

		<li>
			However, all the details provided by the user at the time of sign up are hereby kept confidential with the Team TripleSMS.com for official use only.
		</li>

		<li>
			Purpose of the Email ID is to inform the user in case of any technical failure, down time of the service, change in service, modification of working policy, and to maintain a two way channel between the customers and the company for any kind of official communications.
		</li>

		<li>
			In case of any changes in personal information, users can modify the details by logging in using their existing Email ID/Phone Number and password.
		</li>

		<li>
			Go to the profile page in your account and modify what so ever you wish to.
		</li>

		<li>
			Our services are present to make lives of people easy. Users choose TripleSMS.com by their own will without any force or pressure imposed by the Team TripleSMS.com.
		</li>
	</ul>

	<h4>Terms of Usage</h4>

	<div>
		This is a legally valid agreement that lays out the terms and conditions for the use of all services which comes under https://TripleSMS.com/ service agreed by you. The usage of the service is limited under all the terms & conditions herewith published. This agreement shall be governed by the rules and regulations of the authorities of the government of Myanmar. Please read this agreement carefully. All notices under this agreement will be considered as written and have been duly signed once the same is electronically confirmed.
	</div>

	<h4>Modification of Terms</h4>

	<div>
		We frequently update, modify and otherwise continually seek to improve the service and such changes often dictate that we simultaneously modify the terms and conditions of use. As such, we have the right to modify the terms of this agreement and to change or discontinue any aspect or feature of our service, in either case, as it deems reasonably necessary. If you do not agree with any such changes, your use of the service may be cancelled in accordance with the procedures for cancellation set forth in this agreement. You acknowledge your responsibility to review this agreement from time to time and to be aware of any such changes and, should you request, we will be happy to keep you informed if/when such changes take place. Furthermore, you accept that should there be a contradiction between other specific terms and conditions and these general terms and conditions, the other specific terms and conditions shall apply.
	</div>

	<h4>Service Usage Policy</h4>

	<div>
		You agree to abide by all applicable local, national and international laws and regulations. You are solely responsible for all acts or omissions that occur under your account, including the content of the messages transmitted through the service. The SMS service that sends/receives messages to/from mobile phones is maintained by Team TripleSMS. The utilization of the SMS service is subject to the following Terms of Service.
	</div>
	<br/>

	<ol>
		<li>
			Any illegal use of the SMS service is strictly prohibited.
		</li>

		<li>
			Messages containing sexual, racist or discriminatory content or any such usage of them may be considered as harassment and you are to be held responsible for this. Team TripleSMS does not assume any liability for the content of the messages sent.
		</li>

		<li>
			Team TripleSMS will be exempt from any claim that may arise from third parties as a result of the message content.
		</li>

		<li>
			You guarantee that the content of any SMS always respects and does not in any way conflict with fundamental human rights or will follow the norms of Intellectual Property Right laws. (e.g. right to privacy, prohibition of discrimination on any ground such as sex, race, color, language, religion, political or other opinion, national or social origin.)
		</li>

		<li>
			You accept that the service is provided for professional use only and you agree not to use it to :

			<ul>
				<li>
					Promote alcohol, music, dancing, dating, gambling or deception.
				</li>

				<li>
					Send unsolicited messages (i.e. mobile spam) and to therefore ensure that your messages are only sent to individuals that have given you their permission.
				</li>

				<li>
					Harvest, or otherwise collect information about others, without their consent.
				</li>

				<li>
					Mislead others by creating a false identity, impersonating the identity of someone/something else or by providing contact details that do not belong to you.
				</li>

				<li>
					Transmit, associate or publish any unlawful, racist, harassing, libelous, abusive, threatening, demeaning, lewd, immoral, harmful, vulgar, obscene or otherwise objectionable material of any kind. As a general guideline, if your content is not suitable for ages 13+, it most likely goes against our Usage Policy.
				</li>

				<li>
					Transmit any material that may infringe upon the intellectual property rights of third parties including trademarks, copyrights or other rights of publicity.
				</li>

				<li>
					Transmit any material that contains viruses, Trojan horses, worms, time bombs, cancel-bots or any other harmful/deleterious programs.
				</li>

				<li>
					Interfere with, or disrupt, networks connected to the service or violate the regulations, policies or procedures of such networks.
				</li>

				<li>
					Attempt to gain unauthorized access to the service, other accounts, computer systems or networks connected to the service, through password mining or any other means.
				</li>

				<li>
					Interfere with others use and enjoyment of the service.
				</li>

				<li>
					Engage in any other activity that Team TripleSMS believes could subject it to criminal liability or civil penalty/judgment.
				</li>
			</ul>
		</li>

		<li>
			You acknowledge that Team TripleSMS delivers SMS’s via major telecom operators and can therefore only influence the delivery transmission of SMS within the technical constraints imposed. SMS’s submitted via Internet will be transferred to mobile network providers, provided that the recipient’s phone is switched on and doesn’t have a full memory and is located in an area covered by its subscribed mobile network provider. You acknowledge that, depending on the recipient’s mobile provider service, it may not be possible to transmit the SMS to the recipient successfully.
		</li>

		<li>
			You are responsible for the privacy and storage of the user-name and password. You agree to be legally bound by all the activities carried out through your account.
		</li>

		<li>
			Team TripleSMS neither claims nor guarantees the availability or performance of this service and accepts no liability for transmission delays or message failures. While Team TripleSMS makes every effort to deliver messages promptly.
		</li>

		<li>
			Team TripleSMS doesn’t refund the credits for undeliverable messages to you because we cannot guarantee delivery of the SMS’s to recipients due to possible errors. Team TripleSMS debits transmitted SMS’s according to its transmission logs. These logs are deemed correct and valid even if the customer has objected to the correctness of the accounting records, except if investigation by Team TripleSMS has produced evidence of a technical problem or error.
		</li>

		<li>
			Team TripleSMS reserves the right to exclude you from using this service, refunding you any remaining amount in your account. All purchases must be considered as final, in compliance with our no refund policy.
		</li>

		<li>
			The SMS account balance is non-refundable and does not bear any interest. All the SMS’s must be used in the validity period from the date of the purchase. No carry forward of the credits will be made unless otherwise defined in a separate contract.
		</li>

		<li>
			You undertake the entire responsibility regarding the messages sent through https://TripleSMS.com/ services from your account, which are transmitted as per your request.
		</li>

		<li>
			You shall indemnify and reimburse Team TripleSMS all liabilities, costs, losses and damages, in case of any claim brought against Team TripleSMS from any third party due to breach of contract.
		</li>

		<li>
			You shall in no way raise questions concerning our price policy since it is liable to change with due amendments in the Government, TRAI or Telecom Operators rules and regulations.
		</li>
	</ol>

	<h4>Limitation of Liability/Disclaimer</h4>

	<ul>
		<li>
			Whilst we will take all reasonable steps to deliver your messages to the recipients as fast as possible, we cannot commit to, or guarantee, a specific delivery time. Such times depend on various network and system-related factors among the various entities involved in the transportation of your messages across the cellular mobile networks.
		</li>

		<li>
			Furthermore, delivery reports and the originator are operator dependant features and so it is not possible for us to give 100% guarantee for their availability. We recommend that tests should be conducted beforehand when these features are considered to be of high importance.
		</li>

		<li>
			You acknowledge that as we send and receive text-messages via major telecommunication companies and Mobile Network Operators, our influence over the transmission of your messages is within the technical constraints imposed upon us.
		</li>

		<li>
			Our responsibility is therefore to ensure that the text-messages you send through the service are processed correctly and delivered to the assigned entity. We are not responsible for the final delivery of the message, as this is out of our control and is the responsibility of the Mobile Network Operator.
		</li>

		<li>
			SMS’s that cannot be delivered within the lifespan allocated to them, either by us or a Mobile Network Operator will be discarded by the Mobile Network Operator, without any notice. We are not liable for any loss incurred by the failure of a message to be delivered and you acknowledge that damages concerning financial or other loss resulting from delivery failure cannot be claimed from Team TripleSMS. Furthermore, you agree that the message contents are deemed to have zero value.
		</li>

		<li>
			You also acknowledge that text-messages are transmitted unencrypted and that eavesdropping of mobile phone communications, including SMS delivery by third parties is possible. We also recommend that you ensure sensitive and valuable information is communicated by a number of superior communication methods.
		</li>

		<li>
			Furthermore, use of the service and the Internet is at its own risk and that the service is provided “as is” and “as available”, without any warranties or conditions whatsoever, expressed or implied. We will apply all commercially reasonable efforts to make access to the service available through the required access protocols, but make no warranty or guarantee that you will be able to access the service at any particular time or any particular location. Without limiting the generality of the terms set forth, we and our affiliates, agents, content providers, service providers, and licensees hereby disclaim all express and implied warranties as to the accuracy, completeness, non-infringement, merchantability or fitness for particular purpose of the service generally, and any content or services contained therein, as well as all express and implied warranties that the operation of the service generally and any contender services contained therein will be uninterrupted or error-free.
		</li>

		<li>
			We shall in no event be liable for any inaccuracy, error or omission in, or loss, injury or damage caused in whole or in part by failures, delays or interruptions of the service generally, and any aspect ancillary thereto; you agree to indemnify us for any third party claims arising from such failures, delays or interruptions in connection with regard to the use of the service.
		</li>

		<li>
			Notwithstanding anything to the contrary contained herein, the provisions outlined above are for the benefit of Team TripleSMS and its affiliates, agents, content providers and service providers and each shall have the right to assert and enforce such provisions directly on its own behalf.
		</li>
	</ul> -->

	<br/>
</div>
@endsection