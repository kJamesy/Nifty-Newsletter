<?php
class SentEmailCustomController extends BaseController {

    public function __construct()
    {
        $this->user = Sentry::getUser();
        $this->isAdmin = User::isAdmin( $this->user );
        $this->logged_in_for = $this->user->last_login->diffForHumans();
        $this->configs = Setting::getSiteSettings();               
    }

	public function show($hashId)
	{
		$id = \Jamesy\Miscellaneous::decryptId($hashId);

		if ( $email = Email::find($id) );
			return View::make('backend.emails.send_templates.main', ['email_body' => $email->email_body]);
	}

	public function share($id)
	{
		$hashId = \Jamesy\Miscellaneous::encryptId($id);
		$link = URL::to('email/show/' . $hashId);
		return Response::json(['link' => $link], 200);
	}

	public function pdf($id)
	{
		$hashId = \Jamesy\Miscellaneous::encryptId($id);

		$pdf = new SimpleHTMLToPDF;
		$link = URL::to('email/show/' . $hashId);
		
		$pdf->display($link);			   
	}

	public function test()
	{
		$pdf = file_get_contents('http://api.simplehtmltopdf.com/?link=http://www.google.com');

        header("Content-Type: application/pdf");
        header("Cache-Control: no-cache");
        header("Accept-Ranges: none");
        header("Content-Disposition: attachment; filename=\"your_pdf_name.pdf\"");
		var_dump( $pdf );		
	}

}
