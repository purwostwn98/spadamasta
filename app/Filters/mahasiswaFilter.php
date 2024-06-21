<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class mahasiswaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Kondisi sebelum login

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kondisi after login
        if (session()->get('userdata')["level"] == "mahasiswa") {
            return redirect()->to('/mahasiswa/dashboard');
        }
    }
}
