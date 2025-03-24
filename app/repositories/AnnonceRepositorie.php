<?php
namespace App\Repositories;
use App\Models\Annonce;
use App\Contract\AnnonceRepositoryInterface;
class AnnonceRepositorie implements AnnonceRepositoryInterface{

    public function __construct(){

    }
    public function  getAnnonce(Annonce $annonce){
        return  $annonce;
    }
    public function  getAllAnnonce(){
        $annonces = Annonce::all();
        return  $annonces;
    }
    public function  deleteAnnonce(Annonce  $annonce){
        $annonce->delete();
    }
    public function  updateAnnonce(Annonce  $annonce , Array $attributes){
        $annonce->update($attributes);
    }
    public function  createAnnonce(Array $attributes){
        Annonce::create($attributes);
    }
    public function  getAnnonceByUser($user){
        $annonces = Annonce::where('recruteur_id',$user->id);
        return  $annonces; 
    }
    public function  getAnnonceByStatus($status){
        $annonces = Annonce::where('status' , $status);
    }
}