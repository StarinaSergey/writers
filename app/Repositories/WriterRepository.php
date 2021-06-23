<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Writer;
use Illuminate\Support\Facades\Hash;

class WriterRepository
{
    private Writer $writer;

    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }

    public function getClientForEmail(string $email): ?Writer
    {
        $writerModel = $this->writer::where('email', '=', strtolower($email))
            ->first();

        if(($writerModel instanceof Writer)){
            return $writerModel;
        }

        return null;
    }

    public function getWriter(int $writerId): ?Writer
    {
        $writerModel = $this->writer::select(
            "field_id",
            "firstname",
            "lastname",
            "nickname",
            "email",
            "phone",
            "address",
            "city",
            "zip",
            "country"
        )
            ->with('statistics')
            ->where('field_id', $writerId)
            ->first();

        if(($writerModel instanceof Writer)){
            return $writerModel;
        }
        return null;
    }

    public function getClientForPhoneCodeAndDomain(string $phoneCode, string $phone, string $domain): ?Writer
    {
        $writerModel = $this->writer::where([
            'phone_code' => $phoneCode,
            'phone' => $phone,
            'domain' => $domain
        ])->first();

        if(($writerModel instanceof Writer)){
            return $writerModel;
        }
        return null;
    }

    public function createClient(array $info)
    {
        return $this->writer::create($info);
    }

    public function findForId(int $id): ?Writer
    {
        $writerModel = $this->writer::find($id);

        if(($writerModel instanceof Writer)){
            return $writerModel;
        }
        return null;
    }

    public function updateClient(Writer $writer, array $paramsArray): bool
    {
        foreach ($paramsArray as $name => $value) {
            $writer->$name = $value;
        }
        return $writer->save();
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
