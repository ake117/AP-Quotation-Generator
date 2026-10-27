<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reading the quotation data straight out of the shared Google Sheet.
 *
 * The sheet is private, so a service account does the reading: create one in Google Cloud,
 * download its JSON key next to this file (or point APQT_GOOGLE_SA at it), and share the
 * spreadsheet with the account's e-mail as an Editor — Editor is what lets the numbers be
 * written back into the sheet.
 *
 * The JSON key is a credential: it stays out of git (.gitignore) and out of the repo.
 */
$config['google_spreadsheet_id'] = '1MJeEx6Xv5UZCOAC1g1mGcuhe8y8raB2L6WTARUwVf1U';

$config['google_service_account_json'] = getenv('APQT_GOOGLE_SA')
    ?: APPPATH . 'config/google-service-account.json';

/* Write the QT numbers back into the sheet after importing. Turn off to only read. */
$config['google_write_back'] = true;
