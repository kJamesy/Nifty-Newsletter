<?php

class Analytic extends \Eloquent {

	public function subscriber()
	{
		return $this->belongsTo('Subscriber');
	}	

	public function email()
	{
		return $this->belongsTo('Email');
	}	
}