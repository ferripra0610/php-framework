<?php 

namespace App\Controllers\Ext;

trait Helper {

    public $page;
    public $size;
    public $search;
    public $sortBy;
    public $sortDir;

    public function setParameter($request){
        $get = $request->get();
        $this->page = $get['page'] ?? 1;
        $this->size = $get['size'] ?? 10;
        $this->search =  $get['search'] ?? null;
        $this->sortBy = $_GET['sort_by'] ?? '';
        $this->sortDir = $_GET['sort_dir'] ?? 'asc';
    }


}