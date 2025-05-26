<?php


namespace App\Http\Services;

use App\Models\IPAddress;
use Illuminate\Support\Arr;

class IPAddressService
{
    public function browse(array $data)
    {
        $page = Arr::get($data, 'page', 1);
        $perPage = Arr::get($data, 'perPage', 10);
        $search = Arr::get($data, 'search', '');

        return IPAddress::with('user')
            ->where('ip', 'like', '%' . $search . '%')
            ->orWhere('label', 'like', '%' . $search . '%')
            ->orWhere('comment', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function add(array $data)
    {
        $ip = new IPAddress($data);
        $ip->type = $this->identifyIpType(Arr::get($data, 'ip'));
        $ip = $this->fillRelations($ip);

        $ip->save();

        return $ip->load('user');
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
