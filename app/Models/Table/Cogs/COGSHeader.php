<?php

namespace App\Models\Table\Cogs;

use Illuminate\Database\Eloquent\Model;

class COGSHeader extends Model
{

  protected $connection = 'mysql10';
  protected $table = 'cogs_header';

  protected $fillable = [
      'ID', 
      'ProductCategory', 
      'ProductCategory', 
      'PCRID', 
      'CPOID',
      'CalculationType', 
      'Opportunity', 
      'PNReference', 
      'PNReferenceDesc', 
      'PICTriatra',
      'CostEstimator', 
      'MarketingDeptHead', 
      'SCMDivisionHead', 
      'CalculatedBy', 
      'GrossProfit', 
      'QuotationPrice', 
      'PDF',
      'TotalRawMaterial', 
      'TotalSFComponent', 
      'TotalConsumables', 
      'TotalProcess', 
      'TotalOthers',
      'PEDNumber',
      'UnitWeight',
      'CreatedBy', 
      'UpdatedBy',
    ];
}
