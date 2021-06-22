<?php
use usni\UsniAdaptor;
?>
<address>
    <b>Saņēmējs:</b><br/>
    <b><?= $companyData['name']; ?></b><br/>
    <b>Juridiskā adrese</b>: <?= $companyData['address']; ?><br/>
    <b>Reģistrācijas Nr.</b>: <?= $companyData['registration_number']; ?><br/>
    <b>PVN maksātāja Nr.</b>: <?= $companyData['vat_number']; ?><br/>
    <b>Banka</b>: <?= $companyData['bank_name']; ?><br/>
    <b>Bankas SWIFT kods</b>: <?= $companyData['bank_swift_code']; ?><br/>
    <b>Konta Nr.</b>: <?= $companyData['bank_iban']; ?><br/>
</address>
<?php echo UsniAdaptor::t('users', 'Mobile') . ': ' . $companyData['phone'] ?> <br/>
<?php echo UsniAdaptor::t('users', 'Email') . ': ' . $companyData['email'] ?> <br/>
