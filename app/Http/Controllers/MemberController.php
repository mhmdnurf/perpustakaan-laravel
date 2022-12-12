<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Member;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('data-anggota.index', [
            'title' => 'Data Anggota',
            'members' => Member::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('data-anggota.create', [
            'title' => 'Tambah Data Anggota',
            'members' => Member::all()

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $config = [
            'table' => 'members',
            'field' => 'no_anggota',
            'length' => 8,
            'prefix' => 'AGG-'
        ];

        $id = IdGenerator::generate($config);

        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required|max:255',
                'nis' => 'unique:members',
                'tempat_lahir' => 'required|max:255',
                'tanggal_lahir' => 'required',
                'jenis_kelamin' => 'required',
                'alamat' => 'required|max:255',
                'kelas' => 'required'
            ],
            [
                'nis.unique' => 'Silahkan cek data anggota terlebih dahulu'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with('errorMember', $validator->errors()->getMessages())->withInput();
        }
        try {
            Member::create([
                'no_anggota' => $id,
                'nama' => $request->nama,
                'nis' => $request->nis,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'kelas' => $request->kelas
            ]);

            Alert::toast('Data Anggota berhasil ditambah!', 'success')->position('top')->autoClose(5000)->timerProgressBar()->hideCloseButton();
            return redirect('data-anggota');
        } catch (\Exception $e) {
            Alert::toast('Data Anggota gagal ditambah!', 'error')->position('top')->autoClose(5000)->timerProgressBar()->hideCloseButton();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member::find($id);
        view()->share('member', $member);
        $pdf = PDF::loadview('data-anggota.kartu-anggota')->setPaper('a4', 'portrait');
        return $pdf->stream('kartu-anggota.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $member = Member::find($id);

        return view('/data-anggota.edit', [
            'title' => 'Edit Data Anggota'
        ], compact('member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $member = Member::find($id);

        $rules = [
            'nama' => 'required|max:255',
            'tempat_lahir' => 'required|max:255',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required|max:255'
        ];

        $member->update($request->validate($rules));
        Alert::toast('Data Anggota berhasil diubah!', 'success')->position('top')->autoClose(5000)->timerProgressBar()->hideCloseButton();
        return redirect('/data-anggota');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Member::destroy($id);
        Alert::toast('Data Anggota berhasil dihapus!', 'success')->position('top')->autoClose(5000)->timerProgressBar()->hideCloseButton();
        return redirect('/data-anggota');
    }
}
