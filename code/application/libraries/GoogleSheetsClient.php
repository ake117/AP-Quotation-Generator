<?php

/**
 * Reads the Google Sheet straight from the API, so the media plan never has to be
 * downloaded and uploaded again.
 *
 * Authentication is a service account: the JSON key the team generates in Google Cloud,
 * and the sheet shared with that account's e-mail. No composer dependency — the token is
 * a signed JWT (openssl) exchanged for an access token over curl, which is all this needs.
 *
 * Nothing here knows about quotations; SheetTable does the mapping and the controller
 * decides what to do with the rows.
 */
final class GoogleSheetsClient
{
    const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    const SCOPE = 'https://www.googleapis.com/auth/spreadsheets';

    /** @var string */
    private $keyFile;

    /** @var string|null */
    private $accessToken = null;

    /** @var int */
    private $expiresAt = 0;

    public function __construct(string $serviceAccountJson)
    {
        if (!is_file($serviceAccountJson)) {
            throw new RuntimeException('service account json not found: ' . $serviceAccountJson);
        }

        $this->keyFile = $serviceAccountJson;
    }

    /** Base64 for a JWT segment: no padding, url-safe alphabet. */
    public static function base64Url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /** The claims of the assertion we sign. Times are unix seconds. */
    public static function claims(array $account, int $now, int $lifetime = 3600): array
    {
        return array(
            'iss'   => isset($account['client_email']) ? $account['client_email'] : '',
            'scope' => self::SCOPE,
            'aud'   => self::TOKEN_URL,
            'iat'   => $now,
            'exp'   => $now + $lifetime,
        );
    }

    /** The signed JWT the token endpoint accepts. */
    public function assertion(int $now): string
    {
        $account = $this->account();
        $header = self::base64Url(json_encode(array('alg' => 'RS256', 'typ' => 'JWT')));
        $payload = self::base64Url(json_encode(self::claims($account, $now)));
        $unsigned = $header . '.' . $payload;

        $signature = '';
        $key = openssl_pkey_get_private($account['private_key']);
        if ($key === false || !openssl_sign($unsigned, $signature, $key, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('could not sign the assertion with the service account key');
        }

        return $unsigned . '.' . self::base64Url($signature);
    }

    /** A bearer token, reused until shortly before it expires. */
    public function token(): string
    {
        if ($this->accessToken !== null && time() < $this->expiresAt - 60) {
            return $this->accessToken;
        }

        $response = $this->request('POST', self::TOKEN_URL, array(
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $this->assertion(time()),
        ), false);

        if (empty($response['access_token'])) {
            throw new RuntimeException('google refused the token request: ' . json_encode($response));
        }

        $this->accessToken = $response['access_token'];
        $this->expiresAt = time() + (int) (isset($response['expires_in']) ? $response['expires_in'] : 3600);

        return $this->accessToken;
    }

    /**
     * Cell values of a range, as a matrix of strings (ragged rows kept as they come).
     *
     * @return array<int, array<int, string>>
     */
    public function getValues(string $spreadsheetId, string $range): array
    {
        $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode($spreadsheetId)
            . '/values/' . rawurlencode($range)
            . '?majorDimension=ROWS&valueRenderOption=UNFORMATTED_VALUE';

        $response = $this->request('GET', $url);

        return isset($response['values']) ? $response['values'] : array();
    }

    /**
     * Write a block of values, starting at the top-left of the range.
     *
     * @param array<int, array<int, mixed>> $values
     */
    public function updateValues(string $spreadsheetId, string $range, array $values): array
    {
        $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode($spreadsheetId)
            . '/values/' . rawurlencode($range)
            . '?valueInputOption=USER_ENTERED';

        return $this->request('PUT', $url, array(
            'range'          => $range,
            'majorDimension' => 'ROWS',
            'values'         => $values,
        ));
    }

    /**
     * Insert blank rows, shifting what is below down — the same move the local writer makes
     * on the uploaded workbook.
     */
    public function insertRows(string $spreadsheetId, int $sheetId, int $startIndex, int $howMany): array
    {
        $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode($spreadsheetId) . ':batchUpdate';

        return $this->request('POST', $url, array(
            'requests' => array(array(
                'insertDimension' => array(
                    'range' => array(
                        'sheetId'    => $sheetId,
                        'dimension'  => 'ROWS',
                        'startIndex' => $startIndex,
                        'endIndex'   => $startIndex + $howMany,
                    ),
                    'inheritFromBefore' => true,
                ),
            )),
        ));
    }

    /** The sheet id (not its name) of a tab, which the batch calls need. */
    public function sheetId(string $spreadsheetId, string $title): int
    {
        $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode($spreadsheetId)
            . '?fields=sheets.properties';

        $response = $this->request('GET', $url);
        foreach ((isset($response['sheets']) ? $response['sheets'] : array()) as $sheet) {
            $properties = isset($sheet['properties']) ? $sheet['properties'] : array();
            if (isset($properties['title']) && $properties['title'] === $title) {
                return (int) $properties['sheetId'];
            }
        }

        throw new RuntimeException('no tab named ' . $title . ' in the spreadsheet');
    }

    private function account(): array
    {
        $account = json_decode((string) file_get_contents($this->keyFile), true);
        if (!is_array($account) || empty($account['client_email']) || empty($account['private_key'])) {
            throw new RuntimeException('the service account json has no client_email/private_key');
        }

        return $account;
    }

    /** @return array<string, mixed> */
    private function request(string $method, string $url, ?array $body = null, bool $authorised = true)
    {
        $headers = array('Content-Type: application/json');
        if ($authorised) {
            $headers[] = 'Authorization: Bearer ' . ($this->accessToken !== null ? $this->accessToken : $this->token());
        }

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        if ($body !== null) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $raw = curl_exec($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $error = curl_error($handle);
        curl_close($handle);

        if ($raw === false) {
            throw new RuntimeException('google sheets request failed: ' . $error);
        }

        $decoded = json_decode($raw, true);

        if ($status >= 400) {
            throw new RuntimeException('google sheets said ' . $status . ': ' . $raw);
        }

        return is_array($decoded) ? $decoded : array();
    }
}
