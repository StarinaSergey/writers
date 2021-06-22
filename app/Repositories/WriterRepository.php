<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Writer;
use Illuminate\Support\Facades\Hash;

class WriterRepository
{
    private Writer $client;

    public function __construct(Writer $client)
    {
        $this->client = $client;
    }

    public function getClientForEmailAndDomains(string $email, string $domain): ?Writer
    {
        $clientModel = $this->client::where('email', '=', strtolower($email))
            ->where('domain', '=', $domain)
            ->first();

        if(($clientModel instanceof Writer)){
            return $clientModel;
        }
        return null;
    }

    public function setPassword(Writer $client, string $password): void
    {
        $client->password = $password;
        $client->save();
    }

    public function getClientWithPreferredAndBlockedWriters(int $clientId): ?Writer
    {
        $clientModel =  $this->client::select(
            "id",
            "firstname",
            "lastname",
            "fullname",
            "email",
            "timezone",
            "phone_code",
            "phone",
            "phone_verified",
            "address",
            "city",
            "zip",
            "country",
            "region",
            "chk_email_marketing",
            "chk_email_notifications",
            "chk_sms_notifications",
            "chk_calls",
            "aff_paymethod",
            "aff_paypal_email"
        )
            ->with('preferred_writers')
            ->with('blocked_writers')
            ->with('statistics')
            ->where('id', $clientId)
            ->first();

        if(($clientModel instanceof Writer)){
            return $clientModel;
        }
        return null;
    }

    public function getClientForPhoneCodeAndDomain(string $phoneCode, string $phone, string $domain): ?Writer
    {
        $clientModel = $this->client::where([
            'phone_code' => $phoneCode,
            'phone' => $phone,
            'domain' => $domain
        ])->first();

        if(($clientModel instanceof Writer)){
            return $clientModel;
        }
        return null;
    }

    public function createClient(array $info)
    {
        return $this->client::create($info);
    }

    public function findForId(int $id): ?Writer
    {
        $clientModel = $this->client::find($id);

        if(($clientModel instanceof Writer)){
            return $clientModel;
        }
        return null;
    }

    public function updateClient(Writer $client, array $paramsArray): bool
    {
        foreach ($paramsArray as $name => $value) {
            $client->$name = $value;
        }
        return $client->save();
    }

    public function updateResetHash(string $email, string $domain, string $passwordHash, string $resetHash)
    {
        return Writer::where('email', $email)
            ->where('domain', '=', $domain)
            ->update([
                'password_hash' => $passwordHash,
                'reset_hash' => $resetHash
            ]);
    }
}
