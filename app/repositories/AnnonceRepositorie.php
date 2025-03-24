<?php
namespace App\Repositories;
use App\Models\Annonce;
use App\Contract\AnnonceRepositoryInterface;
class AnnonceRepositorie implements AnnonceRepositoryInterface{

    public function  getAnnonce(Annonce $annonce){
        return  ;
    }
    public function  getAllAnnonce(){}
    public function  deleteAnnonce(Annonce  $annonce){}
    public function  updateAnnonce(Annonce  $annonce , Array $attributes){}
    public function  createAnnonce(Array $attributes){}
    public function  getAnnonceByUser($user){}
    public function  getAnnonceByStatus($status){}
}