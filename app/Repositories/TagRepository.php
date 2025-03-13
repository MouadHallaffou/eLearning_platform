<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Interfaces\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index(){
        return Tag::all();
    }

    public function getById($id){
       return Tag::findOrFail($id);
    }

    public function store(array $data){
       return Tag::create($data);
    }

    public function update(array $data,$id){
       return Tag::whereId($id)->update($data);
    }
    
    public function delete($id){
       Tag::destroy($id);
    }
}