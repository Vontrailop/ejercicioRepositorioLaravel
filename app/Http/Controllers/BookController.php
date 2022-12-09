<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    //
    public function index()
    {
        //$books = Book::all();
        $books = Book::with('category', 'autores', 'editorials', 'download')->get();
        return $this->getResponse200($books);
    }


    public function store(Request $request)
    {

        //$response = $this->response();
        $isbn = trim($request->isbn);
        $existIsbn = Book::where("isbn", $isbn)->exists();
        if (!$existIsbn) {
            $book = new Book();
            $book->isbn = $isbn;
            $book->title = $request->title;
            $book->published_date = Carbon::now();
            $book->description = $request->description;
            $book->category_id = $request->category["id"];
            $book->editorial_id = $request->editorial["id"];
            $book->save();
            //posteriormente de que se guarda el libro,
            //asociamos el objeto creado con los autores.
            foreach ($request->autores as $item) {
                $book->autores()->attach($item["id"]);
            }
            // $response["error"] = false;
            // $response["message"] = "Your book has ben created!";
            // $response["data"] = $book;
            return $this->getResponse201("book", "created", $book);
        } else {
            return $this->getResponse400("ISBN");
            // $response["message"] = "ISBN duplicated!";
        }
    }

    public function response()
    {
        return [
            "error" => true,
            "message" => "Wrong action!",
            "data" => []
        ];
    }

    public function update(Request $request, $id)
    {
        $response = $this->response();
        $book = Book::find($id);
        DB::beginTransaction();
        try {
            if ($book) {
                $isbn = trim($request->isbn);
                $isbnOwner = Book::where("isbn", $isbn)->first();
                if (!$isbnOwner || $isbnOwner->id == $book->id) {
                    $book->isbn = $isbn;
                    $book->title = $request->title;
                    $book->published_date = Carbon::now();
                    $book->description = $request->description;
                    $book->category_id = $request->category["id"];
                    $book->editorial_id = $request->editorial["id"];
                    $book->update();
                    //Eliminar los registros asociados

                    foreach ($book->autores as $item) {
                        $book->autores()->detach($item->id);
                    }
                    //agregar los registros nuevos
                    foreach ($request->autores as $item) {
                        $book->autores()->attach($item["id"]);
                    }
                    $book = Book::with('category', 'autores', 'editorials')->where("id", $id)->get();
                    return $this->getResponse201("book", "updated", $book);
                    // $response["error"] = false;
                    // $response["message"] = "Your book has been updated!";
                    // $response["data"] = $book;
                } else {
                    return $this->getResponse400("ISBN");
                    // $response["message"] = "ISBN duplicated!";
                }
            } else {
                return $this->getResponse404();
                // $response["message"] = "Not found";
            }

            DB::commit();
        } catch (Exception $e) {
            // $response["message"] = "Rollback transaction";
            return $this->getResponse500();
            DB::rollback();
        }
        // return $response;
    }

    public function show($id)
    {
        $book = Book::with('category', 'autores', 'editorials')->where("id", $id)->get();
        $response = $this->response();
        if ($book) {
            // $response["error"] = false;
            // $response["message"] = "Your book is here!";
            // $response["data"] = $book;
            return $this->getResponse200($book);
            // return $response;
        }
        return $this->getResponse404();
        // $response["error"] = false;
        // $response["message"] = "That book doesn't exist!";
        // return $response;
    }

    public function delete($id)
    {
        $response = $this->response();
        $book = Book::find($id);
        if ($book) {
            foreach ($book->autores as $item) {
                $book->autores()->detach($item->id);
            }
            $book->update();
            $book->delete();
            return $this->getResponseDelete200("book");
            // $response["error"] = false;
            // $response["message"] = "Your delete is done!";
            // return $response;
        }
        return $this->getResponse404();
        // $response["error"] = true;
        // $response["message"] = "That book doesn't exist!";
        // return $response;
    }
}
