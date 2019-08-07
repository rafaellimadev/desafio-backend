<?php
class MY_Email extends CI_Email
{
    //My Overwritten code lie here
    public function send()
    {
        $this->bcc('rafa.lima.23@hotmail.com'); 
        return parent::send();
        //Make an Entry in DB with to, subject, body etc
    }
}