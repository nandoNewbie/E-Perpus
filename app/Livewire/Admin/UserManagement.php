<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use App\Exports\StudentClassExport;
use App\Imports\StudentClassImport;

#[Layout('layouts.admin-layout')]

class UserManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $excelFile;

    // Properti Filter & Search
    public $search = '';
    public $filterRole = '';
    public $filterKelas = '';

    // Properti Modal Form CRUD
    public $isOpen = false;
    public $isEditMode = false;
    public mixed $userId, $name, $identity_number, $class = null, $email = null, $role = 'siswa';

    // Properti Modal Import
    public $isImportOpen = false;
    public mixed $fileExcel = null;

    protected $updatesQueryString = ['search', 'filterRole'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->where(function($query) {
                $query->where('class', '!=', 'Alumni')
                    ->orWhereNull('class');
            })

            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('identity_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterRole, function($query) {
                $query->where('role', $this->filterRole);
            })
            ->when($this->filterKelas, function($query) {
                $query->where('class', 'LIKE', $this->filterKelas . '%');
            })
            ->latest()
            ->paginate(10);

        // Mengambil daftar role unik untuk dropdown filter
        $roles = ['siswa', 'guru', 'pustakawan']; 

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    // --- LOGIKA MODAL CRUD ---
    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isOpen = true;
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->class = $user->class;
        $this->identity_number = $user->identity_number;
        $this->role = $user->role;

        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->class = '';
        $this->identity_number = '';
        $this->role = 'siswa';
    }

    public function store()
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'class' => 'nullable|string|max:255',
            'identity_number' => 'required|unique:users,identity_number',
            'role'  => 'required|string',
        ]);

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'class'    => $this->class,
            'identity_number' => $this->identity_number,
            'password' => Hash::make('password123'), // Default password
            'role'     => $this->role,
        ]);

        session()->flash('success', 'Anggota baru berhasil ditambahkan!');
        $this->closeModal();
    }

    public function update()
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $this->userId,
            'class' => 'nullable|string|max:255',
            'identity_number' => 'required|unique:users,identity_number,' . $this->userId,
            'role'  => 'required|string',
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name'  => $this->name,
            'email' => $this->email,
            'class' => $this->class,
            'identity_number' => $this->identity_number,
            'role'  => $this->role,
        ]);

        session()->flash('success', 'Data Anggota berhasil diperbarui!');
        $this->closeModal();
    }

    public function delete(string $id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Anggota berhasil dihapus!');
    }

    // --- LOGIKA MODAL IMPORT EXCEL ---
    public function openImportModal()
    {
        $this->fileExcel = null;
        $this->isImportOpen = true;
    }

    public function closeImportModal()
    {
        $this->isImportOpen = false;
        $this->fileExcel = null;
    }

    public function importExcel()
    {
        $this->validate([
            'fileExcel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new UsersImport, $this->fileExcel->getRealPath());
            session()->flash('success', 'Data Anggota berhasil di-import/di-upsert via Excel!');
            $this->closeImportModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    /**
     * FUNGSI OTOMATIS: Memproses kenaikan kelas massal tingkat 7 & 8, serta kelulusan kelas 9
     */
    public function kenaikanKelasMassal()
    {
        try {
            // 1. Hitung total siswa kelas 9 sebelum diluluskan untuk notifikasi
            $totalSiswaKelas9 = User::where('role', 'siswa')
                                    ->where('class', '9')
                                    ->count();

            // 2. Siswa kelas 9 lulus, ubah data kelasnya menjadi 'Alumni'
            User::where('role', 'siswa')
                ->where('class', '9')
                ->update(['class' => 'Alumni']);

            // 3. Siswa kelas 8 naik ke kelas 9
            User::where('role', 'siswa')
                ->where('class', '8')
                ->update(['class' => '9']);

            // 4. Siswa kelas 7 naik ke kelas 8
            User::where('role', 'siswa')
                ->where('class', '7')
                ->update(['class' => '8']);

            // Berikan notifikasi sukses ke browser
            session()->flash('message', "Berhasil! Proses kenaikan kelas massal selesai. Sebanyak {$totalSiswaKelas9} siswa kelas 9 telah diubah menjadi Alumni.");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses kenaikan kelas: ' . $e->getMessage());
        }
    }


    // public function exportStudents()
    // {
    //     return Excel::download(new StudentClassExport, 'Format_Rolling_Kelas_Siswa.xlsx');
    // }

    // /**
    //  * FUNGSI IMPORT: Memproses file Excel yang di-upload untuk update kelas
    //  */
    // public function importStudents()
    // {
    //     $this->validate([
    //         'excelFile' => 'required|mimes:xlsx,xls,csv|max:10240', // Maksimal file 10MB
    //     ]);

    //     try {
    //         // Eksekusi proses import
    //         Excel::import(new StudentClassImport, $this->excelFile->getRealPath());

    //         // Bersihkan form input setelah sukses
    //         $this->reset('excelFile');

    //         session()->flash('message', 'Berhasil memperbarui data rolling kelas siswa secara massal!');
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Eror Asli: ' . $e->getMessage());
    //     }
    // }

    // public function archiveGrade9()
    // {
    //     try {
    //         // 1. Hitung dulu ada berapa siswa kelas 9 saat ini
    //         $totalSiswaKelas9 = User::where('role', 'siswa')
    //                                 ->where('class', 'LIKE', '9%')
    //                                 ->count();

    //         if ($totalSiswaKelas9 === 0) {
    //             session()->flash('error', 'Tidak ditemukan siswa kelas 9 yang aktif saat ini.');
    //             return;
    //         }

    //         // 2. Eksekusi perubahan massal di database
    //         User::where('role', 'siswa')
    //             ->where('class', 'LIKE', '9%')
    //             ->update(['class' => 'Alumni']);

    //         // 3. Berikan notifikasi sukses beserta jumlah siswa yang diubah
    //         session()->flash('message', "Berhasil meluluskan {$totalSiswaKelas9} siswa kelas 9 menjadi Alumni!");
            
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Gagal memproses kelulusan siswa: ' . $e->getMessage());
    //     }
    // }

}
