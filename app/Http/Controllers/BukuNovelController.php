<?php

namespace App\Http\Controllers;

use App\Models\BukuNovel;
use Illuminate\Http\Request;

class BukuNovelController extends Controller
{
    /**
     * Menampilkan daftar buku novel dalam format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $bukuNovels = BukuNovel::all();

        return response()->json($bukuNovels);
    }

    /**
     * Menyimpan buku novel baru ke dalam basis data dalam format JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul_novel' => 'required|string',
            'pengarang_novel' => 'required|string',
            'penerbit_novel' => 'required|string',
            'novel_terbit' => 'required|string',
            'jumlah_view_novel' => 'nullable|integer',
            'image_novel' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $client = new \GuzzleHttp\Client();
    
        $response = $client->request('POST', 'https://api.imgur.com/3/image/', [
            'headers' => [
                'Authorization' => 'Client-ID ec1c3ef21b6692e',
            ],
            'multipart' => [
                [
                    'name' => 'image',
                    'contents' => fopen($request->file('image_novel')->getPathname(), 'r'),
                ],
            ],
        ]);
    
        $responseJson = json_decode($response->getBody()->getContents(), true);
        $imageUrl = $responseJson['data']['link'];
    
        $bukuNovel = new BukuNovel;
        $bukuNovel->judul_novel = $validatedData['judul_novel'];
        $bukuNovel->pengarang_novel = $validatedData['pengarang_novel'];
        $bukuNovel->penerbit_novel = $validatedData['penerbit_novel'];
        $bukuNovel->novel_terbit = $validatedData['novel_terbit'];
        $bukuNovel->jumlah_view_novel = $validatedData['jumlah_view_novel'];
        $bukuNovel->image_novel = $imageUrl;
        $bukuNovel->save();
    
        return response()->json(['message' => 'Buku novel berhasil ditambahkan']);
    }
    

    /**
     * Menampilkan detail buku novel dalam format JSON.
     *
     * @param  \App\Models\BukuNovel  $bukuNovel
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(BukuNovel $bukuNovel)
    {
        return response()->json($bukuNovel);
    }

    /**
     * Memperbarui buku novel di dalam basis data dalam format JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BukuNovel  $bukuNovel
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'judul_novel' => 'required|string',
            'pengarang_novel' => 'required|string',
            'penerbit_novel' => 'required|string',
            'novel_terbit' => 'required|string',
            'jumlah_view_novel' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bukuNovel = BukuNovel::findOrFail($id);

        if ($request->hasFile('image')) {
            $client = new \GuzzleHttp\Client();

            $response = $client->request('POST', 'https://api.imgur.com/3/image/', [
                'headers' => [
                    'Authorization' => 'Client-ID ec1c3ef21b6692e',
                ],
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($request->file('image')->getPathname(), 'r'),
                    ],
                ],
            ]);

            $responseJson = json_decode($response->getBody()->getContents(), true);
            $imageUrl = $responseJson['data']['link'];

            if ($bukuNovel->image_novel) {
                Storage::disk('public')->delete($bukuNovel->image_novel);
            }

            $bukuNovel->image_novel = $imageUrl;
        }

        $bukuNovel->judul_novel = $validatedData['judul_novel'];
        $bukuNovel->pengarang_novel = $validatedData['pengarang_novel'];
        $bukuNovel->penerbit_novel = $validatedData['penerbit_novel'];
        $bukuNovel->novel_terbit = $validatedData['novel_terbit'];
        $bukuNovel->jumlah_view_novel = $validatedData['jumlah_view_novel'];
        $bukuNovel->save();

        return response()->json(['message' => 'Buku novel berhasil diperbarui']);
    }

    /**
     * Menghapus buku novel dari basis data dalam format JSON.
     *
     * @param  \App\Models\BukuNovel  $bukuNovel
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BukuNovel $bukuNovel)
    {
        $bukuNovel->delete();

        return response()->json(['message' => 'Buku novel berhasil dihapus']);
    }

    public function indexDescending()
    {
        $bukuNovel = BukuNovel::orderBy('jumlah_view_novel', 'desc')->get();

        return response()->json($bukuNovel);
    }

    public function showByTotalNovel()
    {
        $totalNovel = BukuNovel::count();
        $totalNovelString = strval($totalNovel);
    
        return response()->json(['total_novel' => $totalNovelString]);
    }
    
    public function showByTotalView()
    {
        $totalView = BukuNovel::sum('jumlah_view_novel');
    
        return response()->json(['total_view' => $totalView]);
    }    
}
