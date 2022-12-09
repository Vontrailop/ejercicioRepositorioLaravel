<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AutorController extends Controller
{
    //

    public function index()
    {
        $autores = Autor::orderBy('name')->get();
        return $this->getResponse200($autores);
    }

    public function store(Request $request)
    {

        $autor = new Autor();
        $autor->name = $request->name;
        $autor->first_surname = $request->first_surname;
        $autor->second_surname = $request->second_surname;
        $autor->save();

        return $this->getResponse201("autor", "created", $autor);
    }

    public function update(Request $request, $id)
    {
        $autor = Autor::find($id);
        DB::beginTransaction();
        try {
            if ($autor) {
                $autor->name = $request->name;
                $autor->first_surname = $request->first_surname;
                $autor->second_surname = $request->second_surname;
                $autor->update();
                DB::commit();
                return $this->getResponse201("autor", "updated", $autor);
            } else {
                return $this->getResponse404();
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->getResponse500();
        }
    }

    public function show($id)
    {
        $autor = Autor::where("id", $id)->get();
        if ($autor != null) {
            return $this->getResponse200($autor);
        }
        return $this->getResponse404();
    }

    public function delete($id)
    {
        $autor = Autor::find($id);
        if ($autor) {
            foreach($autor->books as $item){
                $autor->books()->detach($item->id);
            }
            $autor->delete();
            return $this->getResponseDelete200("autor");
        } else {
            return $this->getResponse404();
        }
    }
}
