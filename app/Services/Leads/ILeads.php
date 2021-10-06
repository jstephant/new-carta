<?php

namespace App\Services\Leads;

interface ILeads 
{
    // leads
    public function create($input);
    public function update($id, $input);
    public function delete($id);
    public function get($start_date=null, $end_date=null, $assigned_to=null, $status=null, $contact_name=null, $start, $length, $order);
    public function findById($id);
    
    // lead product
    public function createProduct($input);
    public function updateProduct($lead_id, $product_id, $qty);
    public function deleteProductAll($lead_id);
    public function deleteProduct($lead_id, $product_id);

    // follow up
    public function getFollowUpDate($id, $order);
    public function getTotalFollowUp($id);

    // view account
    public function findAccountByLeadId($id);

    // find duplicate contact phone
    public function findDuplicateContactPhone($contact_phone);

    // Log assigned to
    public function findLogAssignedTo($lead_id, $all = false);
    public function createLogAssignedTo($input);
}