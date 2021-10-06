<?php

namespace App\Services\Account;

interface IAccount 
{
    public function get($start_date, $end_date, $dob_from='', $dob_to='', $keyword='', $start, $length, $order);
    public function findById($id);
    public function update($id, $input);

    // contact
    public function createContact($input);
    public function updateContact($id, $input);

    // contact phone
    public function createContactPhone($input);
    public function updateContactPhone($id, $input);

    //contact email
    public function createContactEmail($input);
    public function updateContactEmail($id, $input);

    // sales
    public function getListSales($id, $start_date, $end_date, $start, $length, $order);
}