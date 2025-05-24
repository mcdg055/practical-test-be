<?php

namespace App\Http\Controllers;

use App\Http\Requests\IPAddress\PatchRequest;
use App\Http\Requests\IPAddress\PostRequest;
use App\Http\Resources\IPAddressResource;
use App\Http\Services\IPAddressService;
use App\Models\IPAddress;
use Illuminate\Http\Request;

class IPAddressController extends Controller
{
    public function __construct(private IPAddressService $service)
    {
        
    }
     public function browse(Request $request)
    {
       
    }

    public function read(Request $request, IPAddress $ip)
    {
        
    }

    public function edit(PatchRequest $request, IPAddress $ip)
    {
        
    }

    public function add(PostRequest $request)
    {
        return new IPAddressResource($this->service->add($request->all()));
    }

    public function delete(Request $request, IPAddress $ip)
    {
       
    }
}
