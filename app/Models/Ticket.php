<?php
namespace CodeDay\Models;

class Ticket extends ClearModel {
    public static function find($ticketId)
    {
        return new self(self::clearGet('/registration/'.urlencode($ticketId)));
    }
} 
