<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan as ModelsLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    private $item;
    private $loan;

    public function __construct(Item $item, ModelsLoan $loan)
    {
        $this->item = $item;
        $this->loan = $loan;
        $this->middleware(['auth', 'verified', 'checkRole:admin,user']);
    }

    /**
     * Tampilkan form peminjaman
     */
    public function create()
    {
        return view('dashboard.loan.add', [
            'datas' => $this->item->getDataReady(),
        ]);
    }

    /**
     * Simpan data peminjaman
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'surat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('surat')) {
            $filename = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $request->file('surat')->getClientOriginalName());
            $request->file('surat')->move(public_path('surat-peminjaman'), $filename);

            $databarang = $this->item->getDataByCode($request->kode_barang);

            if (!$databarang) {
                return redirect()->back()->with('error', 'Barang tidak ditemukan.');
            }

            $data = [
                'peminjam' => Auth::user()->name,
                'name' => $databarang->name,
                'user_id' => Auth::user()->id,
                'kode_barang' => $request->kode_barang,
                'surat' => $filename,
                'kondisi' => $databarang->kondisi,
                'tersedia' => $databarang->tersedia,
            ];

            $this->loan->storeData($data);

            return redirect('/loan')->with('Pesan', 'Data Sukses Dikirim');
        }

        return redirect()->back()->with('error', 'Surat tidak ditemukan.');
    }

    /**
     * Tampilkan daftar peminjaman
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->kelas === 'admin') {
            $datas = $this->loan->getAllData();
        } else {
            $datas = $this->loan->getDataByUserId($user->id);
        }

        return view('dashboard.loan.index', ['datas' => $datas]);
    }

    /**
     * Verifikasi peminjaman oleh admin
     */
    public function loan($id)
    {
        $loan = $this->loan->findById($id);

        if (!$loan) {
            return redirect('/loan')->with('error', 'Data tidak ditemukan.');
        }

        $this->item->updateDataByCode($loan->kode_barang, ['tersedia' => 'tidak']);
        $this->loan->updateData($id, ['tersedia' => 'tidak']);

        return redirect('/loan')->with('Pesan', 'Data Sukses Diverifikasi');
    }

    /**
     * Upload bukti pengembalian
     */
    public function return(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:peminjaman_barang,id',
            'foto' => 'required|file|image|max:2048',
        ]);

        $loan = $this->loan->findById($request->id);

        if ($request->hasFile('foto') && $loan && $loan->foto === null) {
            $filename = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $request->file('foto')->getClientOriginalName());
            $request->file('foto')->move(public_path('foto-kembali'), $filename);

            $data = [
                'tersedia' => 'kembali',
                'foto' => $filename,
            ];

            $this->loan->updateData($loan->id, $data);

            return redirect('/loan')->with('Pesan', 'Data Sukses Dikirim');
        }

        return redirect('/loan')->with('error', 'Upload gagal atau data sudah memiliki foto.');
    }

    /**
     * Konfirmasi bahwa pengembalian selesai
     */
    public function returnbyid($id)
    {
        $loan = $this->loan->findById($id);

        if (!$loan) {
            return redirect('/loan')->with('error', 'Data tidak ditemukan.');
        }

        $this->item->updateDataByCode($loan->kode_barang, ['tersedia' => 'ya']);
        $this->loan->updateData($id, ['tersedia' => 'selesai']);

        return redirect('/loan')->with('Pesan', 'Data Sukses Diselesaikan');
    }
}
