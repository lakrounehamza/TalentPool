<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\RecruteurRepositorie;

class RecruteurController extends Controller
{
    private $recruteurRepository;
    public function __construct(RecruteurRepositorie $recruteurRepository)
    {
        $this->recruteurRepository = $recruteurRepository;
    }
    public function recruteur() 
    { 
        $data = $this->recruteurRepository->recruteur();
        return response()->json($data);
    }
}
