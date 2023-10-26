<?php

namespace App\Models\Table\PoTracking;

use Illuminate\Database\Eloquent\Model;

class UserVendor extends Model
{

    protected $connection = 'mysql6';
    protected $table = 'uservendors';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'ID', 'Name', 'Email', 'CountryCode', 'PostalCode', 'Address', 'VendorCode', 'VendorCode_new', 'PhoneNo', 'VendorType', 'EmailPO', 'Province', 'City', 'StatusPenanamanModal', 'JenisUsaha', 'NoNib', 'FileNib', 'NoNpwp', 'FileNpwp', 'NameBank', 'NoRekening', 'AtasNamaRekening', 'FileRekening', 'FileAktaPendirian', 'FileAnggaranDasar', 'FileIjinUsaha', 'FileSkdp', 'FileKtpDireksi', 'FileSkt', 'Skpkp', 'FileSkpkp', 'SuratAgen', 'FileSuratAgen', 'FilePernyataanRekening', 'FilePernyataanPajak', 'FileEtikaBertransaksi', 'FileAhu', 'created_at', 'CreatedBy', 'updated_at', 'LastModifiedBy'
    ];
}
