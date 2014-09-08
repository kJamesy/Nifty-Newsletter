<?php
use Hashids\Hashids;

class TrackingController extends \BaseController {

	public function delivered()
	{
		try {
			$inputs = [];
			foreach (Input::all() as $key => $value) {
				$inputs[strtolower($key)] = Input::get($key);
			}

			$apicall_id = explode('<', explode('@', $inputs['message-id'])[0])[1];

			$analytic = EmailAnalytic::where('to', $inputs['recipient'])->where('apicall_id', $apicall_id)->first();
			$analytic->status = $inputs['event'];
			$analytic->save();

			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			return Response::json(['error'], 406);
		}
	}

	public function failed()
	{
		try {
			$inputs = [];
			foreach (Input::all() as $key => $value) {
				$inputs[strtolower($key)] = Input::get($key);
			}

			$apicall_id = explode('<', explode('@', $inputs['message-id'])[0])[1];

			$analytic = EmailAnalytic::where('to', $inputs['recipient'])->where('apicall_id', $apicall_id)->first();
			$analytic->status = $inputs['event'];
			$analytic->reason = $inputs['reason'];
			$analytic->save();

			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			return Response::json(['error'], 406);
		}
	}

	public function opened()
	{
		try {
			$inputs = [];
			foreach (Input::all() as $key => $value) {
				$inputs[strtolower($key)] = Input::get($key);
			}

			$apicall_id = explode('@', $inputs['message-id'])[0];

			$analytic = EmailAnalytic::where('to', $inputs['recipient'])->where('apicall_id', $apicall_id)->first();
			$analytic->status = $inputs['event'];
			$analytic->ip = $inputs['ip'];
			$analytic->country = $inputs['country'];
			$analytic->city = $inputs['city'];
			$analytic->client_name = $inputs['client-name'];
			$analytic->client_type = $inputs['client-type'];
			$analytic->client_os = $inputs['client-os'];
			$analytic->device_type = $inputs['device-type'];
			$analytic->save();
			
			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			return Response::json(['error'], 406);
		}		

	}

	public function clicked()
	{
		// $feedback = Input::all();
		// $email = new Email;
		// $email->from = Input::get('recipient');
		// $email->reply_to = 'Bounced'; //explode('<', explode('@', Input::get('Message-Id'))[0])[1];
		// $email->subject = Input::get('event');
		// $email->email_body = json_encode($feedback);
		// $email->save();
	}

	public function bounced()
	{
		try {
			$inputs = [];
			foreach (Input::all() as $key => $value) {
				$inputs[strtolower($key)] = Input::get($key);
			}

			$apicall_id = explode('<', explode('@', $inputs['message-id'])[0])[1];

			$analytic = EmailAnalytic::where('to', $inputs['recipient'])->where('apicall_id', $apicall_id)->first();
			$analytic->status = $inputs['event'];
			$analytic->save();

			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			return Response::json(['error'], 406);
		}		
	}

	public function complained()
	{

	}

	public function test()
	{
		$hashids = new Hashids(Config::get('app.key'), 16, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
		var_dump( $hashids->encrypt(5) );
		var_dump( $hashids->encrypt(6) );
	}

	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}