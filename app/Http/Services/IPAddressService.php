<?php


namespace App\Http\Services;

use App\Models\IPAddress;
use Illuminate\Support\Arr;

class IPAddressService
{
    public function add(array $data)
    {
        $ip = new IPAddress($data);
        $ip->type = $this->identifyIpType(Arr::get($data, 'ip'));
        $ip = $this->fillRelations($ip);

        $ip->save();

        return $ip;
    }

    public function fillRelations(IPAddress $ip)
    {
        $ip->user()->associate(auth()->user()->getKey());

        return $ip;
    }

    public function identifyIpType(string $ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return IPAddress::TYPE_IPV4;
        }

        return IPAddress::TYPE_IPV6;;
    }
}
