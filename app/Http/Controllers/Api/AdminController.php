<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruteur;
use App\Models\Candidate;
use App\Models\Annonce;
use App\Models\Candidature;
use App\Repositories\AdminRepositorie;
class AdminController extends Controller
{
    private $adminRepositorie;
    public function __construct(AdminRepositorie $adminRepositorie)
    {
        $this->adminRepositorie = $adminRepositorie;
    }
   public function globales(){
        $data = $this->adminRepositorie->global();
        return response()->json($data);
       
   }
}
