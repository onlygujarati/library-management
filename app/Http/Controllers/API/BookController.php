<?php

namespace App\Http\Controllers\API;

use App\Events\BookBorrowed;
use App\Helpers\SeederHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookBorrowResource;
use App\Models\BookBorrow;
use Illuminate\Support\Facades\Cache;


class BookController extends Controller
{




     /**
     * @OA\Get(
     *     path="/api/book-list",
     *     summary="Get paginated list of books",
     *     security={{"bearerAuth":{}}},
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of books with pagination"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */

    public function getList(Request $request)
    {
        $book = Book::query();

        $book->when(request('id'), function ($q) {
            return $q->where('id', request('id'));
        });
        
        $per_page = config('constant.PER_PAGE_LIMIT');
        
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page))
            {
                $per_page = $request->per_page;
            }
            if($request->per_page == -1 ){
                $per_page = $book->count();
            }
        }

        $cacheKey = 'books_list_' . md5($request->fullUrl());

        $book = Cache::remember($cacheKey, 60, function () use ($book, $per_page) {
            return $book->orderBy('id', 'desc')->paginate($per_page);
        });

        // $book = $book->orderBy('id','desc')->paginate($per_page);

        $items = BookResource::collection($book);

        $response = [
            'pagination' => SeederHelper::jsonPaginationResponse($items),
            'data' => $items,
        ];
        
        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/book-detail",
     *     summary="Get book detail",
     *     tags={"Books"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Book detail"),
     *     @OA\Response(response=404, description="Book not found"),
     * )
     */
    public function detail(Request $request)
    {

        $id = $request->id;
        $book = Book::find($id);
        $auth_user = auth()->user();

        // $book_borrow = BookBorrow::where('book_id', $book->id)->where('user_id', $auth_user->id)->first();
        // $book_borrow = $book->bookborrow()->GetborrowedBook()->get();
        
        $response = [ 'message' => __('message.not_found_entry',['form' => __('message.book')]) ];
        
        if ( $book != null ) {
            $book_borrow = $book->bookborrow()->get();
            $response = [ 'data' => new BookResource($book) ];
            if ( $auth_user->hasRole('admin') ) {
                $response['borrow_book_data'] =  BookBorrowResource::collection($book_borrow);
            }
        }
        
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/create-book",
     *     summary="Create a new book",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author_name", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Book has been create successfully"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(BookRequest $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => __('message.permission_denied')]);
        }

        // $id = $request->id;
        $book = Book::create($request->all());
        Cache::flush(); 
        // $book = Book::updateOrCreate(['id',$request->id],$request->all());
        $response = [
            'message' => __('message.save_form', ['form' => __('message.book')]),
            'data' => new BookResource($book),
        ];
        
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/update-book",
     *     summary="Update a book",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="author_name", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Book has been updated successfully"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(BookRequest $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => __('message.permission_denied')]);
        }

        $id = $request->id;
        $book = Book::find($id);

        $response = [ 'message' => __('message.not_found_entry',['form' => __('message.book')]) ];

        if ( $book != null ) {
            $book->fill($request->all())->update();
            Cache::flush(); 
            $response = [
                'message' => __('message.update_form',['form' => __('message.book')]),
                'data' => new BookResource($book),
            ];
        }
        
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/delete-book",
     *     summary="Delete a book",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Book has been Delete successfully"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => __('message.permission_denied')]);
        }

        $id = $request->id;
        $book = Book::find($id);

        $message = __('message.not_found_entry',['form' => __('message.book')]);
        if ( $book != null ) {
            $book->delete();
            Cache::flush();
            $message = __('message.delete_form',['form' => __('message.book')]);
        }
        
        return response()->json([ 'message' => $message ]);
    }

    /**
     * @OA\Post(
     *     path="/api/books-borrow",
     *     summary="Borrow a book",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"book_id"},
     *             @OA\Property(property="book_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Book borrowed successfully"),
     *     @OA\Response(response=400, description="Book not available"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function borrow(Request $request)
    {
        $id = $request->id;
        $auth_user = auth()->user();
        $book = Book::find($id);

        if ( $book == null ) {
            return response()->json(['message' => __('message.not_found_entry',['form' => __('message.book')])], 200);
        }

        $book_borrow = $book->bookborrow()->where('user_id', $auth_user->id)->first();
        if ( $book_borrow ) {
            return response()->json(['message' => __('message.book_alredy_borrowed')], 200);
        }else{
            BookBorrow::create([ 'user_id' => $auth_user->id, 'book_id' => $book->id ]);
        }

        event(new BookBorrowed($book,$auth_user));
        Cache::flush();

        return response()->json(['message' => __('message.book_borrowed_succescc')]);
    }

    /**
     * @OA\Post(
     *     path="/api/books-return",
     *     summary="Return a borrowed book",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"book_id"},
     *             @OA\Property(property="book_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Book returned successfully"),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function returnBook(Request $request)
    {
        $id = $request->id;
        $auth_user = auth()->user();
        $book = Book::find($id);

        if ( $book == null ) {
            return response()->json(['message' => __('message.not_found_entry',['form' => __('message.book')])], 200);
        }

        // $book_borrow = BookBorrow::where('book_id', $book->id)->where('user_id', $auth_user->id)->first();
        $book_borrow = $book->bookborrow()->where('user_id', $auth_user->id)->first();
        if ( isset($book_borrow) ){
            $book_borrow->delete();
        }else{
            return response()->json(['message' => __('message.book_alredy_return')], 200);
        }

        event(new BookBorrowed($book,$auth_user));
        Cache::flush();

        return response()->json(['message' => __('message.book_return_succescc')]);
    }

    /**
     * @OA\Get(
     *     path="/api/my-borrow-book",
     *     summary="Get user's borrowed books",
     *     tags={"Books"},
     *     @OA\Response(response=200, description="List of borrowed books"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function myBorrowBook(Request $request)
    {

        $book = Book::MyBookborrowList();

        $book->when(request('id'), function ($q) {
            return $q->where('id', request('id'));
        });
        
        $per_page = config('constant.PER_PAGE_LIMIT');
        
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page))
            {
                $per_page = $request->per_page;
            }
            if($request->per_page == -1 ){
                $per_page = $book->count();
            }
        }

        $cacheKey = 'books_list_' . md5($request->fullUrl());

        $book = Cache::remember($cacheKey, 2, function () use ($book, $per_page) {
            return $book->orderBy('id', 'desc')->paginate($per_page);
        });

        // $book = $book->orderBy('id','desc')->paginate($per_page);

        $items = BookResource::collection($book);

        $response = [
            'pagination' => SeederHelper::jsonPaginationResponse($items),
            'data' => $items,
        ];
        
        return response()->json($response);
    }
}