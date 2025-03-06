<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BookResource::collection(Book::with('author')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author_id = auth()->id();
        $book->save();
        if ($request->hasFile('image')) {
            $images = is_array($request->file('image')) ? $request->file('image') : [$request->file('image')];
            foreach ($images as $image) {
                $path = $this->uploadFile($image, "bookImages");
                $book->images()->create(['url' => $path]);
            }
        }
        return response()->json([
            "message" => "Book created successfully",
            "data" => new BookResource($book)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return new BookResource($book->load('author', 'images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        if (auth()->id() !== $book->author_id) {
            return response()->json([
                "message" => "You can't edit this book"
            ], 403);
        }
        $book->title = $request->title;
        $book->description = $request->description;
        $book->update();
        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $paths = $book->images()->pluck("url");
        foreach ($paths as $path) {
            $this->deletePhoto($path);
        }
        $book->images()->delete();
        $book->delete();

        return response()->json([
            "message" => "Book deleted successfully"
        ]);
    }
}
