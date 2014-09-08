<?php
use Hashids\Hashids;

class AnalyticController extends \BaseController {

	public function delivered()
	{	
		try {
			$inputs = [];
			foreach (Input::all() as $key => $value) {
				$inputs[strtolower($key)] = Input::get($key);
			}

			$apicall_id = explode('<', explode('@', $inputs['message-id'])[0])[1];

			if ( $inputs['x-mailgun-tag'] != 'Password Recovery' ) {
				$analytic = Analytic::whereRecipient($inputs['recipient'])->whereApicallId($apicall_id)->first();
				$analytic->status = $inputs['event'];
				$analytic->save();
			}

			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			Log::error($e);
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

			if ( $inputs['x-mailgun-tag'] != 'Password Recovery' ) {
				$analytic = Analytic::whereRecipient($inputs['recipient'])->whereApicallId($apicall_id)->first();
				$analytic->status = $inputs['event'];
				$analytic->reason = $inputs['reason'];
				$analytic->save();
			}

			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			Log::error($e);
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

			// $test = new Test;
			// $test->mailgun_input = json_encode(Input::all());
			// $test->save();

			$apicall_id = explode('@', $inputs['message-id'])[0];

			if ( $inputs['tag'] != 'Password Recovery' ) {
				$analytic = Analytic::whereRecipient($inputs['recipient'])->whereApicallId($apicall_id)->first();
				$analytic->status = $inputs['event'];
				$analytic->ip = $inputs['ip'];
				$analytic->country = $inputs['country'];
				$analytic->city = $inputs['city'];
				$analytic->client_name = $inputs['client-name'];
				$analytic->client_type = $inputs['client-type'];
				$analytic->client_os = $inputs['client-os'];
				$analytic->device_type = $inputs['device-type'];
				$analytic->save();
			}

			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			Log::error($e);
			return Response::json(['error'], 406);
		}		

	}

	public function clicked()
	{
		try {
			$inputs = [];
			foreach (Input::all() as $key => $value) {
				$inputs[strtolower($key)] = Input::get($key);
			}

			$apicall_id = explode('@', $inputs['message-id'])[0];

			if ( $inputs['tag'] != 'Password Recovery' ) {

				$analytic = Analytic::whereRecipient($inputs['recipient'])->whereApicallId($apicall_id)->first();

				$click = Click::whereEmailId($analytic->email_id)->whereSubscriberId($analytic->subscriber_id)->whereUrl($inputs['url'])->count();

				if ( ! $click ) {
					$click = new Click; 
					$click->email_id = $analytic->email_id;
					$click->subscriber_id = $analytic->subscriber_id;
					$click->url = $inputs['url'];
					$click->save();

					if ( $analytic->client_name == NULL ) {
						$analytic->ip = $inputs['ip'];
						$analytic->country = $inputs['country'];
						$analytic->city = $inputs['city'];
						$analytic->client_name = $inputs['client-name'];
						$analytic->client_type = $inputs['client-type'];
						$analytic->client_os = $inputs['client-os'];
						$analytic->device_type = $inputs['device-type'];
						$analytic->reason = "disabled images";
						$analytic->save();
					}

				}
			}
			
			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			Log::error($e);
			return Response::json(['error'], 406);
		}
	}

	public function bounced()
	{
		try {
			$inputs = [];
			foreach (Input::all() as $key => $value) {
				$inputs[strtolower($key)] = Input::get($key);
			}

			$apicall_id = explode('<', explode('@', $inputs['message-id'])[0])[1];

			if ( $inputs['tag'] != 'Password Recovery' ) {
				$analytic = Analytic::whereRecipient($inputs['recipient'])->whereApicallId($apicall_id)->first();
				$analytic->status = $inputs['event'];
				$analytic->save();
			}

			return Response::json(['success'], 200);
		}

		catch (Exception $e) {
			Log::error($e);
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