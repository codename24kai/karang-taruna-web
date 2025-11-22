<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PengajuanController extends Controller
{
    public function index()
    {
        $proposals = Pengajuan::latest()->paginate(10);
        return view('admin.proposal.index', compact('proposals'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'catatan_admin' => 'nullable|string'
        ]);

        $proposal = Pengajuan::findOrFail($id);
        $proposal->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->back()->with('success', 'Status proposal diperbarui.');
    }

    public function destroy($id)
    {
        $proposal = Pengajuan::findOrFail($id);

        // Hapus file PDF biar server gak penuh
        if ($proposal->file_proposal && file_exists(public_path($proposal->file_proposal))) {
            unlink(public_path($proposal->file_proposal));
        }

        $proposal->delete();
        return redirect()->back()->with('success', 'Proposal dihapus.');
    }
}
