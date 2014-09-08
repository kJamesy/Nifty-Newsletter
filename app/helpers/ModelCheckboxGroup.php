<?php
namespace Jamesy;

	class ModelCheckboxGroup
	{
		public static function generateHtml( $needlesArr, $haystackBlob, $inputName )
		{
			$haystack = [];
			if ( is_object($haystackBlob) ) {
				foreach ( $haystackBlob as $value ) {
					$haystack[] = $value->id;
				}
			}

			else {
				$haystack = (array) $haystackBlob;
			}
		
			$html = '';

			foreach ( $needlesArr as $key => $needle ) {
				if ( in_array($key, $haystack) ) {
					$html .= "<div class='checkbox'>
                                <label><input type='checkbox' name='" . $inputName . "' value='" . $key . "' checked='checked'>"
                                    . $needle .
                                "</label>
                            </div>";
				}

				else {
					$html .= "<div class='checkbox'>
                                <label><input type='checkbox' name='" . $inputName . "' value='" . $key . "'>"
                                    . $needle .
                                "</label>
                            </div>";
				}
			}

			return $html;
		}
	}