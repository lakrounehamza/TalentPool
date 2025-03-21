<?php 
namespace App\Contract ;
use App\Models\Annonce;
interface AnnonceRepositoryInterface{
public function  getAnnonce(Annonce $annonce);
public function  getAllAnnonce();
public function  deleteAnnonce(Annonce  $annonce);
public function  updateAnnonce(Annonce  $annonce , Array $attributes);
public function  createAnnonce(Array $attributes);
}