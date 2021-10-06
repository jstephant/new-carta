<?php

namespace App\Services\Google;

interface IGoogle
{
    public function getSheetById($service, $id);
    public function renameSheet($service, $spreadsheetId);
    public function copyTo($service, $spreadsheetId, $destinationsheetId);
    public function clearValues($service, $spreadsheetId, $range);
}