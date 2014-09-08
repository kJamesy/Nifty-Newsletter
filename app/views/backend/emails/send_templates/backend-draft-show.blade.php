<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style>
			a{
				color:#e4701e;
				text-decoration:none;	
			}
			a:hover {
				color:#e4701e;	
				text-decoration:underline;
			}
		</style>
	</head>			
	<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">

		{{ $email_body }}

		<table width="690" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>			
				<td  style="font-family:Verdana, Geneva, sans-serif;font-size:9px;color:#595959;text-align:center;padding-top:20px;" >
					<a target="_blank" href="{{ URL::to('draft/backend-show/' . $email_id) }}">View this email in your browser</a>
					| 
					<a target="_blank" href="#" style="color:#595959;">Unsubscribe</a>
				</td>
			</tr>
		</table>							
	</body>
</html>	